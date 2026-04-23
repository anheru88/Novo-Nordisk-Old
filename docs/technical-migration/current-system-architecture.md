# Current System Architecture

> **System:** Novo Nordisk CAM Tool  
> **Stack:** Laravel 6.2 · PHP 7.2 · MySQL · AdminLTE 3 · Blade + Vue.js  
> **Documented:** February 2026

---

## Table of Contents

1. [Technology Stack](#1-technology-stack)
2. [Database Schema Overview](#2-database-schema-overview)
3. [Eloquent Models & Relationships](#3-eloquent-models--relationships)
4. [Authentication Flow](#4-authentication-flow)
5. [Authorization System](#5-authorization-system)
6. [Application Modules & Business Workflows](#6-application-modules--business-workflows)
7. [API Endpoints](#7-api-endpoints)
8. [Integration Points](#8-integration-points)
9. [Background Jobs & Console Commands](#9-background-jobs--console-commands)
10. [Event & Notification System](#10-event--notification-system)
11. [File & Storage Architecture](#11-file--storage-architecture)
12. [Known Technical Debt](#12-known-technical-debt)

---

## 1. Technology Stack

### Core Framework
| Component | Version | End-of-Life |
|-----------|---------|-------------|
| PHP | 7.2+ | Dec 2019 ⚠️ |
| Laravel | 6.2 | Sep 2022 ⚠️ |
| MySQL | 5.7/8.0 | — |

### Key Composer Dependencies
| Package | Version | Purpose |
|---------|---------|---------|
| `caffeinated/shinobi` | 4.* | Role-based access control |
| `laravel/passport` | ^9.0 | API authentication (OAuth2) |
| `auth0/login` | ^5.3 | SSO with Auth0 |
| `maatwebsite/excel` | ^3.1 | Excel import/export |
| `barryvdh/laravel-dompdf` | ^0.8.5 | PDF generation |
| `pusher/pusher-php-server` | ~4.0 | Real-time WebSocket events |
| `aws/aws-sdk-php` | ^3.226 | S3, SQS, SES integration |
| `intervention/image` | ^2.7 | Image processing (signatures) |
| `kyslik/column-sortable` | ^6.4 | Sortable table columns |
| `alexusmai/laravel-file-manager` | ^2.4 | File manager UI |
| `lcobucci/jwt` | 3.4 | JWT validation for Auth0 |
| `spatie/flysystem-dropbox` | v1.x-dev | Dropbox storage driver |

### Frontend
- **AdminLTE 3** — admin UI template
- **Blade** — server-side templating
- **Vue.js** — minimal client-side interactivity (city pickers, product lookups)
- **Pusher** — real-time notifications via WebSockets
- **jQuery + DataTables** — table management

---

## 2. Database Schema Overview

All application tables use the prefix `nvn_`. System tables (Shinobi roles/permissions, Passport OAuth, Telescope) use their own naming conventions.

### Table Inventory

#### Master Data Tables
| Table | Model | Description |
|-------|-------|-------------|
| `nvn_brands` | `Brands` | Pharmaceutical brands (Ozempic, Victoza, etc.) |
| `nvn_product_lines` | `Product_Line` | Product line categories |
| `nvn_product_measure_units` | `MeasureUnit` | Units of measure (pack, vial, etc.) |
| `nvn_aditional_terms` | `AditionalUses` | Additional indication uses for products |
| `nvn_dist_channels` | `Channel_Types` | Distribution channels (Hospital, Pharmacy, etc.) |
| `nvn_client_types` | `Client_Types` | Client category types |
| `nvn_payment_terms` | `PaymentTerms` | Payment conditions with discount percentages |
| `nvn_locations` | `Location` | Departments and cities (hierarchical via `loc_codecity`) |
| `nvn_doc_status` | `DocStatus` | Status catalog with name and color |
| `nvn_status` | `Status` | Document workflow status (id, name, color, symbol) |
| `nvn_discount_levels` | `DiscountLevels` | Discount authorization levels (1–4) |
| `nvn_negotiation_concepts` | `NegotiationConcepts` | Discount concept types with SAP mapping |

#### Client Tables
| Table | Model | Description |
|-------|-------|-------------|
| `nvn_clients` | `Client` | Main client registry |
| `nvn_clients_sap_codes` | `Client_Sap_Codes` | Multiple SAP codes per client |
| `nvn_clients_files` | `Client_File` | Client documents/files |

#### Product Tables
| Table | Model | Description |
|-------|-------|-------------|
| `nvn_products` | `Product` | Product catalog |
| `nvn_product_sap_codes` | `Product_Sap_Codes` | Multiple SAP codes per product |
| `nvn_products_h` | `Product_h` | Product price change history |
| `nvn_priceslists` | `PricesList` | Price list versions |
| `nvn_productxprices` | `ProductxPrices` | Product prices per list version |
| `nvn_product_auth_levels` | `Product_AuthLevels` | Discount authorization thresholds by channel |
| `nvn_product_scales` | `Scales` | Volume discount scale definitions |
| `nvn_product_scales_level` | `ScalesLevels` | Scale tiers (min/max volume, discount %) |
| `nvn_productxclientxscales` | `ClientxProductScale` | Scale assignments per client-product pair |

#### Quotation Tables
| Table | Model | Description |
|-------|-------|-------------|
| `nvn_quotations` | `Quotation` | Quotation header |
| `nvn_quotations_details` | `QuotationDetails` | Quotation line items |
| `nvn_quotationxcomments` | `QuotationxComments` | Comments on quotations |
| `nvn_quotationxdocs` | `QuotationxDocs` | Attached documents |
| `nvn_quotation_approvers` | `QuotationApprovers` | Approval records |
| `nvn_quotationxstatus` | `QuotationxStatus` | Status history per quotation |

#### Negotiation Tables
| Table | Model | Description |
|-------|-------|-------------|
| `nvn_negotiations` | `Negotiation` | Negotiation header |
| `nvn_negotiations_details` | `NegotiationDetails` | Negotiation line items |
| `nvn_negotiationxcomments` | `NegotiationComments` | Comments on negotiations |
| `nvn_negotiationxdocs` | `NegotiationDocs` | Attached documents |
| `nvn_negotiationxapprovers` | `NegotiationApprovers` | Approval records |
| `nvn_negotiationxstatus` | `NegotiationxStatus` | Status history per negotiation |
| `nvn_negotiations_errors` | `NegotiationErrors` | Validation errors per detail line |

#### Credit Notes / SAP Tables
| Table | Model | Description |
|-------|-------|-------------|
| `nvn_credit_notes` | `CreditNotes` | Credit note batch header |
| `nvn_credit_notes_clients` | `CreditNotesClients` | Credit note per client (by scale) |
| `nvn_credit_notes_details` | `CreditNotesDetails` | Line items per client note |
| `nvn_credit_notes_clients_bills` | `CreditNotesClientsBills` | Credit note per client (by bill) |
| `nvn_credit_notes_details_b` | `CreditNotesDetailsBills` | Line items per bill note |

#### Sales Tables
| Table | Model | Description |
|-------|-------|-------------|
| `nvn_sales` | `Sales` | Sales batch import header |
| `nvn_sales_details` | `SalesDetails` | Individual sale records |

#### ARP Tables
| Table | Model | Description |
|-------|-------|-------------|
| `nvn_arps` | `Arp` | ARP configuration (year, std, month average) |
| `nvn_services_arp` | `ServiceArp` | ARP service groupings |
| `nvn_arp_service` | `ArpService` | Brand-volume-value mappings per service |
| `nvn_arp_business_case` | `BusinessArp` | Business case (PBC) values per ARP |
| `nvn_arp_simulations` | `ArpSimulations` | Simulation runs |
| `nvn_arp_simulations_details` | `ArpSimulationDetail` | Simulation detail rows (multi-dimensional) |

#### Document & Repository Tables
| Table | Model | Description |
|-------|-------|-------------|
| `nvn_folder_repository` | `FolderRepository` | Hierarchical folder structure |
| `nvn_doc_repository` | `DocRepository` | File entries in folders |
| `nvn_doc_formats` | `DocFormat` | Quotation/negotiation document templates |
| `nvn_format_certificates` | `DocFormatCertificate` | Certificate templates by country |
| `nvn_doc_format_types` | `DocFormatType` | Format type categories |

#### Notification Table
| Table | Model | Description |
|-------|-------|-------------|
| `nvn_notifications` | `Notifications` | In-app notification records |

#### Auth / System Tables (non-nvn prefix)
| Table | Package | Description |
|-------|---------|-------------|
| `users` | Laravel | User accounts |
| `roles` | Shinobi | Role definitions |
| `permissions` | Shinobi | Permission definitions |
| `role_user` | Shinobi | User–role pivot |
| `permission_role` | Shinobi | Role–permission pivot |
| `permission_user` | Shinobi | Direct user–permission assignments |
| `oauth_*` | Passport | OAuth2 tokens and clients |
| `telescope_*` | Telescope | Debug/monitoring entries |

---

## 3. Eloquent Models & Relationships

### Core Entity: Client
**Table:** `nvn_clients` | **PK:** `id_client`

```
Client
├── belongsTo  Channel_Types     (id_client_channel → id_channel)
├── belongsTo  Client_Types      (id_client_type → id_type)
├── belongsTo  Location          (id_city → id_locations)
├── belongsTo  Location          (id_department → id_locations)
├── belongsTo  PaymentTerms      (id_payterm → id_payterms)
├── belongsTo  User              (id_diab_contact → id)
├── hasMany    Quotation         (id_client)
├── hasMany    Negotiation       (id_client)
├── hasMany    Client_File       (id_client)
└── hasMany    Client_Sap_Codes  (id_client)
```

**Key fillable:** `client_name`, `client_nit`, `client_sap_code`, `client_sap_name`, `prod_invima_reg`, `client_credit`, `active`, `id_client_channel`, `id_department`, `id_city`, `id_payterm`, `client_contact`, `client_email`, `client_phone`, `created_by`

**Static helpers:**
- `Client::getClientID($nit)` — looks up `id_client` by NIT number
- `Client::scopeClientChannel($query, $id)` — returns the `id_channel` for a given client

---

### Core Entity: Product
**Table:** `nvn_products` | **PK:** `id_product`

```
Product
├── belongsTo  Brands            (id_brand)
├── belongsTo  Product_Line      (id_prod_line)
├── belongsTo  MeasureUnit       (id_measure_unit)
├── belongsTo  AditionalUses     (id_use)
├── hasMany    ProductxPrices    (id_product)
├── hasMany    Product_Sap_Codes (id_product)
└── hasMany    QuotationDetails  (id_product)
```

**Key fillable:** `prod_name`, `prod_sap_code`, `prod_commercial_name`, `prod_generic_name`, `prod_invima_reg`, `prod_cum`, `id_prod_line`, `id_measure_unit`, `id_brand`, `is_prod_regulated`, `prod_obesidad`, `prod_insumo`, `v_institutional_price`, `v_commercial_price`, `prod_valid_date_ini`, `prod_valid_date_end`, `material`, `prod_increment_max`, `renovacion`, `aditional_use`, `extension_time`, `arp_divide`

**Static helpers:**
- `Product::getProductsWithLevels($id_pricelist)` — joins productxprices + product_auth_levels for authorization-aware pricing

---

### Core Entity: Quotation
**Table:** `nvn_quotations` | **PK:** `id_quotation`

```
Quotation
├── belongsTo  Client                (id_client)
├── belongsTo  Channel_Types         (id_channel)
├── belongsTo  Location              (id_city)
├── belongsTo  Status                (status_id)
├── belongsTo  User                  (id_authorizer_user) — assigned approver
├── belongsTo  User                  (created_by) — creator
├── hasMany    QuotationDetails      (id_quotation)
├── hasMany    QuotationxComments    (id_quotation)
├── hasMany    QuotationxDocs        (id_quotation)
└── hasMany    QuotationApprovers    (quotation_id)
```

**Key fillable:** `id_client`, `id_city`, `id_channel`, `is_authorized`, `id_authorizer_user`, `quota_value`, `quota_date_ini`, `quota_date_end`, `created_by`, `status_id`

**Status values for `is_authorized`:**  
`0`=draft, `1`=pending, `2`=pre-approved, `3–6`=approval levels, `7`=approved, `8`=rejected, `9`=cancelled/expired

**Static business methods:**
- `Quotation::updateQuotationsbyDate()` — sets `is_authorized=9` for all quotations where `quota_date_end < yesterday` and `is_authorized < 7`
- `Quotation::updateQuotationsbyProducts()` — cancels quotations where all product lines are expired
- `Quotation::updateQuotationsbyApprovals($id, $status)` — bulk-updates detail lines when a quotation is approved/rejected

---

### Core Entity: Negotiation
**Table:** `nvn_negotiations` | **PK:** `id_negotiation`

```
Negotiation
├── belongsTo  Client                  (id_client)
├── belongsTo  Channel_Types           (id_channel)
├── belongsTo  Location                (id_city)
├── belongsTo  Status                  (status_id)
├── belongsTo  User                    (id_authorizer_user)
├── belongsTo  User                    (created_by)
├── hasMany    NegotiationDetails      (id_negotiation)
├── hasMany    NegotiationComments     (id_negotiation)
├── hasMany    NegotiationDocs         (id_negotiation)
└── hasMany    NegotiationApprovers    (negotiation_id)
```

**Additional fields vs Quotation:** `negotiation_value` (total), `is_authorized`, `pdf_content` (cached PDF HTML)

**Static business methods:**
- `Negotiation::updateNegotiationsbyDate()` — same expiry logic as quotations
- `Negotiation::updateNegotiationsbyAprovations($id, $status, $idClient, $dateIni, $dateEnd, $product)` — complex update of detail lines post-approval
- `Negotiation::updateSingleNegotiationbyProducts()` — cancels negotiations where all product lines are overdue
- `Negotiation::approversNeg($id_negotiation)` — returns approver records for a given negotiation

---

### Entity: NegotiationDetails
**Table:** `nvn_negotiations_details` | **PK:** `id_negotiation_det`

```
NegotiationDetails
├── belongsTo  Negotiation             (id_negotiation)
├── belongsTo  Product                 (id_product)
├── belongsTo  NegotiationConcepts     (id_concept)
├── belongsTo  PaymentTerms            (id_payterm)
├── belongsTo  Client                  (id_client)
├── belongsTo  Quotation               (id_quotation)
└── hasMany    NegotiationErrors       (id_negotiation_det)
```

**Key fillable:** `id_negotiation`, `id_client`, `id_product`, `id_concept`, `discount`, `discount_type`, `discount_acum`, `id_quotation`, `suj_volumen`, `warning`

---

### Entity: Pricing (ProductxPrices + Product_AuthLevels)

```
PricesList (nvn_priceslists)
└── hasMany  ProductxPrices  (id_pricelists)
                └── belongsTo  Product  (id_product)

Product_AuthLevels (nvn_product_auth_levels)
├── belongsTo  Product         (id_product)
├── belongsTo  Channel_Types   (id_dist_channel)
└── belongsTo  DiscountLevels  (id_level_discount)
```

Authorization levels define the **maximum discount** a user at each level can approve per product per channel.

---

### Entity: Scales (Volume Discounts)

```
Scales (nvn_product_scales)
├── belongsTo  Product      (id_product)
├── belongsTo  Channel_Types (channel_id)
└── hasMany    ScalesLevels  (id_scale)

ScalesLevels (nvn_product_scales_level)
├── belongsTo  Scales       (id_scale)
└── belongsTo  MeasureUnit  (id_measure_unit)
```

Scales define **volume-based automatic discounts**: if a client purchases between `scale_min` and `scale_max` units, they receive `scale_discount`% off.

---

### Entity: ARP (Annual Revenue Planning)

```
Arp (nvn_arps)
├── hasMany  ServiceArp       (arps_id)
└── hasOne   BusinessArp      (arp_id)

ServiceArp (nvn_services_arp)
└── hasMany  ArpService       (services_arp_id)
             ├── hasMany  Brands    (brand_id)
             └── belongsTo ServiceArp (services_arp_id)

ArpSimulations (nvn_arp_simulations)
└── hasMany  ArpSimulationDetail  (arp_simulation_id)
```

---

### Entity: Credit Notes (SAP Integration)

```
CreditNotes (nvn_credit_notes)
├── hasMany  CreditNotesClients      (id_credit_notes)
│            └── hasMany  CreditNotesDetails   (id_credit_notes_clients)
└── hasMany  CreditNotesClientsBills (id_credit_notes)
             └── hasMany  CreditNotesDetailsBills (id_credit_notes_clients_b)
```

Two variants: **by scale** (`CreditNotesClients`/`Details`) and **by bill** (`CreditNotesClientsBills`/`DetailsBills`).

---

### Entity: User
**Table:** `users` | **PK:** `id`

**Traits used:** `HasApiTokens` (Passport), `Notifiable`, `HasRolesAndPermissions` (Shinobi)

**Key fields:** `name`, `email`, `password`, `position`, `uuid_firm`, `firm` (signature image path)

**Relationships:**
- `belongsTo DiscountLevels (disc_level)` — determines max discount the user can authorize
- `belongsTo QuotationApprovers` — records where this user is an approver

**Custom setters:**
- `setEmailAttribute($value)` — forces lowercase
- `setFirmAttribute($value)` — strips `public/` prefix from path

---

## 4. Authentication Flow

The system uses **three authentication mechanisms** simultaneously:

### 4.1 Web Session Authentication (Primary)
- Standard Laravel session auth using the `web` guard
- Login via `Auth\LoginController` with email/password
- Auth0 SDK configured but largely commented-out in route definitions
- Session stored server-side; CSRF token required for all POST routes
- **Guard config:** `auth.guards.web` → driver `session`, provider `users`

### 4.2 Laravel Passport (API Guard)
- **Guard config:** `auth.guards.api` → driver `passport`, provider `users`
- Used for mobile/external API authentication (`/api/v1/...`)
- **NOTE:** All API routes are currently commented-out in `routes/api.php` — the API is non-functional in the current state
- Passport tokens stored in `oauth_access_tokens`, `oauth_clients` tables

### 4.3 Auth0 SSO Integration
- Auth0 tenant: `novonordiskco.auth0.com`
- JWT tokens validated with RS256 algorithm
- `middleware('jwt')` used on Auth0 management endpoints
- Routes under `/auth0/` prefix handle Auth0 user CRUD via Management API
- The `NovoUserController` bridges Laravel user creation with Auth0 identity management
- Auth0 callback route is **commented-out** — SSO login is not fully active in current production
- **Config:** `config/laravel-auth0.php` — reads `AUTH0_DOMAIN`, `AUTH0_CLIENT_ID`, `AUTH0_CLIENT_SECRET` from `.env`

### 4.4 Authentication Flow Summary
```
User visits site
    → Standard Laravel login form (email + password)
    → Auth\LoginController::login()
    → Session established (web guard)
    → User redirected to HomeController::index()
    → All subsequent requests use session cookie

For NovoUsers (internal SSO):
    → External NovoUsers system POSTs to /auth0/login
    → NovoUserController validates JWT from Auth0
    → Finds or creates local User record
    → Returns session token for NovoUsers SPA
```

---

## 5. Authorization System

### 5.1 Caffeinated Shinobi (Current)

**Tables:**
- `roles` — role definitions (`name`, `slug`, `description`, `special` flag)
- `permissions` — permission definitions (`name`, `slug`, `description`, `model`)
- `role_user` — pivot: user → role assignments
- `permission_role` — pivot: role → permission assignments
- `permission_user` — pivot: direct user → permission assignments (overrides)

**Special role flags:**
- `all-access` — bypasses all permission checks (Admin role)
- `no-access` — denies all permissions regardless of assignments

**Permission format:** `resource.action` (e.g., `users.index`, `cotizaciones.create`)

**Middleware used:** `auth` (guards routes), then controllers call `auth()->user()->hasPermissionTo('permission.slug')` manually.

**Caching:** Disabled in `config/shinobi.php` (`cache.enabled = false`)

### 5.2 Authorization Levels (Discount Approval)

Separate from Shinobi permissions, the system has **4 authorization levels** for discount approval, stored in `nvn_discount_levels` and assigned to users via `users.disc_level`:

| Level | Max Discount | Who holds it |
|-------|-------------|--------------|
| Level 1 | Up to 5% | Junior authorizer |
| Level 2 | Up to 10% | Senior authorizer |
| Level 3 | Up to 15% | Manager |
| Level 4 | > 15% (up to max) | Director |

The `nvn_product_auth_levels` table maps **which discount level is required** for each product × channel combination, giving fine-grained control over what discounts need what level of approval.

### 5.3 Defined Roles (Current System)

| Role Slug | Description |
|-----------|-------------|
| `admin` | Full system access (Shinobi `all-access`) |
| `admin-ventas` | Complete sales management access |
| `admin-precios` | Price and price list management |
| `autorizador` | Approve/reject quotations and negotiations |
| `cam` | Create quotations and negotiations (CAM/KAM users) |
| `analista` | View reports and read-only operations |
| `consulta` | Read-only access to all modules |

### 5.4 Permissions by Module

| Module | Permissions |
|--------|-------------|
| Users | `users.index`, `users.create`, `users.edit`, `users.destroy` |
| Roles | `roles.index`, `roles.create`, `roles.edit`, `roles.destroy` |
| Products | `products.index`, `products.create`, `products.edit`, `products.destroy` |
| Prices | `prices.index`, `prices.create`, `prices.edit`, `prices.destroy` |
| Clients | `clients.index`, `clients.create`, `clients.edit`, `clients.destroy` |
| Quotations | `cotizaciones.index`, `cotizaciones.create`, `cotizaciones.edit`, `cotizaciones.destroy` |
| Negotiations | `negociaciones.index`, `negociaciones.create`, `negociaciones.edit`, `negociaciones.destroy` |
| Authorizations | `autorizaciones.index`, `autorizaciones.aprobar`, `autorizaciones.rechazar` |
| Reports | `reportes.index`, `reportes.export` |
| Files/Docs | `files.index`, `files.create`, `files.destroy`, `documentos.index`, `documentos.create` |
| ARP | `arp.index`, `arp.create`, `arp.edit`, `arp.destroy`, `simulatorArp.index`, `simulatorArp.import` |
| SAP/Notes | `sapnotes.index`, `sapnotes.import`, `notas.index`, `notas.masive` |

---

## 6. Application Modules & Business Workflows

### Module 1: Parametrization (Master Data)
Managed by multiple controllers; provides reference data for all other modules.

**Sub-modules:**
- **Brands** (`BrandsController`) — pharmaceutical brand catalog
- **Product Lines** (`ProductLinesController`) — category groupings
- **Measurement Units** (`ProductUnitsController`) — units of measure
- **Additional Uses** (`AditionalUsesController`) — off-label/extended product uses
- **Distribution Channels** — hospital, pharmacy, institutional, etc.
- **Client Types** (`ClientTypesController`) — IPS, EPS, droguería, etc.
- **Payment Terms** (`PayMethodsController`) — conditions with discount percentages and SAP codes
- **Negotiation Concepts** (`NegotiationConceptsController`) — discount concept types with SAP concept codes
- **Locations** (`LocationsController`) — departments + cities (hierarchical by `loc_codedep`/`loc_codecity`)

**Workflow:** These are pure CRUD operations. Changes here cascade to quotations, negotiations, and products.

---

### Module 2: Product Catalog
**Controller:** `ProductsController`, `ProductsPricesController`, `AuthLevelController`

**Workflow:**
1. Products created with brand, line, unit, SAP codes, INVIMA registration
2. Products assigned to Price Lists (`PricesList` → `ProductxPrices`) with institutional and commercial prices
3. Authorization levels defined per product per channel (`Product_AuthLevels`)
4. Volume discount scales defined (`Scales` → `ScalesLevels`)
5. Scales assigned to specific client-product pairs (`ClientxProductScale`)

**Bulk import:** `ProductsImport` class handles Excel import with line/unit mapping.  
**Price import:** `PricesImport` class handles price list Excel import.

---

### Module 3: Client Management
**Controller:** `ClientsController`

**Workflow:**
1. Clients created with NIT, SAP codes, channel type, payment terms, location
2. Client files uploaded (contracts, agreements) and stored in `public/uploads/clients/`
3. Certificate PDFs generated via `createPDFCertificate()` using `DocFormatCertificate` templates
4. Bulk client import via `ClientsImport` Excel class

---

### Module 4: Quotation Workflow
**Controller:** `QuotationsController`

```
CREATE (CAM user)
  ├── Select client → determines channel type
  ├── Set validity dates (ini, end)
  ├── Add products
  │    ├── getProductsClient() → fetch eligible products for channel
  │    ├── calcProductQuota() → calculate price with discounts
  │    └── getPreviousProduct() → check for existing quotations
  ├── Set payment terms per line
  └── Assign authorizer user

STATUS FLOW
  Draft (0) → Sent for approval (1)
    → Pre-approved (2) [by PreAutorizationsController]
      → Level 1 approval (3) → Level 2 (4) → Level 3 (5) → Level 4 (6)
        → Fully Approved (7) → PDF generated + emailed to client
    → Rejected (8) [at any level]
  Auto-expired (9) [via console command, runs daily]

NOTIFICATIONS
  → Event: OrderNotificationsEvent broadcast via Pusher
  → Email: QuotationNotification via Mailgun/SES
  → In-app: Notifications model record created
```

**PDF generation:** `createPDF()` uses DOMPDF + `DocFormat` templates.  
**Email send:** `quotationSendEmail()` via Laravel Mail + `Notifiable` notification.

---

### Module 5: Negotiation Workflow
**Controller:** `NegotiationsController` (1,994 lines — most complex controller)

```
CREATE (CAM user)
  ├── Select client + reference quotation(s)
  ├── getProductsClientQuota() → load products from approved quotation
  ├── calcDiscount() → validate proposed discount against authorization levels
  ├── negociacionAsistida() → apply scale discounts automatically
  ├── negociacionAsistidaxConcepto() → apply concept-based discounts
  └── Store with initial status + notify approvers

DISCOUNT VALIDATION
  ├── Check Product_AuthLevels for max allowed discount per level
  ├── Compare requested discount vs user's disc_level
  ├── If within level: auto-approve (no approver needed)
  └── If exceeds level: route to appropriate level authorizer

STATUS FLOW
  Same as quotation: 0 → 1 → 2 → 3/4/5/6 → 7 (approved) | 8 (rejected) | 9 (expired)

POST-APPROVAL
  ├── updateNegotiationsbyAprovations() updates all detail lines
  ├── Negotiation exported as PDF (PDF content cached in pdf_content column)
  └── Email sent to client
```

---

### Module 6: Authorization Workflow
**Controllers:** `AutorizationsController`, `PreAutorizationsController`

```
Authorizer receives notification
  → Navigates to autorizaciones/ or preautorizaciones/
  → Reviews quotation or negotiation details
  → Calls autorizeQuotation() or autorizeNegotiation()
  → Sets answer: approved/rejected + comment
  → NegotiationApprovers / QuotationApprovers record updated
  → Notification sent to creator
  → If last required approver: document moves to final status
```

**External authorization (no login):**
- `POST /authorizequota` and `POST /authorizenegoti` — allow authorizers to approve/reject via email link without full login (used in mobile/email workflow)

---

### Module 7: Sales & Liquidation
**Controller:** `ReportsController` (notas section), `SapController`

```
SALES IMPORT
  SalesImport (Excel, chunked 1000 rows)
  ├── Validates 17 mandatory fields
  ├── Maps client NIT → id_client
  ├── Maps product SAP code → id_product
  └── Stores in nvn_sales + nvn_sales_details

SCALE LIQUIDATION (Credit Notes by Scale)
  Sales::ncEscalas($idSale)
  ├── Joins sales_details with product_scales + scale_levels
  ├── Groups by client + product + scale
  ├── Calculates volume total vs scale thresholds
  └── Generates credit note amounts

SAP CSV/EXCEL GENERATION
  SapController::generateCsv() / generateExcel()
  ├── Reads CreditNotes + CreditNotesClients + CreditNotesDetails
  └── Exports in SAP-compatible format for upload
```

---

### Module 8: ARP Simulator
**Controllers:** `ArpController`, `ArpSimulationsController`, `ServiceArpController`, `BusinessArpController`

```
CONFIGURATION
  ├── Arp: annual parameters (year, std deviation, monthly average)
  ├── ServiceArp: service groupings (e.g., "Diabetes", "Obesity")
  ├── ArpService: brand × volume × value_cop per service
  └── BusinessArp: PBC (business case) values per ARP

SIMULATION
  ├── Import Excel file via ArpImport
  ├── Stores rows in nvn_arp_simulations_details
  │    (brand, product, client, cal_year_month, vol_type, forecast_vol,
  │     volume, asp_cop, amount_mcop, amount_dkk, currency, etc.)
  └── Export multi-sheet Excel via ArpSimulationsExport
       (ArpSimulationsSheets, ArpSpecialSheet, ArpVersionSheets)
```

---

### Module 9: Document Management
**Controllers:** `DocsController`, `FilesController`, `DocFormatsController`

**Document Repository:**
- Folders managed in `nvn_folder_repository` (hierarchical via `id_parent`)
- Files stored in `nvn_doc_repository` linked to folders
- Shared via signed URLs (`Route::get('sharedfiles')→middleware('signed')`)
- Temporary links generated by `FilesController::sharedDocsSendEmail()`

**Document Formats:**
- `DocFormat` — templates for quotation/negotiation PDFs
- `DocFormatCertificate` — country-specific certificate templates
- Rich text body/conditions/terms stored in DB, rendered with DOMPDF

**Client Files:**
- Stored at `public/uploads/clients/{id_client}/`
- Referenced in `nvn_clients_files` table

---

## 7. API Endpoints

**Current state: All API routes are commented-out in `routes/api.php`.**

The planned API structure (commented code) was:

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/v1/login` | API authentication |
| POST | `/api/v1/register` | New user registration |
| GET | `/api/v1/logout` | Token invalidation |
| GET | `/api/v1/cot` | List quotations |
| GET | `/api/v1/showquotation/{id}` | Get quotation details |
| POST | `/api/v1/producfordetail` | Get product details |
| GET | `/api/v1/neg` | List negotiations |
| POST | `/api/v1/authorizenegoty` | Authorize negotiation |
| GET | `/api/v1/shownegotiation/{id}` | Get negotiation details |

**Auth0 Management endpoints (active):**

| Method | Endpoint | Controller | Purpose |
|--------|----------|-----------|---------|
| GET | `/auth0/get-app-data` | `NovoUserController@getAppData` | Get app metadata |
| POST | `/auth0/create-user` | `NovoUserController@createUser` | Create Auth0 user |
| POST | `/auth0/delete-user` | `NovoUserController@deleteUser` | Delete Auth0 user |
| GET | `/auth0/login` | `NovoUserController@getLogin` | Get login URL |
| POST | `/auth0/login` | `NovoUserController@login` | Process Auth0 login |

**Web endpoints used as AJAX (non-REST):**

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/getCities` | Load cities by department (Vue.js) |
| POST | `/getProduct` | Load product details (pricing popup) |
| POST | `/getClient` | Load client details |
| POST | `/calcProductQuota` | Calculate quotation price |
| POST | `/calcDiscount` | Validate negotiation discount |
| POST | `/getProductsClient` | Products available for a client |
| POST | `/negociacionAsistida` | Apply scale discounts |
| POST | `/getScales` | Load scale definitions |

---

## 8. Integration Points

### 8.1 SAP Integration
**Type:** File-based (CSV/Excel export), not real-time API  
**Direction:** Outbound only (system → SAP)

**Flow:**
1. Credit notes calculated in-system
2. `SapController::generateCsv()` / `generateExcel()` creates SAP-format files
3. Files downloaded by users and manually uploaded to SAP S/4HANA

**Relevant classes:** `SapExport`, `NotesExport`, `SapImport`, `GenSheetImport`

**SAP field mappings:**
- Client: `client_sap_code` (nvn_clients_sap_codes.sap_code)
- Product: `prod_sap_code` (nvn_product_sap_codes.sap_code)
- Concept: `sap_concept` (nvn_negotiation_concepts.sap_concept)
- Payment: `payment_code` (nvn_payment_terms.payment_code)

### 8.2 Auth0 (NovoUsers SSO)
**Type:** OAuth2 / JWT API  
**Tenant:** `novonordiskco.auth0.com`  
**Direction:** Bidirectional

**Flow:**
1. NovoUsers SPA calls `/auth0/login` with Auth0 JWT
2. Laravel validates JWT (RS256) via `lcobucci/jwt`
3. User found/created locally; session established
4. User management (create/delete) propagated to Auth0 via Management API

**Config:** `AUTH0_DOMAIN`, `AUTH0_CLIENT_ID`, `AUTH0_CLIENT_SECRET`, `AUTH0_MANAGEMENT_API_TOKEN` (env vars)

### 8.3 AWS Services
| Service | Usage |
|---------|-------|
| S3 | File storage (configured via `filesystems.php`, `spatie/flysystem-dropbox` also present) |
| SES | Email sending (alternative to Mailgun) |
| SQS | Queue driver (configured in `queue.php`) |

### 8.4 Email (Mailgun / SES)
- **Mailgun:** Primary email provider (configured in `services.php`)
- **SES:** Fallback (configured)
- Used for: quotation/negotiation notifications, document sharing links, approval requests
- Notifications: `QuotationNotification`, `Notifiable`, `GenericNotification`

### 8.5 Pusher (Real-time WebSockets)
- **Purpose:** In-app real-time notifications
- **Event:** `OrderNotificationsEvent` — broadcasts to users' private channels
- **Listener:** `OrderNotificationsListener` — sends push via Pusher SDK
- **Frontend:** Pusher JS client subscribes to `private-channel-{userId}`

### 8.6 SFTP (Implied)
- `config/filesystems.php` likely has SFTP disk configured
- Used for SAP file transfers in some environments (not confirmed in code; referenced in documentation)

---

## 9. Background Jobs & Console Commands

### Scheduled Console Commands

| Command Class | Artisan Slug | Purpose | Schedule |
|--------------|-------------|---------|----------|
| `UpdateQuotationsStatus` | (custom) | Cancels expired quotations | Daily |
| `UpdateNegotiationStatus` | (custom) | Cancels expired negotiations | Daily |

**UpdateQuotationsStatus logic:**
```
Calls Quotation::updateQuotationsbyDate()
  → UPDATE nvn_quotations SET is_authorized=9
     WHERE quota_date_end < yesterday
     AND (is_authorized < 7 OR status_id <= 6)

Calls Quotation::updateQuotationsbyProducts()
  → For each quotation: if all QuotationDetails.prod_valid_date_end < now → cancel
```

**UpdateNegotiationStatus logic:**
```
Calls Negotiation::updateNegotiationsbyDate()
  → Same pattern for nvn_negotiations

Calls Negotiation::updateSingleNegotiationbyProducts()
  → Same pattern for nvn_negotiations_details
```

**NOTE:** These commands must be registered in `app/Console/Kernel.php` and scheduled via cron or Laravel Scheduler. Confirm the schedule in `Kernel.php` before migration.

### Other Commands
| Command Class | Purpose |
|--------------|---------|
| `FixPngProfiles` | One-time fix for malformed PNG iCCP profiles in uploaded images |
| `MigrateFirmImages` | One-time migration of firm signature images to new folder structure |

---

## 10. Event & Notification System

### Events
| Event | Trigger | Payload |
|-------|---------|---------|
| `OrderNotificationsEvent` | `Notifications::sendNotification()` | `{description, url, userId[]}` |

### Listeners
| Listener | Handles | Action |
|----------|---------|--------|
| `OrderNotificationsListener` | `OrderNotificationsEvent` | Broadcasts via Pusher to `private-channel-{userId}` |

### Mail Notifications
| Class | Trigger | Channel |
|-------|---------|---------|
| `QuotationNotification` | Quotation approval/rejection | Database + Mail |
| `BrandNotification` | Brand updates | Database |
| `GenericNotification` | General-purpose mail | Mail |
| `Notifiable` | CAM Tool approval requests | Mail |

### In-App Notifications
Stored in `nvn_notifications` table:
- `destiny_id` — target user ID
- `sender_id` — acting user ID
- `type` — notification category
- `data` — message text
- `url` — link to relevant resource
- `readed` / `read_at` — read tracking

`Notifications::sendNotification($msg, $users[], $url, $type)` — static method, creates/updates records and fires `OrderNotificationsEvent`.

---

## 11. File & Storage Architecture

### Upload Directories (under `public/`)
| Path | Contents |
|------|---------|
| `public/uploads/quotations/{id_quotation}/` | Quotation supporting documents |
| `public/uploads/negotiations/{id_negotiation}/` | Negotiation supporting documents |
| `public/uploads/clients/{id_client}/` | Client files (contracts, etc.) |
| `public/firms/` | User signature images (PNG, after migration command) |
| `public/uploads/firms/` | Pre-migration location for signatures |
| `public/templates/` | Document template assets |
| `public/downloads/` | Generated export files |

**File storage method:** Direct to `public/` path using `File::makeDirectory()` and `$file->move()`. No disk abstraction for most uploads.

**Signed URLs:** Used for temporary file sharing (`Route::get('sharedfiles')→middleware('signed')`). Links generated with `URL::temporarySignedRoute()`.

**AWS S3:** Configured in `filesystems.php` but not uniformly used — most file operations write directly to `public/` disk.

---

## 12. Known Technical Debt

| Issue | Impact | Notes |
|-------|--------|-------|
| Laravel 6.2 EOL | **Critical** — no security patches | Must migrate |
| PHP 7.2 EOL | **Critical** — known CVEs | Must upgrade to 8.4 |
| API routes commented out | High — mobile integration broken | Decide: REST API or Filament-only |
| Auth0 SSO partially inactive | High — SSO routes commented-out | Must fully activate or remove |
| `public/` filesystem for uploads | High — not cloud-native | Migrate to S3 storage |
| Inconsistent PK naming | Medium — `id_*` vs `id` | Standardize in new system |
| `Payment.php` duplicates `PaymentTerms.php` | Low — same table, different model | Remove `Payment.php` |
| `NegotiationDocs` wrong FK in relationship | Low — `belongsTo` uses wrong key | Fix in migration |
| No unit tests for business logic | High — risky changes | Write tests during migration |
| Shinobi cache disabled | Low — performance | Enable in new system (Spatie) |
| `ConceptosController` is empty stub | Low | Remove or implement |
| File paths hardcoded to `public/` | Medium | Abstract to Storage facade |
| `SOLUCION_PNG_ICCP.md` in root | Low — leftover debug doc | Remove before go-live |

