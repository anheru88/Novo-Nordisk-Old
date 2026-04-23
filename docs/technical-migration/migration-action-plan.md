# Migration Action Plan

> **From:** Laravel 6.2 / PHP 7.2 / Caffeinated Shinobi / Laravel Passport  
> **To:** Laravel 12 / PHP 8.4 / Spatie Laravel Permission / Laravel Sanctum  
> **Strategy:** Incremental module-by-module hot migration (both systems coexist)  
> **Total Duration:** 24 weeks (6 months)

---

## Table of Contents

1. [Migration Principles](#1-migration-principles)
2. [Technology Replacement Map](#2-technology-replacement-map)
3. [Phase Overview](#3-phase-overview)
4. [Phase 1 — Foundation: Auth, Users & Roles](#4-phase-1--foundation-auth-users--roles)
5. [Phase 2 — Master Data: Products, Prices & Clients](#5-phase-2--master-data-products-prices--clients)
6. [Phase 3 — Core Workflow: Quotations](#6-phase-3--core-workflow-quotations)
7. [Phase 4 — Core Workflow: Negotiations & Authorizations](#7-phase-4--core-workflow-negotiations--authorizations)
8. [Phase 5 — Liquidation, Credit Notes & SAP](#8-phase-5--liquidation-credit-notes--sap)
9. [Phase 6 — ARP Simulator & Sales Management](#9-phase-6--arp-simulator--sales-management)
10. [Phase 7 — Document Management & Formats](#10-phase-7--document-management--formats)
11. [Phase 8 — Reporting, Dashboard & Notifications](#11-phase-8--reporting-dashboard--notifications)
12. [Shinobi → Spatie Permission Migration](#12-shinobi--spatie-permission-migration)
13. [Passport → Sanctum Migration](#13-passport--sanctum-migration)
14. [Auth0 SSO Migration](#14-auth0-sso-migration)
15. [Database Migration Strategy](#15-database-migration-strategy)
16. [Testing & Approval Checkpoints](#16-testing--approval-checkpoints)
17. [Cutover Protocol](#17-cutover-protocol)

---

## 1. Migration Principles

1. **Zero destructive changes to the current database** — the `nvn_` prefix tables remain intact and identical until full cutover. The new system connects to the same database during parallel operation, adding new tables only.
2. **Module-by-module delivery** — each phase produces a fully functional, user-testable Filament panel module. No phase is "infrastructure only."
3. **Business logic first** — functional correctness of calculations (discounts, scales, authorization levels) takes priority over UI polish.
4. **100% permission coverage before user testing** — every Filament resource must have its Spatie permissions registered and tested before QA begins.
5. **No data loss** — all historical records (quotations, negotiations, approvals) must be readable in the new system from day one of each module's cutover.
6. **Parallel operation window** — after each phase launches in production, both old and new systems accept traffic for a defined validation window (2 weeks minimum) before the old module is disabled.

---

## 2. Technology Replacement Map

| Current (Laravel 6) | New (Laravel 12) | Migration Complexity |
|--------------------|-----------------|---------------------|
| Caffeinated Shinobi 4.x | Spatie Laravel Permission 6.x | Medium — explicit data migration SQL required |
| Laravel Passport 9.x | Laravel Sanctum 4.x | Low — web sessions only; no API rebuild needed |
| Auth0 Login SDK 5.3 | Auth0 Laravel SDK 7.x | Low — same Auth0 tenant, updated SDK |
| AdminLTE 3 + Blade | FilamentPHP 3.x + Livewire 3 | High — full UI rebuild in Filament |
| Maatwebsite Excel 3.1 | Maatwebsite Excel 3.1+ (ported) | Low — same package, port Export/Import classes |
| barryvdh/laravel-dompdf 0.8 | barryvdh/laravel-dompdf 3.x | Low — updated package, same API |
| Pusher PHP Server 4.x | Pusher PHP Server 7.x / Laravel Reverb | Low/Medium |
| Intervention Image 2.x | Intervention Image 3.x | Low — API changes in v3 |
| alexusmai/laravel-file-manager | Filament native file handling | High — replace with Filament MediaLibrary or custom |
| kyslik/column-sortable | Filament native sorting | Low — Filament Tables has built-in sorting |
| laravelcollective/html | Removed (Filament uses Blade components) | Low |
| realrashid/sweet-alert | Filament native notifications | Low |
| Public disk file uploads | AWS S3 via Storage facade | Medium — path migration required |

---

## 3. Phase Overview

| Phase | Module(s) | Weeks | Deliverable |
|-------|-----------|-------|-------------|
| 1 | Auth + Users + Roles | 1–3 | Login, user management, role/permission assignment |
| 2 | Products + Prices + Clients | 4–7 | Full product catalog, price lists, client registry |
| 3 | Quotations | 8–11 | Quotation creation, approval workflow, PDF |
| 4 | Negotiations + Authorizations | 12–15 | Negotiation workflow, discount calculations |
| 5 | Liquidation + Credit Notes + SAP | 16–18 | Sales import, credit note generation, SAP export |
| 6 | ARP Simulator + Sales Mgmt | 19–21 | ARP simulation import/export |
| 7 | Document Management + Formats | 22–22 | File repository, document templates |
| 8 | Reporting + Dashboard + Notif. | 23–24 | Reports, exports, real-time notifications |

---

## 4. Phase 1 — Foundation: Auth, Users & Roles

**Duration:** Weeks 1–3  
**Dependencies:** None (starting point)  
**Delivers:** A working Filament admin panel with authentication, user management, and role/permission RBAC

### 4.1 Setup Tasks
- [ ] Create new Laravel 12 project
- [ ] Install FilamentPHP 3.x admin panel
- [ ] Install and configure Spatie Laravel Permission
- [ ] Install and configure Laravel Sanctum
- [ ] Configure Auth0 Laravel SDK 7.x
- [ ] Connect to existing database (same credentials, same `nvn_` tables)
- [ ] Configure AWS S3 as default file storage disk
- [ ] Configure Redis for cache and session
- [ ] Set up 3 environments: local, staging, production

### 4.2 Authentication Implementation

**Laravel Sanctum (web sessions):**
```php
// config/auth.php
'guards' => [
    'web' => ['driver' => 'session', 'provider' => 'users'],
    'sanctum' => ['driver' => 'sanctum', 'provider' => 'users'],
],
```

**Auth0 SSO integration:**
- Install `auth0/auth0-php` SDK 8.x and `auth0/login` 7.x
- Configure `Auth0\Laravel\Auth0` service provider
- Re-activate Auth0 callback route: `Route::get('/auth0/callback', ...)`
- Implement `NovoUserController` with updated SDK method signatures
- Validate JWT tokens via Auth0 JWKS endpoint (RS256)

**Filament Panel Auth:**
- Configure `FilamentPanelProvider` to use Sanctum guard
- Implement custom login page using Filament's authentication scaffold
- Add Auth0 login button to Filament login page

### 4.3 Spatie Permission Migration (from Shinobi)

**Step 1 — Install Spatie:**
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

**Step 2 — Seed permissions (run once on staging, then production):**
```sql
-- Migrate roles from Shinobi to Spatie
INSERT INTO spatie_roles (name, guard_name, created_at, updated_at)
SELECT name, 'web', NOW(), NOW()
FROM roles;

-- Migrate permissions
INSERT INTO spatie_permissions (name, guard_name, created_at, updated_at)
SELECT slug, 'web', NOW(), NOW()
FROM permissions;

-- Migrate role-permission assignments
INSERT INTO spatie_role_has_permissions (permission_id, role_id)
SELECT sp.id, sr.id
FROM permission_role pr
JOIN permissions p ON p.id = pr.permission_id
JOIN roles r ON r.id = pr.role_id
JOIN spatie_permissions sp ON sp.name = p.slug
JOIN spatie_roles sr ON sr.name = r.name;

-- Migrate user-role assignments
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT sr.id, 'App\Models\User', ru.user_id
FROM role_user ru
JOIN roles r ON r.id = ru.role_id
JOIN spatie_roles sr ON sr.name = r.name;
```

**Step 3 — Update User model:**
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasRoles;
    // Remove: use HasRolesAndPermissions (Shinobi trait)
}
```

**Step 4 — Update permission checks in controllers:**
```php
// OLD (Shinobi)
auth()->user()->hasPermissionTo('cotizaciones.create')

// NEW (Spatie)
auth()->user()->can('cotizaciones.create')
// or with Gate:
Gate::authorize('cotizaciones.create')
```

**Step 5 — Filament Policies:**
Create a Policy class per resource. Example for `QuotationPolicy`:
```php
public function viewAny(User $user): bool {
    return $user->can('cotizaciones.index');
}
public function create(User $user): bool {
    return $user->can('cotizaciones.create');
}
```

### 4.4 User Management Filament Resource

**`UserResource` must implement:**
- List users with role filter
- Create/edit user with: name, email, position, role assignment, discount_level assignment
- Signature image upload (stored in S3 `firms/` prefix)
- Password reset action
- Role assignment via Filament `Select` with `spatie-laravel-permission` integration

**`RoleResource` must implement:**
- Create/edit roles with permission checkboxes
- Display permission matrix grouped by module
- Special flags: `all-access` equivalent → assign all permissions

### 4.5 Phase 1 Acceptance Criteria
- [ ] Admin can log in via email/password
- [ ] Auth0 SSO login flow works end-to-end
- [ ] All 7 roles exist with correct permissions in Spatie tables
- [ ] Users can be created, edited, and assigned roles
- [ ] Permission-based access: users without permission cannot see menu items
- [ ] Authorization level (disc_level) assigned to authorizer users
- [ ] Session management works (idle timeout, remember me)

---

## 5. Phase 2 — Master Data: Products, Prices & Clients

**Duration:** Weeks 4–7  
**Dependencies:** Phase 1 complete (auth + permissions working)  
**Delivers:** Complete product catalog management, price list versioning, client registry

### 5.1 Master Data Filament Resources

**Parametrization (simple CRUD resources):**
- `BrandResource` — `nvn_brands` (brand_name)
- `ProductLineResource` — `nvn_product_lines` (type_name)
- `MeasureUnitResource` — `nvn_product_measure_units` (unit_name)
- `AditionalUseResource` — `nvn_aditional_terms` (use_name)
- `ChannelTypeResource` — `nvn_dist_channels` (channel_name)
- `ClientTypeResource` — `nvn_client_types` (type_name)
- `PaymentTermResource` — `nvn_payment_terms` (name, percent, payment_code)
- `NegotiationConceptResource` — `nvn_negotiation_concepts` (type, percent, sap_concept, name)
- `LocationResource` — `nvn_locations` (hierarchical: departments → cities)
- `DiscountLevelResource` — `nvn_discount_levels` (level_name)
- `StatusResource` — `nvn_status` (name, color, symbol)

**Product Catalog:**

`ProductResource` — full CRUD on `nvn_products`:
- Fields: name, SAP code, commercial name, generic name, INVIMA, CUM, brand, line, unit, packaging, valid dates, prices (institutional + commercial), max increment %, regulated flag, obesity flag, insumo flag, material, additional use, renovation details
- Relation managers: `SapCodesRelationManager`, `PricesRelationManager`
- Bulk action: Excel import (`ProductsImport` class ported)
- Export action: `ProductsExport` class ported

`PricesListResource` — manage `nvn_priceslists` + `nvn_productxprices`:
- Price list versions with active/inactive flag
- Sub-resource to add/edit product prices within a list
- Bulk price import action (`PricesImport` ported)
- Authorization level configuration per product+channel (`Product_AuthLevels`)

**Scales (Volume Discounts):**

`ScaleResource` — manage `nvn_product_scales` + `nvn_product_scales_level`:
- Select product + channel for the scale
- Add/edit tier levels (min, max, discount %, measure unit)
- Assign scales to client-product pairs via `ClientxProductScale`

**Client Management:**

`ClientResource` — full CRUD on `nvn_clients`:
- Fields: name, NIT, quote name, SAP name, SAP code, channel, type, department, city, contact info, payment terms, credit flag, active status
- Relation managers: `SapCodesRelationManager`, `FilesRelationManager`, `QuotationsRelationManager`
- Action: Generate certificate PDF (`createPDFCertificate` logic ported)
- Action: Bulk client import (`ClientsImport` ported)
- Action: Export clients to Excel (`ClientsExcelExport` ported)

### 5.2 Business Logic to Preserve

**Location hierarchy:**
```php
// Cities load dynamically based on selected department
// Filament Select with ->reactive() and ->options(fn($get) => ...)
// Replicates LocationsController::getCities() and Location::getDepartments()
```

**Client NIT uniqueness:** Validate unique NIT on create/edit.

**Product validity dates:** Warn (not block) when `prod_valid_date_end < today`. Mark expired products in list view.

**SAP code uniqueness:** `nvn_product_sap_codes.sap_code` has UNIQUE constraint — validate on import and manual entry.

### 5.3 Phase 2 Acceptance Criteria
- [ ] All parametrization tables editable in Filament
- [ ] Products CRUD works with all fields and SAP codes
- [ ] Price lists created, versioned, and activated
- [ ] Authorization levels set per product × channel
- [ ] Scales configured with tier levels
- [ ] Clients CRUD with SAP codes and file attachments
- [ ] City dropdown filters by department
- [ ] Bulk Excel import works for products, prices, clients
- [ ] PDF certificate generated correctly for clients

---

## 6. Phase 3 — Core Workflow: Quotations

**Duration:** Weeks 8–11  
**Dependencies:** Phase 2 complete (product + client data available)  
**Delivers:** Full quotation lifecycle from creation to PDF delivery

### 6.1 Quotation Filament Resource

**`QuotationResource` implements:**

**List view:**
- Columns: ID, consecutive #, client, channel, created by, date range, status (colored badge)
- Filters: status, date range, created_by, channel
- Sortable: all columns (replaces `kyslik/column-sortable`)

**Create form workflow:**
1. Select client → auto-populate channel type
2. Set validity dates (ini, end)
3. Select authorizer user (filtered to users with `autorizaciones.aprobar` permission)
4. Add product lines:
   - Select product (filtered by client's channel)
   - Load prices from active price list
   - Enter discount % → validate against `Product_AuthLevels` for client's channel
   - Select payment terms
   - System calculates: net price, total value
5. Save → status = 1 (sent for approval) → notify authorizer

**Key business logic methods to port:**
- `getProductsClient()` → Filament `Select` with `->searchable()` and filter by channel
- `calcProductQuota()` → Livewire action calculating discounted price
- `getPreviousProduct()` → check if product exists in a prior active quotation for this client
- `getPayForm()` → load payment term details
- `getHistoryProduct()` → show pricing history for a product-client pair

**View/Show page:**
- Full quotation summary with all line items
- Status timeline
- Comments section
- Attached documents list
- Actions: Send email, Download PDF, Edit dates, Cancel, Pre-approve (if authorized)

**PDF generation:**
- Port `QuotationsController::createPDF()` logic
- Use `DocFormat` templates stored in DB
- Generate via `barryvdh/laravel-dompdf` 3.x
- Store PDF in S3, return download link

**Email sending:**
- Port `quotationSendEmail()` using updated `QuotationNotification`
- Filament action with modal for recipient email input

### 6.2 Pre-Authorization & Authorization Flow

**`PreAuthorizationResource`:**
- Shows quotations/negotiations in `pre-approved (2)` state
- Pre-authorizer can approve → moves to Level 1 review
- Pre-authorizer can reject → moves to rejected (8) with mandatory comment

**`AuthorizationResource`:**
- Shows pending items for the logged-in authorizer (filtered by `user_id` in `QuotationApprovers`)
- For each item: review details, enter decision (approve/reject), add comment
- On approval: check if all required levels approved → if yes, set `is_authorized=7`, generate PDF, notify creator
- On rejection: set `is_authorized=8`, notify creator immediately

**Email-link authorization (no login):**
- Route: `GET /authorize/{token}` → show simplified approval view
- Token includes quotation/negotiation ID + user ID (signed, expires 7 days)
- Maps to `AutorizationsController::autorizeQuotation()` logic

### 6.3 Status Transitions
```
0 (Draft)
  → 1 (Submitted) — by creator
  → 2 (Pre-approved) — by pre-authorizer
  → 3 (Level 1 approved) — by Level 1 authorizer
  → 4 (Level 2 approved) — by Level 2 authorizer
  → 5 (Level 3 approved) — by Level 3 authorizer
  → 6 (Level 4 approved) — by Level 4 authorizer
  → 7 (Fully Approved) — final state, PDF sent
  → 8 (Rejected) — from any level
  → 9 (Expired) — by scheduler
```

**Scheduler job:**
```php
// In Kernel.php schedule:
$schedule->call(function() {
    Quotation::updateQuotationsbyDate();
    Quotation::updateQuotationsbyProducts();
})->dailyAt('00:05');
```

### 6.4 Phase 3 Acceptance Criteria
- [ ] CAM user can create quotation with multiple product lines
- [ ] Discount validation enforces authorization level thresholds
- [ ] Authorizer receives notification and can approve/reject
- [ ] Full approval chain (levels 1–4) works correctly
- [ ] Quotation PDF generated with correct format template
- [ ] Email sent to client with PDF attachment
- [ ] Expired quotations auto-cancelled by scheduler
- [ ] Edit dates action works without resetting approval status
- [ ] Cancel action available with confirmation
- [ ] Pre-authorization flow functional

---

## 7. Phase 4 — Core Workflow: Negotiations & Authorizations

**Duration:** Weeks 12–15  
**Dependencies:** Phase 3 complete (quotation data available as source)  
**Delivers:** Full negotiation lifecycle with complex discount logic

### 7.1 Negotiation Filament Resource

**`NegotiationResource` — same UX pattern as quotations plus:**

**Create form — additional complexity:**
1. Select client + reference quotation(s)
2. `getProductsClientQuota()` → load products from approved quotation
3. Select discount concept (`NegotiationConcepts` — maps to SAP concept code)
4. Enter discount type: percentage (`discount_type='%'`) or absolute (`discount_type='COP'`)
5. Enter discount value
6. `calcDiscount()` — validate discount logic:
   - Check `Product_AuthLevels` for max discount per product × channel × level
   - Check if discount + existing cumulative discount (`discount_acum`) ≤ max
   - Set `warning=1` on line if threshold exceeded
7. `negociacionAsistida()` — auto-apply scale discounts:
   - For client-product pairs with `ClientxProductScale` assignments
   - Look up `ScalesLevels` for applicable tier based on volume
   - Calculate resulting discount
8. `negociacionAsistidaxConcepto()` — apply concept-specific discount logic
9. `suj_volumen` flag — marks if the product line is subject to volume compliance

**Discount calculation rules:**
- The `id_concept` determines the calculation method
- `discount_acum` tracks cumulative discount across multiple negotiations for the same product-client pair
- `NegotiationErrors` records violations for auditing
- A negotiation CAN be saved with `warning=1` lines, but the warning is surfaced to approvers

### 7.2 Assisted Negotiation (`negociacionAsistida`) Logic
```
For each product line where suj_volumen = true:
  1. Find Scales where product_id = line.id_product AND channel_id = client.id_channel
  2. For each Scale, find ScalesLevels where scale_min <= forecast_volume <= scale_max
  3. Apply scale_discount to line
  4. Record in NegotiationDetails with discount = calculated_discount, discount_type = '%'
```

### 7.3 Post-Negotiation Processing
Same as quotations plus:
- `updateNegotiationsbyAprovations()` — updates ALL `NegotiationDetails` for a negotiation post-approval:
  - Verifies each detail against current scales
  - Updates `discount_acum` in the database
- `pdf_content` column — store pre-generated PDF HTML content (optimization to avoid re-rendering)

### 7.4 Phase 4 Acceptance Criteria
- [ ] Negotiation created from approved quotation with product inheritance
- [ ] Discount validation enforces all `Product_AuthLevels` rules
- [ ] Scale-assisted negotiation calculates correct tier discounts
- [ ] Concept-based discounts apply correctly with SAP concept codes
- [ ] `discount_acum` tracked correctly across multiple negotiations
- [ ] `NegotiationErrors` records created for policy violations
- [ ] Full approval chain mirrors quotation flow
- [ ] PDF generated and emailed
- [ ] File attachments work (`NegotiationDocs`)
- [ ] Expired negotiations auto-cancelled by scheduler

---

## 8. Phase 5 — Liquidation, Credit Notes & SAP

**Duration:** Weeks 16–18  
**Dependencies:** Phase 4 complete (negotiations approved and active)  
**Delivers:** Sales import, scale-based credit note generation, SAP-format file export

### 8.1 Sales Import

**`SalesResource` Filament page (custom page, not standard CRUD):**
1. Upload Excel file (17-column format)
2. Run `SalesImport` — validates all required fields, rejects rows with errors
3. Preview import results (success count, error rows)
4. Confirm import → store in `nvn_sales` + `nvn_sales_details`

**Import validation rules (preserve from `SalesImport`):**
- Required fields: bill number, client SAP code, product SAP code, quantity, price, net value, volume, brand, payment term
- Client SAP code must exist in `nvn_clients_sap_codes`
- Product SAP code must exist in `nvn_product_sap_codes`
- Amounts must be numeric and > 0

### 8.2 Credit Notes (Notas de Crédito)

**Two generation paths:**

**Path A — By Scale (ncEscalas):**
```
Sales::ncEscalas($idSale)
  → JOIN nvn_sales_details + nvn_product_scales + nvn_product_scales_level
  → GROUP BY client_id + product_id + scale_id
  → SUM(volume) per group
  → Match volume sum to scale tier → get discount %
  → Calculate credit note amount = qty * price * discount%
  → Store in nvn_credit_notes → nvn_credit_notes_clients → nvn_credit_notes_details
```

**Path B — By Bill:**
```
For each sale line:
  → Match against active NegotiationDetails for that client+product
  → Apply agreed discount to billed amount
  → Store in nvn_credit_notes → nvn_credit_notes_clients_bills → nvn_credit_notes_details_b
```

### 8.3 SAP Export

**`SapNotesResource` Filament page:**
- List credit note batches
- Action: Generate CSV (SAP-format) → download
- Action: Generate Excel → download
- Action: Delete batch
- Import SAP response file (multi-sheet via `SapImport` / `GenSheetImport`)

**SAP CSV format:** Port `SapExport` class; columns must match SAP upload template exactly.  
**SAP Excel format:** Port `NotesExport` class with multi-sheet structure.

### 8.4 Phase 5 Acceptance Criteria
- [ ] Excel sales import processes correctly with error reporting
- [ ] Scale-based credit notes calculated correctly
- [ ] Bill-based credit notes calculated correctly
- [ ] SAP CSV export matches required format
- [ ] SAP Excel export generates all required sheets
- [ ] SAP import response file processed correctly
- [ ] Credit note batches can be deleted (with confirmation)

---

## 9. Phase 6 — ARP Simulator & Sales Management

**Duration:** Weeks 19–21  
**Dependencies:** Phase 2 complete (brands, products available)  
**Delivers:** Annual Revenue Planning simulator with multi-sheet Excel reports

### 9.1 ARP Configuration

**`ArpResource`** — manage `nvn_arps` (year, std, month_avr):
- One ARP record per year
- Unique year constraint

**`ServiceArpResource`** — manage `nvn_services_arp`:
- Link services to ARP records
- One service per brand grouping

**`ArpServiceResource`** (inline RelationManager):
- Brand × volume × value_cop per service
- `scopeBrandsVolume` scope: filter brands with volume > 0 and value > 0

**`BusinessArpResource`** — manage `nvn_arp_business_case`:
- PBC (business case) values per ARP

### 9.2 ARP Simulation

**`ArpSimulationResource` Filament page:**
1. Select simulation name
2. Upload Excel file → `ArpImport` processes rows into `nvn_arp_simulations_details`
3. View simulation data by: brand, product, client, year/quarter/month, version
4. Export multi-sheet Excel: `ArpSimulationsExport` with sheets:
   - `ArpSimulationsSheets` — detail data with formatting
   - `ArpSpecialSheet` — special ARP version data
   - `ArpVersionSheets` — version comparison

**`ArpSimulationDetail` fields to display:** brand, product, client, cal_year_month, vol_type, forecast_vol, sales_pack_vol, volume, asp_cop, amount_mcop, amount_dkk, currency, net_sales, version, year, quarter, month, BU, group, cluster, region

**`scopeSumDetails($idProduct):`** aggregate volume + amount_mcop grouped by client + product — used in simulation summary view.

### 9.3 Phase 6 Acceptance Criteria
- [ ] ARP configuration (years, services, brand mappings) manageable
- [ ] Excel import processes correctly with validation
- [ ] Simulation data viewable with filters (brand, year, version)
- [ ] Multi-sheet Excel export generates correctly
- [ ] Business case (PBC) values configurable and included in export
- [ ] Special version sheet generated correctly

---

## 10. Phase 7 — Document Management & Formats

**Duration:** Week 22  
**Dependencies:** Phase 1 (auth), Phase 2 (clients)  
**Delivers:** Hierarchical document repository, document format templates, secure file sharing

### 10.1 Document Repository

**`FolderResource`** — manage `nvn_folder_repository`:
- Tree-view navigation (parent/child via `id_parent`)
- Create subfolder within current folder
- Upload files to folder → store in S3 + record in `nvn_doc_repository`
- Generate temporary signed URL for sharing (7-day expiry)
- Email sharing: send signed URL via `sharedDocsSendEmail`
- Breadcrumb navigation in Filament

### 10.2 Client Files

**RelationManager on `ClientResource`:**
- Upload/list/delete files for a client
- Files stored in S3 under `clients/{id_client}/`
- Shareable via signed URL

### 10.3 Document Format Templates

**`DocFormatResource`** — manage `nvn_doc_formats`:
- Rich text editor for: body, conditions_time, conditions_content, conditions_special, terms_title, terms_content
- Signature image upload (stored in S3)
- Sign name, sign charge, footer text
- Active/inactive toggle
- Preview rendered template

**`DocFormatCertificateResource`** — manage `nvn_format_certificates`:
- Country selection
- Header, body, footer rich text sections
- Footer columns (3-column layout)
- User firm reference
- Active/inactive toggle

### 10.4 Phase 7 Acceptance Criteria
- [ ] Document repository folders navigable with breadcrumbs
- [ ] Files uploadable to S3 via Filament
- [ ] Signed temporary URLs generated and emailed correctly
- [ ] Client files managed within client view
- [ ] Quotation/negotiation document templates editable
- [ ] Certificate templates editable per country
- [ ] Template preview renders correctly via DOMPDF

---

## 11. Phase 8 — Reporting, Dashboard & Notifications

**Duration:** Weeks 23–24  
**Dependencies:** All prior phases  
**Delivers:** Comprehensive reports, real-time notifications, final dashboard

### 11.1 Reports

**`ReportResource` Filament page:**
- Report type selector: Quotations, Negotiations, Clients, Sales
- Date range filter, client filter, status filter, channel filter
- Export to Excel: port `ReportExport`, `QuotationExport`, `NegotiationExport`, `ClientsExcelExport`

**Credit Notes Report:**
- Filter by credit note batch, client, date
- Export: `NotesExport` ported

### 11.2 Real-time Notifications

**Choose one approach:**
- **Option A:** Retain Pusher (simplest — just update SDK version)
- **Option B:** Migrate to Laravel Reverb (self-hosted WebSockets — eliminates Pusher cost)

**Notification in-app widget:**
- Filament custom notification widget polling `nvn_notifications` table
- Badge count via `notifycountall` endpoint equivalent
- Mark-as-read action

**Email notifications:** Port all `Notification` classes using Laravel 12 Mail.

### 11.3 Dashboard (FilamentPHP Widgets)

Implement as Filament Dashboard widgets:
- **Stats overview:** Active quotations, pending authorizations, active negotiations, expiring this week
- **Quotation status chart:** Donut chart by status
- **Recent activity:** Latest quotations + negotiations created by current user
- **Pending approvals:** Table of items awaiting the logged-in user's authorization
- **Expiring soon:** Quotations/negotiations expiring in next 7 days

### 11.4 Phase 8 Acceptance Criteria
- [ ] All report types generate correct data
- [ ] Excel exports match current system format
- [ ] Real-time notifications delivered via WebSocket
- [ ] In-app notification bell shows unread count
- [ ] Mark-as-read works
- [ ] Dashboard widgets display accurate data
- [ ] All dashboard filters work

---

## 12. Shinobi → Spatie Permission Migration

### Full Migration Script (run in staging first)
```sql
-- Step 1: Create roles in Spatie format
INSERT INTO spatie_roles (name, guard_name, created_at, updated_at)
VALUES
  ('admin', 'web', NOW(), NOW()),
  ('admin-ventas', 'web', NOW(), NOW()),
  ('admin-precios', 'web', NOW(), NOW()),
  ('autorizador', 'web', NOW(), NOW()),
  ('cam', 'web', NOW(), NOW()),
  ('analista', 'web', NOW(), NOW()),
  ('consulta', 'web', NOW(), NOW());

-- Step 2: Create permissions in Spatie format (slug = name)
INSERT INTO spatie_permissions (name, guard_name, created_at, updated_at)
SELECT slug, 'web', NOW(), NOW()
FROM permissions
WHERE slug IS NOT NULL;

-- Step 3: Role-permission assignments
INSERT INTO spatie_role_has_permissions (permission_id, role_id)
SELECT sp.id, sr.id
FROM permission_role pr
JOIN permissions p ON p.id = pr.permission_id
JOIN roles r ON r.id = pr.role_id
JOIN spatie_permissions sp ON sp.name = p.slug AND sp.guard_name = 'web'
JOIN spatie_roles sr ON sr.name = r.name AND sr.guard_name = 'web';

-- Step 4: User-role assignments
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT sr.id, 'App\\Models\\User', ru.user_id
FROM role_user ru
JOIN roles r ON r.id = ru.role_id
JOIN spatie_roles sr ON sr.name = r.name AND sr.guard_name = 'web';
```

### Controller Pattern Migration
```php
// OLD: Manual Shinobi check
if (!auth()->user()->hasPermissionTo('cotizaciones.create')) {
    abort(403);
}

// NEW: Filament Policy (automatic via FilamentResource)
// Define in QuotationPolicy::create() → $user->can('cotizaciones.create')

// NEW: Manual Gate check (for custom actions)
Gate::authorize('cotizaciones.create');
```

### Admin "Super User" Equivalent
Shinobi's `all-access` special role → Spatie equivalent:
```php
// In AppServiceProvider::boot()
Gate::before(function (User $user, string $ability) {
    if ($user->hasRole('admin')) {
        return true;
    }
});
```

---

## 13. Passport → Sanctum Migration

**Scope:** Web sessions only (API routes are currently disabled). If future REST API is needed, implement separately.

**Steps:**
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

**Remove Passport:**
- Remove `Laravel\Passport\HasApiTokens` from `User` model
- Add `Laravel\Sanctum\HasApiTokens` to `User` model
- Remove `Passport::routes()` from `AuthServiceProvider`
- Update `config/auth.php`: api guard driver → `sanctum`
- Remove `laravel/passport` from `composer.json`

**Passport tables** (`oauth_*`) can remain in the database during transition but are no longer used.

---

## 14. Auth0 SSO Migration

**Upgrade path:** `auth0/login:^5.3` → `auth0/login:^7.x`

**Key SDK changes in v7:**
- Configuration via `config/auth0.php` (new format)
- `Auth0::getSdk()->authentication()` replaces old client patterns
- JWT validation updated to use Auth0 JWKS caching
- Management API client: `Auth0::getSdk()->management()`

**NovoUserController updates:**
```php
// v5: Auth0API()->users->create([...])
// v7: Auth0::getSdk()->management()->users()->create([...])
```

**Retain Auth0 tenant:** `novonordiskco.auth0.com` — no migration needed on Auth0 side.  
**Retain callbacks:** Same redirect URI: `{APP_URL}/auth0/callback`

---

## 15. Database Migration Strategy

### Principles
1. **No destructive DDL** on existing tables until final cutover
2. **New tables** (Spatie, Sanctum, new feature tables) created alongside existing ones
3. **`nvn_` prefix preserved** on all existing tables
4. **New models** use same table names as existing models

### Migration File Strategy
- Do NOT run `php artisan migrate:fresh` on production
- Apply only additive migrations: `CREATE TABLE`, `ADD COLUMN`, index creation
- Use `Schema::hasTable()` and `Schema::hasColumn()` guards in migration files
- Backup production database before each phase release

### New Tables Required (additions only)

```sql
-- Spatie Laravel Permission
CREATE TABLE spatie_roles (...);
CREATE TABLE spatie_permissions (...);
CREATE TABLE model_has_roles (...);
CREATE TABLE model_has_permissions (...);
CREATE TABLE role_has_permissions (...);

-- Laravel Sanctum
CREATE TABLE personal_access_tokens (...);

-- Laravel Sessions (if switching to database sessions)
CREATE TABLE sessions (...);

-- Cache (if switching to database cache)
CREATE TABLE cache (...);
CREATE TABLE cache_locks (...);
```

### Data Integrity Checks Before Each Phase
```sql
-- Verify foreign key integrity before each migration phase
SELECT COUNT(*) FROM nvn_quotations WHERE id_client NOT IN (SELECT id_client FROM nvn_clients);
SELECT COUNT(*) FROM nvn_negotiations WHERE id_client NOT IN (SELECT id_client FROM nvn_clients);
SELECT COUNT(*) FROM nvn_negotiations_details WHERE id_product NOT IN (SELECT id_product FROM nvn_products);
-- Fix any orphaned records before proceeding
```

---

## 16. Testing & Approval Checkpoints

### Testing Protocol Per Phase

**For each phase, in order:**
1. **Developer tests** — unit + feature tests pass locally
2. **Staging deployment** — deploy to staging environment
3. **Internal QA** — development team tests all acceptance criteria
4. **Stakeholder UAT** — Novo Nordisk team tests in staging (minimum 3 business days)
5. **Stakeholder sign-off** — written approval before production deployment
6. **Production deployment** — deploy with rollback plan ready
7. **Parallel monitoring** — 2-week window where both systems are active
8. **Module cutover** — disable equivalent feature in old system after validation

### Test Coverage Requirements
| Test Type | Minimum Coverage |
|-----------|-----------------|
| Unit tests (business logic) | 85% of methods |
| Feature tests (HTTP routes) | 100% of Filament resources |
| Authorization tests | 100% of permission rules |
| Import/Export tests | All import classes with sample files |
| PDF generation tests | All document templates |

### Critical Test Cases Per Module

**Phase 1 (Auth):**
- Login with valid/invalid credentials
- Auth0 JWT validation with expired/invalid token
- Permission check: user without role cannot access protected route
- Admin bypasses all permission checks

**Phase 3 (Quotations):**
- Discount calculation: discount > Level 1 threshold → requires Level 2 approver
- Expiry scheduler: quotation with `quota_date_end < yesterday` gets cancelled
- All 4 approval levels chain correctly
- PDF renders correctly with all DocFormat fields

**Phase 4 (Negotiations):**
- `discount_acum` accumulates correctly across multiple negotiations for same client-product
- Scale-assisted discount applies correct tier
- `warning=1` set when discount exceeds policy but not blocking
- Approved negotiation updates all `NegotiationDetails` correctly

**Phase 5 (SAP):**
- Sales import rejects rows with missing required fields
- Credit note calculation matches expected amount for known test data
- SAP CSV columns match SAP template exactly

---

## 17. Cutover Protocol

### Pre-Cutover Checklist (per module)
- [ ] All acceptance criteria passed in staging
- [ ] Stakeholder sign-off obtained
- [ ] Database backup taken
- [ ] Rollback procedure documented and tested
- [ ] New system connected to production database
- [ ] Environment variables verified in production
- [ ] S3 bucket permissions verified
- [ ] Auth0 production tenant configured
- [ ] Email provider credentials verified
- [ ] Scheduler (cron) configured for new system

### Module Cutover Steps
1. Enable module in new system (remove `disabled` flag or feature toggle)
2. Notify users via email: "Module X is now available in the new system"
3. Keep old system module accessible for 2 weeks
4. Monitor error logs for both systems daily
5. After 2 weeks with no issues: disable module in old system
6. After all modules cut over: decommission old system

### Rollback Trigger Criteria
- Data corruption detected in `nvn_*` tables
- Error rate > 1% of requests in production
- Critical business calculation (discount, credit note) produces wrong results
- Auth0 login failure affecting > 5% of users

### Final Go-Live (Week 24)
1. All modules active in new system
2. Old system in read-only mode for 30 days
3. Old system decommissioned after 30 days
4. DNS/URL cutover to new system
5. Communicate go-live to all stakeholders

