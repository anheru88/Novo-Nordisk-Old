# Detailed Migration Timeline

> **Project Start:** March 3, 2026  
> **Go-Live Date:** August 28, 2026  
> **Total Duration:** 26 weeks (including 2-week buffer)  
> **Methodology:** Agile sprints (1-week iterations), module-based delivery

---

## Table of Contents

1. [Timeline Summary](#1-timeline-summary)
2. [Phase 1 — Foundation (Weeks 1–3)](#2-phase-1--foundation-weeks-1-3)
3. [Phase 2 — Master Data (Weeks 4–7)](#3-phase-2--master-data-weeks-4-7)
4. [Phase 3 — Quotations (Weeks 8–11)](#4-phase-3--quotations-weeks-8-11)
5. [Phase 4 — Negotiations & Authorizations (Weeks 12–15)](#5-phase-4--negotiations--authorizations-weeks-12-15)
6. [Phase 5 — Liquidation, Credit Notes & SAP (Weeks 16–18)](#6-phase-5--liquidation-credit-notes--sap-weeks-16-18)
7. [Phase 6 — ARP Simulator & Sales (Weeks 19–21)](#7-phase-6--arp-simulator--sales-weeks-19-21)
8. [Phase 7 — Document Management (Week 22)](#8-phase-7--document-management-week-22)
9. [Phase 8 — Reporting & Dashboard (Weeks 23–24)](#9-phase-8--reporting--dashboard-weeks-23-24)
10. [Buffer & Integration (Weeks 25–26)](#10-buffer--integration-weeks-25-26)
11. [Approval Gates](#11-approval-gates)
12. [Rollback Plan](#12-rollback-plan)

---

## 1. Timeline Summary

| Phase | Start | End | Duration | Key Milestone |
|-------|-------|-----|----------|--------------|
| **Phase 1:** Foundation | Mar 3 | Mar 21 | 3 weeks | ✅ New system login + RBAC |
| **Phase 2:** Master Data | Mar 24 | Apr 17 | 4 weeks | ✅ Products, Prices, Clients live |
| **Phase 3:** Quotations | Apr 20 | May 15 | 4 weeks | ✅ Full quotation workflow |
| **Phase 4:** Negotiations | May 18 | Jun 12 | 4 weeks | ✅ Negotiation + discount engine |
| **Phase 5:** SAP / Credit Notes | Jun 15 | Jul 3 | 3 weeks | ✅ Liquidation + SAP exports |
| **Phase 6:** ARP + Sales | Jul 6 | Jul 24 | 3 weeks | ✅ ARP simulator |
| **Phase 7:** Documents | Jul 27 | Aug 1 | 1 week | ✅ Document repository |
| **Phase 8:** Reports + Dashboard | Aug 3 | Aug 14 | 2 weeks | ✅ All reporting live |
| **Buffer / UAT** | Aug 17 | Aug 28 | 2 weeks | 🚀 **Go-Live** |

**Total:** 26 weeks | **Parallel monitoring:** 2 weeks post go-live

---

## 2. Phase 1 — Foundation (Weeks 1–3)

**Dates:** March 3 – March 21, 2026

### Week 1 (Mar 3–7): Infrastructure & Project Setup
| Day | Task | Owner |
|-----|------|-------|
| Mon | Create Laravel 12 project, configure Git repo, branch strategy | Tech Lead |
| Mon | Set up 3 environments: local, staging (AWS), production (AWS) | DevOps |
| Tue | Install FilamentPHP 3.x, configure admin panel provider | Dev 1 |
| Tue | Install Spatie Laravel Permission, run migrations | Dev 1 |
| Wed | Install Laravel Sanctum, configure guards | Dev 2 |
| Wed | Install Auth0 Laravel SDK 7.x, configure `config/auth0.php` | Dev 2 |
| Thu | Connect staging to existing production database (read-only replica) | DevOps |
| Thu | Configure AWS S3 bucket + IAM roles for file storage | DevOps |
| Fri | Configure Redis (cache + session), SQS (queue) | DevOps |
| Fri | Set up CI/CD pipeline (GitHub Actions → staging deploy) | DevOps |

**Deliverable:** Deployed staging environment with FilamentPHP base panel, Auth0 callback working.

---

### Week 2 (Mar 10–14): Authentication & User Management
| Day | Task | Owner |
|-----|------|-------|
| Mon | Implement Filament login page with email/password | Dev 1 |
| Mon | Implement Auth0 SSO login button in Filament | Dev 2 |
| Tue | Port `NovoUserController` Auth0 management endpoints (create/delete user) | Dev 2 |
| Tue | Run Shinobi → Spatie permission migration script on staging | Dev 1 |
| Wed | Verify all 7 roles + all permissions migrated correctly | Dev 1 |
| Wed | Build `UserResource`: list, create, edit, role assignment, signature upload | Dev 1 |
| Thu | Build `RoleResource`: list, create, edit, permission matrix | Dev 2 |
| Thu | Implement Spatie policy pattern for all resources (template) | Dev 1 |
| Fri | Implement `Admin super-user Gate::before` bypass | Dev 2 |
| Fri | Write Phase 1 feature tests (auth + permissions) | Dev 1 + Dev 2 |

**Deliverable:** Users and roles manageable in Filament; permission system enforced.

---

### Week 3 (Mar 17–21): Phase 1 Testing & Approval Gate
| Day | Task | Owner |
|-----|------|-------|
| Mon–Tue | Internal QA: test all auth flows, permission scenarios | QA |
| Mon–Tue | Fix bugs found during internal QA | Dev team |
| Wed | Deploy to staging for stakeholder review | DevOps |
| Wed–Thu | **Stakeholder UAT — Phase 1** (Novo Nordisk IT team) | Client |
| Fri | Collect and address UAT feedback | Dev team |
| Fri | **Stakeholder sign-off on Phase 1** ← APPROVAL GATE | PM |

**Acceptance Criteria Check:**
- [ ] Login with email/password works
- [ ] Auth0 SSO works end-to-end
- [ ] All 7 roles have correct permissions
- [ ] User creation with role + discount level works
- [ ] Signature image uploads to S3

---

## 3. Phase 2 — Master Data (Weeks 4–7)

**Dates:** March 24 – April 17, 2026

### Week 4 (Mar 24–28): Parametrization Resources
| Day | Task | Owner |
|-----|------|-------|
| Mon | Build `BrandResource`, `ProductLineResource`, `MeasureUnitResource`, `AditionalUseResource` | Dev 1 |
| Tue | Build `ChannelTypeResource`, `ClientTypeResource`, `PaymentTermResource` | Dev 2 |
| Wed | Build `NegotiationConceptResource`, `DiscountLevelResource`, `StatusResource` | Dev 1 |
| Thu | Build `LocationResource` with department→city hierarchy | Dev 2 |
| Fri | Unit tests for all parametrization resources | Dev 1 + Dev 2 |

**Deliverable:** All 12 master data tables editable in Filament.

---

### Week 5 (Mar 31–Apr 4): Product Catalog
| Day | Task | Owner |
|-----|------|-------|
| Mon | Build `ProductResource` (full form with all fields) | Dev 1 |
| Mon | Implement `SapCodesRelationManager` inline in ProductResource | Dev 1 |
| Tue | Build `PricesListResource` + `ProductxPricesRelationManager` | Dev 2 |
| Tue | Build `AuthLevelResource` (product × channel × discount level) | Dev 2 |
| Wed | Port `ProductsImport` (Excel bulk import) — test with sample file | Dev 1 |
| Wed | Port `PricesImport` (Excel price list import) | Dev 2 |
| Thu | Port `ProductsExport` (Excel export with valid dates) | Dev 1 |
| Fri | Unit tests for product imports; verify SAP code uniqueness validation | Dev 1 + Dev 2 |

**Deliverable:** Full product catalog with prices and authorization levels.

---

### Week 6 (Apr 7–11): Client Management + Scales
| Day | Task | Owner |
|-----|------|-------|
| Mon | Build `ClientResource` (full form: NIT, SAP codes, channel, payment terms, location) | Dev 1 |
| Mon | Implement reactive city dropdown (department → city) using Filament `->reactive()` | Dev 1 |
| Tue | Build `ClientFilesRelationManager` with S3 upload | Dev 2 |
| Tue | Implement certificate PDF generation action in `ClientResource` | Dev 1 |
| Wed | Build `ScaleResource` with `ScalesLevelsRelationManager` | Dev 2 |
| Wed | Build `ClientxProductScaleResource` (assign scales to client-product pairs) | Dev 2 |
| Thu | Port `ClientsImport` + `ClientsExcelExport` | Dev 1 |
| Fri | Unit tests: location hierarchy, client imports, scale tier logic | Dev 1 + Dev 2 |

**Deliverable:** Client management with files, certificates, and scale assignments.

---

### Week 7 (Apr 14–17): Phase 2 Testing & Approval Gate
| Day | Task | Owner |
|-----|------|-------|
| Mon–Tue | Internal QA: test all master data scenarios, imports, PDF | QA |
| Mon–Tue | Fix bugs | Dev team |
| Wed | Deploy Phase 2 to staging | DevOps |
| Wed–Thu | **Stakeholder UAT — Phase 2** | Client |
| Fri | Address feedback; **Stakeholder sign-off on Phase 2** ← APPROVAL GATE | PM |

**Acceptance Criteria Check:**
- [ ] All parametrization tables editable
- [ ] Products with SAP codes and prices manageable
- [ ] Authorization levels set per product × channel
- [ ] Scale tiers configured correctly
- [ ] Client certificate PDF renders correctly
- [ ] Excel imports work for products, prices, clients

---

## 4. Phase 3 — Quotations (Weeks 8–11)

**Dates:** April 20 – May 15, 2026

### Week 8 (Apr 20–24): Quotation Form & Product Selection
| Day | Task | Owner |
|-----|------|-------|
| Mon | Build `QuotationResource` list view (with filters and badges) | Dev 1 |
| Mon | Build quotation create form: client select + channel auto-populate | Dev 1 |
| Tue | Implement product selection for quotation (filter by channel) | Dev 2 |
| Tue | Port `getProductsClient()` logic as Livewire action | Dev 2 |
| Wed | Port `calcProductQuota()` — price calculation with discount | Dev 1 |
| Wed | Implement discount validation against `Product_AuthLevels` | Dev 1 |
| Thu | Port `getPreviousProduct()` — check existing quotations for client+product | Dev 2 |
| Thu | Port `getHistoryProduct()` — pricing history for product+client | Dev 2 |
| Fri | Build payment terms selector per line; calculate totals | Dev 1 |

---

### Week 9 (Apr 27–May 1): Quotation Workflow & Status
| Day | Task | Owner |
|-----|------|-------|
| Mon | Implement quotation submit action (status 0 → 1) + notify authorizer | Dev 1 |
| Mon | Build authorizer selector (filtered by permission) | Dev 1 |
| Tue | Build `PreAuthorizationResource` — pre-approval workflow | Dev 2 |
| Tue | Build `AuthorizationResource` — full approval chain | Dev 2 |
| Wed | Implement status transition logic (all levels 1–4) | Dev 1 |
| Wed | Implement email-link authorization (signed token, no login required) | Dev 1 |
| Thu | Implement quotation rejection with mandatory comment | Dev 2 |
| Thu | Implement quotation cancellation action | Dev 2 |
| Fri | Port date-edit action (change validity dates without status reset) | Dev 1 |

---

### Week 10 (May 4–8): PDF, Email & Scheduler
| Day | Task | Owner |
|-----|------|-------|
| Mon | Port PDF generation using `DocFormat` templates + DOMPDF 3.x | Dev 1 |
| Tue | Implement email send action with recipient input modal | Dev 2 |
| Tue | Port `QuotationNotification` mail notification class | Dev 2 |
| Wed | Port `OrderNotificationsEvent` + `OrderNotificationsListener` | Dev 1 |
| Wed | Configure Pusher (or Laravel Reverb) for real-time notifications | Dev 2 |
| Thu | Implement `UpdateQuotationsStatus` scheduled command | Dev 1 |
| Thu | Port `QuotationxComments` (comments on quotation) | Dev 2 |
| Fri | Build quotation show/detail page with timeline and docs section | Dev 1 |

---

### Week 11 (May 11–15): Phase 3 Testing & Approval Gate
| Day | Task | Owner |
|-----|------|-------|
| Mon–Tue | Internal QA: full quotation lifecycle, all approval paths, PDF, email | QA |
| Mon–Tue | Fix bugs | Dev team |
| Wed | Deploy Phase 3 to staging | DevOps |
| Wed–Thu | **Stakeholder UAT — Phase 3** (CAM users + authorizers test) | Client |
| Fri | Address feedback; **Stakeholder sign-off on Phase 3** ← APPROVAL GATE | PM |

**Acceptance Criteria Check:**
- [ ] Full quotation creation with multiple product lines
- [ ] Discount validation enforces authorization thresholds
- [ ] All 4 approval levels chain correctly
- [ ] PDF generates with correct template
- [ ] Email sends with PDF attachment
- [ ] Scheduler cancels expired quotations

---

## 5. Phase 4 — Negotiations & Authorizations (Weeks 12–15)

**Dates:** May 18 – June 12, 2026

### Week 12 (May 18–22): Negotiation Form & Product Loading
| Day | Task | Owner |
|-----|------|-------|
| Mon | Build `NegotiationResource` list view | Dev 1 |
| Tue | Build negotiation create form: client + quotation reference | Dev 1 |
| Tue | Port `getProductsClientQuota()` — load from approved quotation | Dev 2 |
| Wed | Implement discount concept selector (`NegotiationConcepts`) | Dev 1 |
| Wed | Implement discount type (% vs COP amount) | Dev 2 |
| Thu | Port `calcDiscount()` — validate against `Product_AuthLevels` | Dev 1 |
| Thu | Implement `discount_acum` tracking across negotiations | Dev 2 |
| Fri | Implement `NegotiationErrors` recording for policy violations | Dev 1 |

---

### Week 13 (May 25–29): Scale Logic & Assisted Negotiation
| Day | Task | Owner |
|-----|------|-------|
| Mon | Port `negociacionAsistida()` — auto-apply scale discounts | Dev 1 |
| Tue | Port `negociacionAsistidaxConcepto()` — concept-based discounts | Dev 2 |
| Wed | Implement `suj_volumen` flag and volume compliance tracking | Dev 1 |
| Thu | Port `getProductsClientNego()` — for editing existing negotiations | Dev 2 |
| Thu | Port `updateNegotiation()` — edit negotiation details | Dev 1 |
| Fri | Port file attachment: `NegotiationDocs` + `storeNegotiationFiles()` | Dev 2 |

---

### Week 14 (Jun 1–5): Negotiation Post-Approval Logic
| Day | Task | Owner |
|-----|------|-------|
| Mon | Implement `updateNegotiationsbyAprovations()` — post-approval detail update | Dev 1 |
| Tue | Implement `pdf_content` caching for negotiation PDFs | Dev 2 |
| Wed | Port negotiation date-edit action | Dev 1 |
| Thu | Port `NegotiationComments` (comments on negotiation) | Dev 2 |
| Fri | Implement `UpdateNegotiationStatus` scheduled command | Dev 1 |

---

### Week 15 (Jun 8–12): Phase 4 Testing & Approval Gate
| Day | Task | Owner |
|-----|------|-------|
| Mon–Tue | Internal QA: full negotiation lifecycle, scale logic, discount accumulation | QA |
| Mon–Tue | Fix bugs | Dev team |
| Wed | Deploy Phase 4 to staging | DevOps |
| Wed–Thu | **Stakeholder UAT — Phase 4** | Client |
| Fri | Address feedback; **Stakeholder sign-off on Phase 4** ← APPROVAL GATE | PM |

---

## 6. Phase 5 — Liquidation, Credit Notes & SAP (Weeks 16–18)

**Dates:** June 15 – July 3, 2026

### Week 16 (Jun 15–19): Sales Import
| Day | Task | Owner |
|-----|------|-------|
| Mon | Port `SalesImport` with 17-field validation | Dev 1 |
| Tue | Build Filament custom page for sales file upload + preview | Dev 2 |
| Wed | Implement chunked import (1000 rows) with error collection | Dev 1 |
| Thu | Build import result summary (success count, error rows with details) | Dev 2 |
| Fri | Tests: valid import, missing field rejection, SAP code mismatch | Dev 1 |

---

### Week 17 (Jun 22–26): Credit Note Generation
| Day | Task | Owner |
|-----|------|-------|
| Mon | Port `Sales::ncEscalas()` — scale-based credit note calculation | Dev 1 |
| Tue | Implement credit note generation flow (path A: by scale) | Dev 1 |
| Tue | Implement credit note generation flow (path B: by bill) | Dev 2 |
| Wed | Build credit note review UI in Filament | Dev 2 |
| Thu | Port `NotesExport` — credit notes Excel report | Dev 1 |
| Fri | Tests: known sales data → verify credit note amounts | Dev 1 + Dev 2 |

---

### Week 18 (Jun 29–Jul 3): SAP Export & Phase 5 Gate
| Day | Task | Owner |
|-----|------|-------|
| Mon | Port `SapExport` — SAP-format CSV with exact column mapping | Dev 1 |
| Mon | Port `SapImport` + `GenSheetImport` — multi-sheet SAP response | Dev 2 |
| Tue | Build `SapNotesResource` Filament page (list, import, export, delete) | Dev 1 |
| Wed | Internal QA + fix bugs | QA + Dev team |
| Thu | Deploy to staging; **Stakeholder UAT — Phase 5** | Client |
| Fri | Address feedback; **Stakeholder sign-off on Phase 5** ← APPROVAL GATE | PM |

---

## 7. Phase 6 — ARP Simulator & Sales (Weeks 19–21)

**Dates:** July 6 – July 24, 2026

### Week 19 (Jul 6–10): ARP Configuration
| Day | Task | Owner |
|-----|------|-------|
| Mon | Build `ArpResource` — annual ARP configuration | Dev 1 |
| Mon | Build `ServiceArpResource` + `ArpServiceRelationManager` | Dev 1 |
| Tue | Build `BusinessArpResource` — PBC values | Dev 2 |
| Wed | Port `ArpImport` — Excel ARP simulation import with validation | Dev 1 |
| Thu | Build `ArpSimulationResource` — simulation management page | Dev 2 |
| Fri | Tests: ARP import with sample file | Dev 1 |

---

### Week 20 (Jul 13–17): ARP Simulation & Export
| Day | Task | Owner |
|-----|------|-------|
| Mon | Port `ArpSimulationsExport` — multi-sheet Excel export | Dev 1 |
| Tue | Port `ArpSimulationsSheets`, `ArpSpecialSheet`, `ArpVersionSheets` | Dev 1 |
| Wed | Build ARP simulation filters (brand, year, version, quarter) | Dev 2 |
| Thu | Port `scopeSumDetails()` — simulation summary by client+product | Dev 2 |
| Fri | Tests: ARP export generates all 3 sheet types correctly | Dev 1 + Dev 2 |

---

### Week 21 (Jul 20–24): Phase 6 Testing & Approval Gate
| Day | Task | Owner |
|-----|------|-------|
| Mon–Tue | Internal QA | QA |
| Wed | Deploy to staging; **Stakeholder UAT — Phase 6** | Client |
| Thu | Address feedback | Dev team |
| Fri | **Stakeholder sign-off on Phase 6** ← APPROVAL GATE | PM |

---

## 8. Phase 7 — Document Management (Week 22)

**Dates:** July 27 – August 1, 2026

### Week 22 (Jul 27–Aug 1)
| Day | Task | Owner |
|-----|------|-------|
| Mon | Build `FolderResource` with tree-view and file upload to S3 | Dev 1 |
| Tue | Implement signed URL generation for file sharing | Dev 2 |
| Tue | Implement `sharedDocsSendEmail` — email link sharing | Dev 2 |
| Wed | Build `DocFormatResource` — quotation template editor (rich text) | Dev 1 |
| Wed | Build `DocFormatCertificateResource` — certificate template editor | Dev 1 |
| Thu | Internal QA: folder navigation, file sharing, template rendering | QA |
| Fri | Deploy + **Stakeholder UAT — Phase 7**; **Sign-off** ← APPROVAL GATE | PM + Client |

---

## 9. Phase 8 — Reporting & Dashboard (Weeks 23–24)

**Dates:** August 3 – August 14, 2026

### Week 23 (Aug 3–7): Reports & Exports
| Day | Task | Owner |
|-----|------|-------|
| Mon | Build `ReportResource` — quotation + negotiation reports | Dev 1 |
| Tue | Port `ReportExport`, `QuotationExport`, `NegotiationExport` | Dev 1 |
| Tue | Port `ClientsExcelExport` — client report export | Dev 2 |
| Wed | Build credit notes report (`NotesExport` used here) | Dev 2 |
| Thu | Implement all report filters (date range, client, status, channel) | Dev 1 |
| Fri | Tests: verify report data matches current system output | Dev 1 + Dev 2 |

---

### Week 24 (Aug 10–14): Dashboard, Notifications & Final QA
| Day | Task | Owner |
|-----|------|-------|
| Mon | Build Filament dashboard with stats overview widget | Dev 1 |
| Mon | Build pending approvals widget (filtered for current user) | Dev 2 |
| Tue | Build expiring soon widget, quotation status chart | Dev 1 |
| Tue | Implement in-app notification bell widget | Dev 2 |
| Wed | Full end-to-end regression testing (all modules) | QA |
| Thu | Deploy Phase 8 to staging; **Full system UAT** | Client |
| Fri | **Final Stakeholder Sign-off — Complete System** ← APPROVAL GATE | PM |

---

## 10. Buffer & Integration (Weeks 25–26)

**Dates:** August 17 – August 28, 2026

### Week 25 (Aug 17–21): Buffer & Go-Live Preparation
| Task | Owner |
|------|-------|
| Address all outstanding UAT feedback from all phases | Dev team |
| Performance testing: load test with realistic data volumes | DevOps |
| Security review: penetration test or manual audit | Tech Lead |
| Final database backup procedure documented | DevOps |
| Production environment final configuration check | DevOps |
| User training materials prepared (quick-start guides) | PM |
| User training sessions scheduled | PM |

### Week 26 (Aug 24–28): Go-Live Week 🚀
| Day | Task | Owner |
|-----|------|-------|
| Mon | Final production database backup | DevOps |
| Mon | Deploy complete new system to production | DevOps |
| Mon | Verify all integrations: Auth0, S3, SES/Mailgun, Pusher | DevOps + Dev team |
| Tue | Enable new system for pilot users (admin + IT team) | PM |
| Tue | Monitor error logs for first 4 hours | Dev team |
| Wed | **GO-LIVE: Open to all users** | PM |
| Wed | Communicate launch to all Novo Nordisk Colombia users | PM |
| Wed–Fri | Intensive support: standby for issues | Dev team |
| **Fri Aug 28** | **🚀 SYSTEM IS LIVE** | All |

---

## 11. Approval Gates

Summary of all required stakeholder sign-off points:

| Gate | Phase | Date | What Requires Approval |
|------|-------|------|----------------------|
| Gate 1 | Phase 1 | **Mar 21, 2026** | Auth, login, users, roles, permissions |
| Gate 2 | Phase 2 | **Apr 17, 2026** | Products, prices, clients, scales |
| Gate 3 | Phase 3 | **May 15, 2026** | Full quotation workflow + PDF + email |
| Gate 4 | Phase 4 | **Jun 12, 2026** | Full negotiation workflow + discounts |
| Gate 5 | Phase 5 | **Jul 3, 2026** | Sales import + credit notes + SAP export |
| Gate 6 | Phase 6 | **Jul 24, 2026** | ARP simulator + Excel reports |
| Gate 7 | Phase 7 | **Aug 1, 2026** | Document management + format templates |
| Gate 8 | Phase 8 | **Aug 14, 2026** | Complete system — final approval |

**Gate Protocol:**
1. Dev team notifies PM that phase is ready for UAT (minimum 2 business days' notice)
2. Stakeholders test in staging (minimum 3 business days)
3. Issues logged in issue tracker; severity classified (blocker / major / minor)
4. Blockers and majors must be resolved before sign-off
5. Minor issues may be deferred to buffer week
6. Sign-off delivered as written confirmation (email or ticket)
7. Dev team must receive written sign-off before proceeding to next phase

**Escalation Path:** If stakeholders cannot provide sign-off within the allotted window, PM escalates to project sponsor to decide: extend timeline or accept pending minors.

---

## 12. Rollback Plan

### Per-Phase Rollback
Each module release has an independent rollback procedure:
1. Old system module remains active for **2 weeks after new module goes live**
2. If critical issue detected: disable new module (feature toggle off)
3. Users automatically fall back to old system
4. No data loss: both systems share the same `nvn_*` tables

### Trigger Criteria for Rollback
| Trigger | Action |
|---------|--------|
| Data calculation error (discounts, credit notes) | Immediate rollback + investigation |
| Authentication failure > 5% of logins | Rollback auth + emergency fix |
| SAP export format mismatch | Rollback Phase 5 module |
| Error rate > 2% sustained for 1 hour | Escalate to PM → decision within 1 hour |
| Database corruption | Emergency rollback ALL + restore from backup |

### Final Go-Live Rollback Window
- After Aug 28 go-live, old system maintained in **read-only mode for 30 days**
- If critical issue in first 30 days: old system can be re-activated within 2 hours
- Old system decommissioned: **September 27, 2026** (30 days post go-live)

### Emergency Contacts
| Role | Responsibility |
|------|---------------|
| Tech Lead | Technical decision on rollback |
| DevOps | Execute infrastructure rollback |
| PM | Business decision + stakeholder communication |
| Novo Nordisk IT Lead | Auth0 + SAP coordination |

