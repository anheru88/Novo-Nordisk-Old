# Backend Functional Specifications

> **Purpose:** Complete backend business rules, validation logic, data processing workflows, security rules, and API contracts for the Novo Nordisk CAM Tool.  
> **Audience:** Backend developers building the Laravel 12 / FilamentPHP 3.x replacement.  
> **Source:** Derived from static analysis of the Laravel 6.2 production codebase.

---

## Table of Contents

1. [Authentication & Session Management](#1-authentication--session-management)
2. [Authorization & Permission Rules](#2-authorization--permission-rules)
3. [Discount Calculation Engine](#3-discount-calculation-engine)
4. [Quotation Business Rules](#4-quotation-business-rules)
5. [Negotiation Business Rules](#5-negotiation-business-rules)
6. [Scale-Based Discount Logic](#6-scale-based-discount-logic)
7. [Authorization Workflow Engine](#7-authorization-workflow-engine)
8. [Sales Import & Validation](#8-sales-import--validation)
9. [Credit Note Calculation](#9-credit-note-calculation)
10. [SAP Integration Contracts](#10-sap-integration-contracts)
11. [Background Jobs & Schedulers](#11-background-jobs--schedulers)
12. [Notification System](#12-notification-system)
13. [File Storage Rules](#13-file-storage-rules)
14. [PDF Generation Specifications](#14-pdf-generation-specifications)
15. [AJAX / Quasi-API Contracts](#15-ajax--quasi-api-contracts)
16. [Input Validation Rules](#16-input-validation-rules)
17. [Performance Requirements](#17-performance-requirements)
18. [Security Rules](#18-security-rules)

---

## 1. Authentication & Session Management

### 1.1 Authentication Mechanisms

The system supports three authentication mechanisms (current and target):

| Mechanism | Current | Target | Guard |
|-----------|---------|--------|-------|
| Email/password | Laravel session (web guard) | Laravel Sanctum (web sessions) | `web` |
| SSO | Auth0 Login SDK 5.3 | Auth0 Laravel SDK 7.x | `web` (after Auth0 callback) |
| API tokens | Laravel Passport 9.x (disabled) | Not implemented (UI is FilamentPHP) | N/A |

### 1.2 Web Session Flow

```
User submits login form
  → LoginController@login
  → Auth::attempt(['email', 'password'])
  → On success: regenerate session, redirect to /home
  → On failure: back with errors
Session lifetime: configured in config/session.php (default: 120 minutes)
Remember me: NOT implemented (no remember_token usage in current codebase)
```

**Target (Sanctum):**
```php
// Sanctum manages sessions identically to Laravel's session guard
// No token issuance needed — FilamentPHP uses cookie-based sessions
// config/sanctum.php: stateful domains must include the app domain
```

### 1.3 Auth0 SSO Flow

```
User clicks "Login with Auth0"
  → Redirect to: https://novonordiskco.auth0.com/authorize
      ?client_id={AUTH0_CLIENT_ID}
      &redirect_uri={APP_URL}/auth0/callback
      &response_type=code
      &scope=openid profile email
  → Auth0 authenticates user
  → Redirect to: {APP_URL}/auth0/callback?code={code}
  → NovoUserController@callback:
      1. Exchange code for tokens via Auth0 /token endpoint
      2. Validate ID token (RS256, JWKS from Auth0)
      3. Extract sub (Auth0 user ID), email, name
      4. Find User by email in nvn_users
      5. If not found: create user record (Auth0-provisioned)
      6. Call Auth::login($user) → establish web session
      7. Redirect to /home
```

**JWT Validation Rules (Auth0 tokens):**
- Algorithm: RS256
- JWKS URI: `https://novonordiskco.auth0.com/.well-known/jwks.json`
- Required claims: `sub`, `iss` (must match Auth0 domain), `aud` (must match client_id), `exp`
- Reject if: token expired, signature invalid, issuer mismatch

### 1.4 Auth0 Management API (used for user provisioning)

Endpoints called server-side via Auth0 Management API SDK:

```
POST /api/v2/users
  → Create Auth0 user (triggered when admin creates a user in CAM Tool)
  → Body: { connection: 'Username-Password-Authentication', email, password, name }
  → Returns: Auth0 user object with 'user_id' (sub)

DELETE /api/v2/users/{user_id}
  → Delete Auth0 user (triggered when admin deletes user in CAM Tool)

PATCH /api/v2/users/{user_id}
  → Update user password (triggered by admin password reset)
  → Body: { password: newPassword }

POST /api/v2/dbconnections/change_password
  → Send password reset email to user
```

**Auth0 SDK v7 method signatures (target):**
```php
use Auth0\Laravel\Facade\Auth0;

// Create user
Auth0::getSdk()->management()->users()->create([
    'connection' => 'Username-Password-Authentication',
    'email'      => $email,
    'password'   => $password,
    'name'       => $name,
]);

// Delete user
Auth0::getSdk()->management()->users()->delete($auth0UserId);

// Update password
Auth0::getSdk()->management()->users()->update($auth0UserId, [
    'password' => $newPassword,
]);
```

---

## 2. Authorization & Permission Rules

### 2.1 Role Definitions

| Role | Slug | Description |
|------|------|-------------|
| Administrator | `admin` | Full system access; bypasses all permission checks |
| Sales Administrator | `admin-ventas` | Manage clients, quotations; view all modules |
| Price Administrator | `admin-precios` | Manage products, prices, authorization levels |
| Authorizer | `autorizador` | Approve/reject quotations and negotiations |
| CAM | `cam` | Create quotations and negotiations |
| Analyst | `analista` | View and export reports; view negotiations |
| Query | `consulta` | Read-only access to all non-sensitive data |

### 2.2 Permission Slug Catalog

All permission slugs follow the pattern `{module}.{action}`:

```
# Users module
usuarios.index | usuarios.create | usuarios.edit | usuarios.destroy

# Roles/Permissions module
roles.index | roles.create | roles.edit | roles.destroy
permisos.index | permisos.create | permisos.edit | permisos.destroy

# Parametrization
parametrizacion.index | parametrizacion.create | parametrizacion.edit | parametrizacion.destroy

# Products
productos.index | productos.create | productos.edit | productos.destroy

# Price Lists
listas.index | listas.create | listas.edit | listas.destroy

# Clients
clientes.index | clientes.create | clientes.edit | clientes.destroy

# Quotations
cotizaciones.index | cotizaciones.create | cotizaciones.edit | cotizaciones.destroy

# Negotiations
negociaciones.index | negociaciones.create | negociaciones.edit | negociaciones.destroy

# Authorizations
autorizaciones.index | autorizaciones.aprobar | autorizaciones.pre-aprobar

# Liquidations / Credit Notes
liquidaciones.index | liquidaciones.create | liquidaciones.edit | liquidaciones.destroy

# Reports
reportes.index | reportes.export

# ARP
arp.index | arp.create | arp.edit | arp.destroy

# Sales Management
ventas.index | ventas.create | ventas.edit | ventas.destroy

# Document Repository
repositorio.index | repositorio.create | repositorio.edit | repositorio.destroy

# Document Formats
formatos.index | formatos.create | formatos.edit | formatos.destroy
```

### 2.3 Admin Super-User Bypass

The `admin` role bypasses ALL permission checks. Implement in Laravel 12 via `Gate::before`:

```php
// app/Providers/AppServiceProvider.php → boot()
Gate::before(function (User $user, string $ability): ?bool {
    if ($user->hasRole('admin')) {
        return true; // bypass all policy/gate checks
    }
    return null; // fall through to normal checks
});
```

### 2.4 Authorizer Discount Level

Users with the `autorizador` role have an assigned `disc_level` (1–4) stored in `nvn_users.disc_level` (FK to `nvn_discount_levels.id_level`).

**Business rule:** An authorizer at level N can approve discounts up to the threshold configured for level N in `nvn_product_auth_levels`. An authorizer CANNOT approve discounts that require a level higher than their assigned level.

**Discount level thresholds (typical configuration):**
| Level | Meaning | Typical % Range |
|-------|---------|----------------|
| 1 | Level 1 Authorizer | Up to 5% |
| 2 | Level 2 Authorizer | 5%–10% |
| 3 | Level 3 Authorizer | 10%–15% |
| 4 | Level 4 Authorizer | > 15% (maximum) |

Exact thresholds are configured per product × distribution channel in `nvn_product_auth_levels`.

---

## 3. Discount Calculation Engine

### 3.1 Table: `nvn_product_auth_levels`

| Column | Type | Description |
|--------|------|-------------|
| `id_product` | FK | Product |
| `id_channel` | FK | Distribution channel |
| `id_level` | FK | Discount level (1–4) |
| `max_discount` | DECIMAL | Maximum discount % allowed at this level |

### 3.2 Discount Validation Logic

**Function: `calcProductQuota()` (Quotations) / `calcDiscount()` (Negotiations)**

```
Given: product_id, channel_id, requested_discount%

1. Fetch all rows from nvn_product_auth_levels
   WHERE id_product = product_id AND id_channel = channel_id
   ORDER BY id_level ASC

2. For each level (1, 2, 3, 4):
   - If requested_discount <= level.max_discount:
     - Required approval level = this level
     - BREAK

3. If requested_discount > level4.max_discount:
   - REJECT with error: "Discount exceeds maximum allowed for this product"
   - Do NOT create the line item

4. Assign required_level to the quotation/negotiation line
5. The quotation/negotiation requires an authorizer with disc_level >= required_level
```

### 3.3 `discount_acum` Tracking (Negotiations only)

The `discount_acum` field in `nvn_negotiations_details` tracks the cumulative discount percentage that has been applied to a product-client pair across all active negotiations.

**Accumulation rule:**
```
When creating a new NegotiationDetail for product P + client C:
  1. SUM all existing NegotiationDetails.discount
     WHERE id_product = P
       AND negotiation.id_client = C
       AND negotiation.is_authorized IN (1, 2, 3, 4, 5, 6, 7)  -- active statuses
  2. current_acum = SUM result
  3. proposed_total = current_acum + new_requested_discount

  4. IF proposed_total > max_discount_for_product_channel:
       SET NegotiationDetail.warning = 1
       SET NegotiationDetails.discount_acum = proposed_total
       (Negotiation is SAVED but marked with warning — not blocked)

  5. ELSE:
       SET NegotiationDetail.warning = 0
       SET NegotiationDetail.discount_acum = proposed_total
```

**Warning flag behavior:**
- `warning = 1` on a line is surfaced to the authorizer during approval review
- The authorizer CAN still approve a negotiation with `warning = 1` lines
- `NegotiationErrors` table receives an entry for every `warning = 1` line (audit trail)

### 3.4 `NegotiationErrors` Record Structure

```sql
INSERT INTO nvn_negotiation_errors (
    id_negotiation,
    id_product,
    error_message,
    discount_requested,
    discount_acum,
    max_discount,
    created_at
) VALUES (...);
```

---

## 4. Quotation Business Rules

### 4.1 Quotation Status State Machine

```
0 = Draft           → created but not submitted
1 = Submitted       → sent to pre-authorizer
2 = Pre-Approved    → pre-authorizer approved; sent to Level 1
3 = Level 1 OK      → Level 1 authorizer approved
4 = Level 2 OK      → Level 2 authorizer approved
5 = Level 3 OK      → Level 3 authorizer approved
6 = Level 4 OK      → Level 4 authorizer approved
7 = Fully Approved  → all required levels approved; PDF sent to client
8 = Rejected        → rejected at any level
9 = Expired         → auto-cancelled by scheduler
```

**Transition rules:**
- Only the CREATOR can move 0 → 1
- Only users with `autorizaciones.pre-aprobar` can move 1 → 2
- Level N authorizer can only move N+1 → N+2 (i.e., Level 1 moves 2 → 3)
- Any authorized user can move any status → 8 (reject) with mandatory comment
- Status 9 (Expired) set ONLY by scheduler — never manually

**Required approval levels:**
The number of approval levels required depends on the HIGHEST required level among all line items:
```
max_required_level = MAX(QuotationDetails.required_level)
If max_required_level == 1: statuses needed are 2, 3, 7
If max_required_level == 2: statuses needed are 2, 3, 4, 7
If max_required_level == 3: statuses needed are 2, 3, 4, 5, 7
If max_required_level == 4: statuses needed are 2, 3, 4, 5, 6, 7
```

### 4.2 Quotation Expiry Rules

**Function: `Quotation::updateQuotationsbyDate()` (run daily at 00:05)**

```sql
UPDATE nvn_quotations
SET is_authorized = 9
WHERE quota_date_end < CURDATE()
  AND is_authorized NOT IN (7, 8, 9)  -- skip already finalized
```

**Function: `Quotation::updateQuotationsbyProducts()` (run daily at 00:05)**
```
For each quotation in status 0-6:
  For each QuotationDetail:
    If Product.prod_valid_date_end < today:
      Mark quotation line as expired (product validity expired)
      If ALL lines expired: cancel the quotation (status = 9)
```

### 4.3 Quotation Fields

**`nvn_quotations` key business fields:**

| Field | Type | Rule |
|-------|------|------|
| `quota_consecutive` | INT AUTO | Auto-incremented consecutive number; display as "COT-{year}-{consecutive}" |
| `quota_date_ini` | DATE | Validity start date; must be ≥ today on create |
| `quota_date_end` | DATE | Validity end date; must be > quota_date_ini |
| `id_client` | FK | Required; drives channel_type |
| `id_channel` | FK | Auto-populated from client.id_channel; editable |
| `user_id` | FK | Creator (current logged-in user) |
| `is_authorized` | TINYINT | Status (see state machine above) |
| `email_send` | BOOLEAN | Whether email was sent to client |
| `id_format` | FK | Reference to DocFormat template used for PDF |

### 4.4 Product-in-Quotation Validation

**`getPreviousProduct()` logic:**
```
Query: Does a DIFFERENT active quotation (status NOT IN 8, 9) for the SAME client
       already contain this exact product?
If YES: Display warning to user (allowed to proceed, but informed of overlap)
If NO: No warning
```

**Price loading logic:**
```
On product selection in quotation form:
  1. Find active PricesList (nvn_priceslists WHERE is_active = 1)
  2. Find ProductxPrices WHERE id_product = selected AND id_pricelist = active_list
  3. Return: institutional_price, commercial_price
  4. Display both; user selects which to apply
```

---

## 5. Negotiation Business Rules

### 5.1 Negotiation-Specific Fields

| Field | Table | Description |
|-------|-------|-------------|
| `id_quotation` | `nvn_negotiations` | Reference quotation (source of products) |
| `discount_type` | `nvn_negotiations_details` | `'%'` (percentage) or `'COP'` (absolute value) |
| `discount_acum` | `nvn_negotiations_details` | Cumulative discount for this product-client pair |
| `warning` | `nvn_negotiations_details` | 1 = discount exceeds policy threshold |
| `suj_volumen` | `nvn_negotiations_details` | 1 = subject to volume scale compliance |
| `id_concept` | `nvn_negotiations_details` | FK to nvn_negotiation_concepts (SAP concept) |
| `pdf_content` | `nvn_negotiations` | Pre-rendered PDF HTML content (optimization) |

### 5.2 Negotiation Expiry

**Function: `Negotiation::updateNegotiationsbyDate()` (run daily at 00:10)**
```sql
UPDATE nvn_negotiations
SET is_authorized = 9
WHERE nego_date_end < CURDATE()
  AND is_authorized NOT IN (7, 8, 9)
```

### 5.3 Post-Approval Update (`updateNegotiationsbyAprovations`)

Triggered when a negotiation reaches `is_authorized = 7` (fully approved):

```
For each NegotiationDetail in the approved negotiation:
  1. Re-validate discount against current Product_AuthLevels (in case thresholds changed)
  2. Recalculate discount_acum:
     SUM all NegotiationDetails.discount for same product+client across all active negotations
  3. Update NegotiationDetail.discount_acum = recalculated value
  4. If recalculated > max: set warning = 1, create NegotiationErrors entry
```

### 5.4 Discount Concept Mapping

Every `NegotiationDetail` has an `id_concept` FK to `nvn_negotiation_concepts`:

| Column | Description |
|--------|-------------|
| `sap_concept` | SAP code used in SAP export (e.g., `ZD01`, `ZD02`) |
| `type` | Internal category: `'descuento'`, `'bonificacion'`, etc. |
| `percent` | Default percentage for this concept (can be overridden per line) |
| `name` | Display name |

**Concept-based discount (`negociacionAsistidaxConcepto`):**
```
If the selected concept has a default percent AND suj_volumen = false:
  Apply concept.percent as the discount (no scale lookup needed)
  discount_type = '%'
  id_concept = concept.id
```

---

## 6. Scale-Based Discount Logic

### 6.1 Table Structure

```
nvn_product_scales (id_scale, id_product, id_channel, scale_name)
  └── nvn_product_scales_level (id_scale_level, id_scale, id_um, scale_min, scale_max, scale_discount)

nvn_clients_x_product_scale (id_client, id_product, id_scale)
  └── Assigns a specific scale to a client-product pair
```

### 6.2 Assisted Negotiation Algorithm (`negociacionAsistida`)

```
Input: client_id, product_id, forecast_volume (in units)

1. Find Scale:
   SELECT s.* FROM nvn_product_scales s
   JOIN nvn_clients_x_product_scale cps ON cps.id_scale = s.id_scale
   WHERE cps.id_client = client_id AND cps.id_product = product_id
   LIMIT 1

   IF no scale found: skip this product (no assisted negotiation)

2. Find matching tier:
   SELECT * FROM nvn_product_scales_level
   WHERE id_scale = scale.id_scale
     AND scale_min <= forecast_volume
     AND scale_max >= forecast_volume
   LIMIT 1

   IF no tier matches: use the highest tier (MAX(scale_max))

3. Apply:
   NegotiationDetail.discount = tier.scale_discount
   NegotiationDetail.discount_type = '%'
   NegotiationDetail.suj_volumen = 1
   NegotiationDetail.id_scale_level = tier.id_scale_level
```

### 6.3 Credit Note Scale Matching (`ncEscalas`)

Used AFTER actual sales are imported to calculate credit notes based on realized volume:

```
Input: id_sale (a sales batch record)

1. JOIN nvn_sales_details + nvn_product_scales + nvn_product_scales_level
   ON client_sap_code → nvn_clients_sap_codes → id_client
   ON product_sap_code → nvn_product_sap_codes → id_product

2. GROUP BY id_client, id_product, id_scale
   SUM(volume) as total_volume per group

3. For each group:
   Find matching tier: scale_min <= total_volume <= scale_max
   credit_note_amount = SUM(quantity * net_price) * tier.scale_discount / 100

4. INSERT INTO nvn_credit_notes (batch record)
   INSERT INTO nvn_credit_notes_clients (per-client subtotals)
   INSERT INTO nvn_credit_notes_details (per-product-line detail)
```

---

## 7. Authorization Workflow Engine

### 7.1 Authorizer Assignment

Quotations and negotiations have a SINGLE designated authorizer per document (`id_user_authorizer` in `nvn_quotations` / `nvn_negotiations`). This is the primary Level 1 approver.

**For multi-level approval:** The system tracks which levels have been approved via `nvn_quotation_approvers` / `nvn_negotiation_approvers` tables:

```sql
-- nvn_quotation_approvers
id_approver | id_quotation | user_id | level | approved_at | comment | action (approve/reject)
```

### 7.2 Email-Link Authorization

Authorizers receive an email with a one-click authorization link (no login required):

```
Link format: GET /authorize/{type}/{id}/{token}
  type = 'quotation' | 'negotiation'
  id   = document ID
  token = HMAC-SHA256 signed token: base64(id + user_id + expires_at)

Validation:
  1. Verify HMAC signature with APP_KEY
  2. Check expires_at > now() (token valid 7 days)
  3. Check document status is still pending (not already approved/rejected)
  4. If all pass: show approval form (no login required)
  5. On submit: process approval/rejection as if user were logged in
```

### 7.3 Approval Processing

```php
// On approval action:
function processApproval(Document $doc, User $authorizer, string $decision, ?string $comment) {
    if ($decision === 'approve') {
        $nextStatus = $doc->is_authorized + 1;
        // Check if all required levels reached
        if ($nextStatus > $doc->required_approval_level) {
            $nextStatus = 7; // Fully approved
            $this->generateAndSendPDF($doc);
            $this->notifyCreator($doc, 'approved');
        }
        $doc->update(['is_authorized' => $nextStatus]);
    } elseif ($decision === 'reject') {
        // Comment is MANDATORY for rejection
        if (empty($comment)) throw new ValidationException('Comment required for rejection');
        $doc->update(['is_authorized' => 8]);
        $this->notifyCreator($doc, 'rejected', $comment);
    }
    // Record in approvers log
    ApproverLog::create([...]);
}
```

---

## 8. Sales Import & Validation

### 8.1 Excel File Format (17 required columns)

| # | Column Name | Validation Rule |
|---|-------------|----------------|
| 1 | bill_number | Required, string |
| 2 | client_sap_code | Required; must exist in `nvn_clients_sap_codes` |
| 3 | client_name | Required, string |
| 4 | product_sap_code | Required; must exist in `nvn_product_sap_codes` |
| 5 | product_name | Required, string |
| 6 | quantity | Required, numeric, > 0 |
| 7 | unit_price | Required, numeric, > 0 |
| 8 | net_value | Required, numeric, > 0 |
| 9 | volume | Required, numeric, ≥ 0 |
| 10 | brand | Required, string |
| 11 | payment_term_code | Required; must exist in `nvn_payment_terms.payment_code` |
| 12 | bill_date | Required, valid date |
| 13 | channel | Required, string |
| 14 | department | Optional, string |
| 15 | city | Optional, string |
| 16 | currency | Required; must be `'COP'` or `'DKK'` |
| 17 | year_month | Required; format `YYYYMM` |

### 8.2 Import Processing (Chunked)

```php
// SalesImport implements WithChunkReading, WithValidation, SkipsOnFailure
// Chunk size: 1000 rows
// On failure: row is skipped and error recorded; import continues
// Error collection: array of [row_number, column, error_message]
// Final result: import_count (success), error_count, error_details[]
```

### 8.3 SAP Code Resolution

```sql
-- Resolve client from SAP code
SELECT c.id_client, c.name, c.id_channel
FROM nvn_clients c
JOIN nvn_clients_sap_codes sc ON sc.id_client = c.id_client
WHERE sc.sap_code = :client_sap_code
LIMIT 1;

-- Resolve product from SAP code
SELECT p.id_product, p.prod_name
FROM nvn_products p
JOIN nvn_product_sap_codes ps ON ps.id_product = p.id_product
WHERE ps.sap_code = :product_sap_code
LIMIT 1;
```

---

## 9. Credit Note Calculation

### 9.1 Path A — By Scale (`ncEscalas`)

**Trigger:** Manual action by admin after sales import is confirmed.

```
For each SalesDetail in the selected sale batch:
  1. Resolve id_client via client_sap_code
  2. Resolve id_product via product_sap_code
  3. Find active scale: nvn_clients_x_product_scale WHERE id_client AND id_product
  4. Aggregate: SUM(volume) grouped by (id_client, id_product, id_scale)
  5. Match aggregate volume to scale tier
  6. credit_amount = SUM(net_value) * tier.scale_discount / 100
  7. Store:
     - nvn_credit_notes (batch header: id_sale, total_amount, generated_at)
     - nvn_credit_notes_clients (per-client: id_client, subtotal)
     - nvn_credit_notes_details (per-product: id_product, quantity, amount, discount_pct)
```

### 9.2 Path B — By Bill (negotiation-based)

```
For each SalesDetail in the selected sale batch:
  1. Resolve id_client, id_product (same as Path A)
  2. Find active NegotiationDetail WHERE id_product AND negotiation.id_client
       AND negotiation.is_authorized = 7 (approved)
       AND negotiation.nego_date_ini <= bill_date <= nego_date_end
  3. Apply negotiation discount:
     IF discount_type = '%': credit_amount = net_value * discount / 100
     IF discount_type = 'COP': credit_amount = discount (flat amount per unit * quantity)
  4. Store:
     - nvn_credit_notes (batch)
     - nvn_credit_notes_clients_bills (per-client-bill subtotals)
     - nvn_credit_notes_details_b (per-product-line)
```

---

## 10. SAP Integration Contracts

### 10.1 SAP Export CSV Format

Port `SapExport` class. The CSV must match the SAP upload template EXACTLY:

| Column | Source | Notes |
|--------|--------|-------|
| `KUNNR` | `nvn_clients_sap_codes.sap_code` | SAP client number |
| `MATNR` | `nvn_product_sap_codes.sap_code` | SAP material number |
| `KWERT` | `nvn_credit_notes_details.amount` | Credit amount (positive) |
| `KBETR` | `nvn_negotiations_details.discount` | Discount % |
| `KONWA` | Hardcoded: `'COP'` | Currency |
| `ZTERM` | `nvn_payment_terms.payment_code` | SAP payment term code |
| `KNUMH` | `nvn_negotiation_concepts.sap_concept` | SAP condition type (e.g., `ZD01`) |
| `DATAB` | `nvn_negotiations.nego_date_ini` | Valid-from date |
| `DATBI` | `nvn_negotiations.nego_date_end` | Valid-to date |

**File encoding:** UTF-8 without BOM  
**Delimiter:** Semicolon (`;`)  
**Line ending:** `\r\n`  
**Date format:** `YYYYMMDD`

### 10.2 SAP Excel Export (Multi-sheet)

Port `NotesExport` — generates an `.xlsx` file with these sheets:

| Sheet | Content |
|-------|---------|
| `Notas` | Credit note summary (batch header, client, total) |
| `Detalle` | Line-by-line credit note detail |
| `SAP` | SAP-format data (same columns as CSV above) |

### 10.3 SAP Import Response

Port `SapImport` + `GenSheetImport` — multi-sheet response file from SAP after processing:

- Sheet 1: Successfully processed records (with SAP document numbers)
- Sheet 2: Error records (with SAP error codes and messages)

Processing logic:
```
For each row in success sheet:
  UPDATE nvn_credit_notes SET sap_document = row.sap_doc_number WHERE id = row.id

For each row in error sheet:
  UPDATE nvn_credit_notes SET sap_error = row.error_message WHERE id = row.id
```

---

## 11. Background Jobs & Schedulers

### 11.1 Scheduled Commands

| Command | Schedule | Purpose |
|---------|----------|---------|
| `UpdateQuotationsStatus` | Daily at 00:05 | Expire overdue quotations (status → 9) |
| `UpdateNegotiationStatus` | Daily at 00:10 | Expire overdue negotiations (status → 9) |

**Kernel registration (target Laravel 12):**
```php
// routes/console.php (Laravel 12 uses this instead of Kernel.php)
use Illuminate\Support\Facades\Schedule;

Schedule::command('quotations:update-status')->dailyAt('00:05');
Schedule::command('negotiations:update-status')->dailyAt('00:10');
```

### 11.2 `UpdateQuotationsStatus` Logic

```php
// app/Console/Commands/UpdateQuotationsStatus.php

public function handle(): void
{
    // Expire by date
    Quotation::where('quota_date_end', '<', now()->toDateString())
        ->whereNotIn('is_authorized', [7, 8, 9])
        ->update(['is_authorized' => 9]);

    // Expire by product validity
    $quotations = Quotation::whereNotIn('is_authorized', [7, 8, 9])->get();
    foreach ($quotations as $quotation) {
        $hasValidProduct = $quotation->details()
            ->join('nvn_products', 'nvn_products.id_product', '=', 'nvn_quotations_details.id_product')
            ->where('nvn_products.prod_valid_date_end', '>=', now()->toDateString())
            ->exists();

        if (!$hasValidProduct) {
            $quotation->update(['is_authorized' => 9]);
        }
    }
}
```

### 11.3 `FixPngProfiles` Command

**Purpose:** Batch-fix corrupted PNG metadata in uploaded firm/signature images.  
**Usage:** Manual (not scheduled); run after bulk signature uploads to fix PNG profile errors.

```bash
php artisan images:fix-png-profiles
```

**Logic:** Iterates over `storage/app/public/firms/`, uses Intervention Image to strip/re-encode PNG profile data, resaves file.

### 11.4 `MigrateFirmImages` Command

**Purpose:** One-time migration command to move firm images from old storage path to new S3 path.  
**Usage:** Run once during Phase 1 deployment.

```bash
php artisan images:migrate-firms
```

### 11.5 Queue Configuration

```
Driver: SQS (AWS) in production; database in local/staging
Queue names:
  - default    : General jobs
  - exports    : Excel export jobs (potentially long-running)
  - emails     : Notification email jobs
  - pdf        : PDF generation jobs

Worker config: php artisan queue:work --queue=exports,emails,pdf,default
```

---

## 12. Notification System

### 12.1 Real-time Notifications (Pusher)

**Event:** `OrderNotificationsEvent` extends `ShouldBroadcast`

```php
// Broadcast channel: 'notifications.{user_id}'
// Event name: 'OrderNotification'
// Payload:
{
  "type": "quotation_approved" | "quotation_rejected" | "negotiation_approved" | "negotiation_rejected" | "new_pending",
  "document_id": int,
  "document_type": "quotation" | "negotiation",
  "message": "string",
  "created_at": "ISO 8601 timestamp"
}
```

**Listener:** `OrderNotificationsListener`
- Stores notification in `nvn_notifications` table
- Broadcasts via `broadcast(new OrderNotificationsEvent(...))->toOthers()`

**Target (Laravel 12):** Option A — upgrade Pusher SDK to 7.x (same Pusher channels). Option B — migrate to Laravel Reverb (self-hosted WebSocket server).

### 12.2 In-App Notification Model

```sql
-- nvn_notifications
id | user_id | type | document_id | document_type | message | read_at | created_at
```

**Unread count query:**
```sql
SELECT COUNT(*) FROM nvn_notifications
WHERE user_id = :user_id AND read_at IS NULL;
```

**Mark as read:**
```sql
UPDATE nvn_notifications SET read_at = NOW()
WHERE id = :id AND user_id = :user_id;
```

### 12.3 Email Notifications

All email notifications use Laravel's `Notification` system via `Mailable` classes:

| Trigger | Mailable | Recipients |
|---------|----------|------------|
| Quotation submitted | `QuotationPendingNotification` | Assigned authorizer |
| Quotation approved (final) | `QuotationApprovedNotification` | Creator + client email |
| Quotation rejected | `QuotationRejectedNotification` | Creator |
| Negotiation submitted | `NegotiationPendingNotification` | Assigned authorizer |
| Negotiation approved (final) | `NegotiationApprovedNotification` | Creator |
| Negotiation rejected | `NegotiationRejectedNotification` | Creator |
| Document shared | `SharedDocumentNotification` | Recipient email (entered by user) |

**Mail driver:** SES (AWS) in production; Mailgun in staging; `log` in local.

---

## 13. File Storage Rules

### 13.1 Current Storage (Laravel 6)

| File Type | Path | Access |
|-----------|------|--------|
| User signatures | `storage/app/public/firms/{user_id}.{ext}` | Public URL via `/storage/firms/` |
| Client files | `storage/app/public/clients/{client_id}/{filename}` | Public URL |
| Quotation docs | `storage/app/public/quotations/{quotation_id}/{filename}` | Public URL |
| Negotiation docs | `storage/app/public/negotiations/{negotiation_id}/{filename}` | Public URL |
| Document repository | `storage/app/public/repository/{folder_id}/{filename}` | Public URL |
| Generated PDFs | `storage/app/public/pdfs/{type}/{id}.pdf` | Temporary download |
| SAP export files | `storage/app/public/sap/{batch_id}.csv` | Temporary download |

### 13.2 Target Storage (Laravel 12 + S3)

| File Type | S3 Key Prefix | Visibility |
|-----------|--------------|-----------|
| User signatures | `firms/{user_id}/{filename}` | Private (pre-signed URL) |
| Client files | `clients/{client_id}/{filename}` | Private (pre-signed URL) |
| Quotation docs | `quotations/{quotation_id}/{filename}` | Private (pre-signed URL) |
| Negotiation docs | `negotiations/{negotiation_id}/{filename}` | Private (pre-signed URL) |
| Document repository | `repository/{folder_id}/{filename}` | Private (pre-signed URL) |
| Generated PDFs | `pdfs/{type}/{id}/{uuid}.pdf` | Private (pre-signed URL, 1-hour expiry) |
| SAP export files | `sap-exports/{batch_id}/{filename}` | Private (pre-signed URL, 1-hour expiry) |

### 13.3 Signed URL Generation

```php
// For shareable document links (user-controlled expiry)
Storage::disk('s3')->temporaryUrl(
    $path,
    now()->addDays(7),  // Document repository sharing: 7 days
);

// For PDF downloads (short-lived)
Storage::disk('s3')->temporaryUrl(
    $path,
    now()->addHour(),   // PDF downloads: 1 hour
);
```

### 13.4 File Validation Rules

| File Type | Allowed MIME Types | Max Size |
|-----------|-------------------|----------|
| Signature images | `image/png`, `image/jpeg` | 2 MB |
| Client documents | `application/pdf`, `image/png`, `image/jpeg`, `application/msword`, `application/vnd.openxmlformats-officedocument.wordprocessingml.document` | 10 MB |
| SAP import files | `application/vnd.ms-excel`, `application/vnd.openxmlformats-officedocument.spreadsheetml.sheet` | 25 MB |
| Sales import files | Same as SAP | 50 MB |
| ARP import files | Same as SAP | 25 MB |

---

## 14. PDF Generation Specifications

### 14.1 DocFormat Template Structure

PDFs for quotations and negotiations are generated from `nvn_doc_formats` templates using DOMPDF.

**`nvn_doc_formats` fields used in PDF:**

| Field | Used For |
|-------|---------|
| `body` | Main body HTML (Blade template with `@{{ variable }}` placeholders) |
| `conditions_time` | Time-based conditions section |
| `conditions_content` | Content conditions section |
| `conditions_special` | Special conditions section |
| `terms_title` | Terms section title |
| `terms_content` | Terms and conditions body |
| `sign_img` | Path to signatory image |
| `sign_name` | Signatory name (printed below signature) |
| `sign_charge` | Signatory title/position |
| `footer` | Footer text (page X of Y) |

### 14.2 Template Variables

Available in Blade template for quotation PDFs:

```
{{ $quotation->quota_consecutive }}   → Document number
{{ $quotation->client->name }}         → Client name
{{ $quotation->client->nit }}          → Client NIT
{{ $quotation->quota_date_ini }}       → Valid from
{{ $quotation->quota_date_end }}       → Valid to
{{ $quotation->details }}              → Collection of product lines
{{ $detail->product->prod_name }}      → Product name
{{ $detail->discount }}               → Discount %
{{ $detail->net_price }}              → Net price after discount
{{ $detail->subtotal }}               → Line subtotal
{{ $quotation->total }}               → Grand total
{{ $user->name }}                     → Creator name
```

### 14.3 PDF Generation Process

```php
// Controller logic to port
public function generatePDF(Quotation $quotation): Response
{
    $format = DocFormat::where('is_active', 1)->first();
    $html = view('pdfs.quotation', [
        'quotation' => $quotation->load('details.product', 'client'),
        'format'    => $format,
    ])->render();

    $pdf = Pdf::loadHtml($html)
        ->setPaper('letter', 'portrait')
        ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

    $filename = "COT-{$quotation->id_quotation}.pdf";
    Storage::disk('s3')->put("pdfs/quotations/{$filename}", $pdf->output());

    return Storage::disk('s3')->temporaryUrl("pdfs/quotations/{$filename}", now()->addHour());
}
```

### 14.4 Certificate PDF (Clients)

Port `ClientsController::createPDFCertificate()`:

```php
// Uses nvn_format_certificates template
// Available variables:
{{ $client->name }}          → Client name
{{ $client->nit }}           → Client NIT
{{ $client->channel->name }} → Channel name
{{ $certificate->header }}   → Certificate header (rich text)
{{ $certificate->body }}     → Certificate body (rich text)
{{ $certificate->footer_col1 }} → Footer column 1
{{ $certificate->footer_col2 }} → Footer column 2
{{ $certificate->footer_col3 }} → Footer column 3
{{ $user->firm_img }}         → Signatory firm image (S3 signed URL)
```

---

## 15. AJAX / Quasi-API Contracts

The current system uses AJAX calls to web routes for dynamic data loading (no formal REST API). These must be ported as Livewire actions in FilamentPHP:

### 15.1 Product Lookup Endpoints (to port as Livewire actions)

**Get products for client's channel:**
```
Current: POST /quotations/get-products-client
Input:  { id_client: int }
Output: [{ id_product, prod_name, prod_sap_code, institutional_price, commercial_price, ... }]
Target: Livewire action updatedIdClient() on QuotationResource form
```

**Calculate product price with discount:**
```
Current: POST /quotations/calc-product-quota
Input:  { id_product, discount, discount_type, id_client, id_pricelist }
Output: { net_price, subtotal, required_level, warning }
Target: Livewire action calcProductPrice() triggered by discount field change
```

**Get payment form details:**
```
Current: POST /quotations/get-pay-form
Input:  { id_payment }
Output: { payment_name, payment_percent, payment_code }
Target: Filament Select with getOptionLabel() or afterStateUpdated()
```

**Get product pricing history:**
```
Current: POST /quotations/get-history-product
Input:  { id_product, id_client }
Output: [{ date, price, discount, negotiation_id }]
Target: Livewire action showProductHistory() → opens modal with table
```

### 15.2 Location Lookup (to port as Filament reactive select)

```
Current: GET /locations/get-cities?id_department={id}
Output: [{ id_city, city_name }]
Target: Filament Select ->options(fn($get) => Location::getCities($get('id_department')))
        with ->reactive() on the department select
```

### 15.3 Client Info Lookup

```
Current: POST /clients/get-client-info
Input:  { id_client }
Output: { id_channel, channel_name, payment_terms: [...] }
Target: Livewire action updatedIdClient() on form → sets channel and loads payment terms
```

### 15.4 Notification Count (Pusher + polling)

```
Current: GET /notifications/count → { unread_count: int }
Target: Filament custom notification widget with 30-second polling interval
        OR Pusher broadcast to update badge count in real-time
```

---

## 16. Input Validation Rules

### 16.1 User Management

```php
// Create User
'name'        => 'required|string|max:255',
'email'       => 'required|email|unique:nvn_users,email',
'password'    => 'required|min:8|confirmed',
'id_role'     => 'required|exists:roles,id',
'disc_level'  => 'nullable|exists:nvn_discount_levels,id_level', // required if role = autorizador
'position'    => 'nullable|string|max:255',
'firm_img'    => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
```

### 16.2 Quotation

```php
'id_client'       => 'required|exists:nvn_clients,id_client',
'quota_date_ini'  => 'required|date|after_or_equal:today',
'quota_date_end'  => 'required|date|after:quota_date_ini',
'id_authorizer'   => 'required|exists:nvn_users,id|user_has_role:autorizador',
// Per product line:
'details.*.id_product' => 'required|exists:nvn_products,id_product',
'details.*.discount'   => 'required|numeric|min:0|max:100',
'details.*.id_payment' => 'required|exists:nvn_payment_terms,id_payment',
```

### 16.3 Negotiation

```php
'id_client'        => 'required|exists:nvn_clients,id_client',
'id_quotation'     => 'nullable|exists:nvn_quotations,id_quotation',
'nego_date_ini'    => 'required|date|after_or_equal:today',
'nego_date_end'    => 'required|date|after:nego_date_ini',
// Per detail line:
'details.*.id_product'      => 'required|exists:nvn_products,id_product',
'details.*.id_concept'      => 'required|exists:nvn_negotiation_concepts,id_concept',
'details.*.discount'        => 'required|numeric|min:0',
'details.*.discount_type'   => 'required|in:%,COP',
'details.*.suj_volumen'     => 'boolean',
```

### 16.4 Client

```php
'name'         => 'required|string|max:255',
'nit'          => 'required|string|unique:nvn_clients,nit|max:20',
'id_channel'   => 'required|exists:nvn_dist_channels,id_channel',
'id_type'      => 'required|exists:nvn_client_types,id_type',
'id_department'=> 'required|exists:nvn_locations,id_location',
'id_city'      => 'required|exists:nvn_locations,id_location',
'email'        => 'nullable|email',
'phone'        => 'nullable|string|max:20',
'credit_flag'  => 'boolean',
'is_active'    => 'boolean',
```

### 16.5 Product

```php
'prod_name'         => 'required|string|max:255',
'prod_commercial'   => 'nullable|string|max:255',
'prod_generic'      => 'nullable|string|max:255',
'id_brand'          => 'required|exists:nvn_brands,id_brand',
'id_line'           => 'required|exists:nvn_product_lines,id_line',
'id_um'             => 'required|exists:nvn_product_measure_units,id_um',
'prod_valid_date_ini' => 'nullable|date',
'prod_valid_date_end' => 'nullable|date|after:prod_valid_date_ini',
'institutional_price' => 'required|numeric|min:0',
'commercial_price'    => 'required|numeric|min:0',
'max_increment'       => 'nullable|numeric|min:0|max:100',
'is_regulated'        => 'boolean',
'is_obesity'          => 'boolean',
'is_insumo'           => 'boolean',
```

---

## 17. Performance Requirements

### 17.1 Response Time Targets

| Operation | Target P95 | Max Acceptable |
|-----------|------------|---------------|
| Page load (Filament resources) | < 1.5s | 3s |
| Product search in quotation form | < 500ms | 1s |
| Discount calculation (per line) | < 200ms | 500ms |
| PDF generation (quotation) | < 5s | 15s |
| Excel export (< 10,000 rows) | < 10s | 30s |
| Excel import (< 5,000 rows) | < 20s | 60s |
| SAP export (credit notes) | < 15s | 45s |
| ARP simulation export (multi-sheet) | < 30s | 90s |
| Real-time notification delivery | < 500ms | 2s |

### 17.2 Background Job Limits

| Job | Max Execution Time | Retry Attempts | Retry Delay |
|-----|-------------------|----------------|-------------|
| Excel export | 5 minutes | 2 | 30 seconds |
| PDF generation | 2 minutes | 3 | 15 seconds |
| Email send | 30 seconds | 5 | 60 seconds |
| Sales import (large) | 10 minutes | 1 | N/A |

### 17.3 Concurrency & Scalability

- **Concurrent users:** System designed for 50–100 concurrent users
- **Database connections:** Pool size = 20 (configurable per environment)
- **Cache:** Redis; cache TTL for product/price data = 1 hour
- **Sessions:** Redis-backed (not database) for performance

### 17.4 Uptime & Availability

- **Target SLA:** 99.5% uptime (business hours: Mon–Fri 7am–7pm COT)
- **Scheduled maintenance window:** Saturdays 2am–4am COT
- **Recovery Time Objective (RTO):** < 2 hours
- **Recovery Point Objective (RPO):** < 1 hour (daily DB backups + continuous bin-log replication)

### 17.5 Test Coverage Requirements

| Test Type | Minimum Coverage |
|-----------|----------------|
| Unit tests (business logic) | 85% of controller/service methods |
| Feature tests (FilamentPHP resources) | 100% of Filament resources (list, create, edit, delete) |
| Authorization tests | 100% of permission rules per role |
| Import/Export tests | All import classes with valid + invalid sample files |
| PDF generation tests | All document format templates |
| Scheduler tests | All scheduled commands (simulate date changes) |

---

## 18. Security Rules

### 18.1 Input Sanitization

- All user inputs sanitized via Laravel's `htmlspecialchars` (automatic in Blade)
- Excel imports: strip all formula-starting characters (`=`, `+`, `-`, `@`) from string fields before insertion (CSV injection prevention)
- File uploads: validate MIME type server-side (not client-side only); use `Storage::putFile` (never direct `move`)
- SQL: ALL database queries via Eloquent ORM or `DB::select` with bindings — NEVER string interpolation

### 18.2 Authorization Enforcement

Every Filament resource MUST implement a Policy class. Gate checks are enforced:
- List views: `viewAny` policy method
- Detail views: `view` policy method
- Create forms: `create` policy method
- Edit forms: `update` policy method
- Delete actions: `delete` policy method
- Custom actions (approve, reject, export): custom Gate abilities

**Critical:** Never rely on UI hiding alone. Always enforce authorization at the controller/action level.

### 18.3 Sensitive Data Handling

| Data | Rule |
|------|------|
| User passwords | Hashed with `bcrypt` (cost 12+); never stored or logged in plaintext |
| Auth0 client secret | In `.env` only; never in version control |
| AWS credentials | In `.env` + AWS IAM role (never hardcoded) |
| Pusher app secret | In `.env` only |
| SAP credentials | If file-based (SFTP): credentials in `.env`; use `phpseclib/phpseclib` |
| PDF download URLs | Pre-signed S3 URLs with 1-hour expiry; not publicly guessable paths |
| Document share URLs | HMAC-signed tokens; expire in 7 days |

### 18.4 CSRF Protection

- All Filament forms: CSRF protected automatically by FilamentPHP
- Email-link authorization route: uses signed URL (`URL::signedRoute`) instead of CSRF
- Any custom AJAX routes: must include `@csrf` and validate via middleware

### 18.5 Rate Limiting

```php
// Apply to login endpoint (prevent brute force)
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(10)->by($request->email . $request->ip());
});

// Apply to Auth0 callback (prevent token replay)
RateLimiter::for('auth0-callback', function (Request $request) {
    return Limit::perMinute(20)->by($request->ip());
});
```

### 18.6 Audit Trail

The following actions must be logged to an audit log (model: `nvn_audit_logs` or Laravel Telescope in non-production):

| Action | Log Entry |
|--------|-----------|
| User login / logout | user_id, timestamp, IP, success/failure |
| Quotation status change | user_id, quotation_id, old_status, new_status, comment |
| Negotiation status change | user_id, negotiation_id, old_status, new_status, comment |
| Credit note generated | user_id, batch_id, sale_id, total_amount |
| SAP file exported | user_id, batch_id, filename, exported_at |
| User created/deleted | admin_user_id, target_user_id, action |
| Permission changed | admin_user_id, role_id, permission_id, action |
| File downloaded/shared | user_id, file_path, recipient (if shared), timestamp |

