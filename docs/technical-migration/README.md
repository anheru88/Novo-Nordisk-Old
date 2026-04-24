# Novo Nordisk CAM Tool — Technical Migration Documentation

> **Migration Target (actual, in progress):** Laravel 6.2 (PHP 7.2) → **Laravel 13.6 (PHP 8.4) + FilamentPHP 5.6**
> **Audience:** Development team, technical lead, project manager
> **Last Updated:** April 2026
>
> ⚠️ Parts of these docs were drafted against an earlier target (L12 + Filament 3). The live stack has since moved to L13 + Filament 5; treat references to `L12`/`Filament 3` as historical intent. The **business logic, data flow, permissions and module scope remain valid**.

---

## 📂 Document Index

| File | Purpose | Primary Audience |
|------|---------|-----------------|
| [current-system-architecture.md](./current-system-architecture.md) | Full inventory of existing system: models, DB schema, auth, permissions, integrations | Developers, Architects |
| [migration-action-plan.md](./migration-action-plan.md) | Phase-by-phase migration strategy with dependencies and checkpoints | Tech Lead, PM |
| [detailed-timeline.md](./detailed-timeline.md) | Week-by-week schedule, deliverables, testing windows, go-live plan | PM, Stakeholders |
| [backend-functional-specs.md](./backend-functional-specs.md) | Business rules, validation logic, API contracts, security rules, job queues | Developers |

---

## 🎯 Migration Goal

Rebuild the Novo Nordisk Colombia commercial process management platform ("CAM Tool") using **Laravel 12 + FilamentPHP 3.x**, preserving 100% of existing business logic while eliminating the security and maintainability debt of the current Laravel 6 / PHP 7.2 stack.

### Current State (as of 2026)
- **Framework:** Laravel 6.2 — end-of-life September 2022
- **PHP:** 7.2 — end-of-life December 2019
- **Auth:** Laravel Passport (API) + Auth0 SSO + session-based web auth
- **Authorization:** Caffeinated Shinobi 4.x (RBAC)
- **Database:** MySQL with `nvn_` prefixed tables (~50 tables)
- **Frontend:** AdminLTE 3 + Blade templates + Vue.js (minimal)
- **Key packages:** Maatwebsite Excel, barryvdh/laravel-dompdf, Pusher, AWS SDK

### Target State (current)
- **Framework:** Laravel 13.6
- **PHP:** 8.4
- **Admin Panel:** FilamentPHP 5.6 (Livewire-based, no separate SPA) — panel path `/admin`, provider `App\Providers\Filament\AdminPanelProvider`
- **Auth:** Laravel session auth + Auth0 SSO (same tenant) — Sanctum available for future API tokens
- **Authorization:** `spatie/laravel-permission` (replaced legacy Caffeinated Shinobi)
- **Database:** MySQL; schema being normalized incrementally (PKs → `id`, FKs → `{table}_id`, attribute columns unprefixed). The legacy `nvn_` dump is seeded via `LocalSeeders` with column mapping.
- **Infrastructure:** local dev on Herd / `novo.test`; cloud target TBD.

### Progress Snapshot (April 2026)
- ✅ Spatie Permission replacing Shinobi (see commit `b5a0dac`).
- ✅ Filament 5 admin panel + RBAC resources + sidebar navigation.
- ✅ PK/FK normalization (`c975589`).
- ✅ Attribute column normalization for 28 tables (`e48ce3a`).
- ✅ Documents module: Folder explorer + Client files explorer rebuilt in Filament 5 Infolist (breadcrumbs, click-to-download, missing-file validation, native search).
- 🚧 Brands, Users, Roles resources scaffolded.
- ⬜ Quotations, Negotiations, Authorizations, Liquidation, ARP Simulator, Reporting — pending.

---

## 📋 System Module Map

The application has **11 functional modules** (8 from original RFP + 3 discovered during audit):

| # | Module | Priority | Current Status |
|---|--------|----------|---------------|
| 1 | **Users & Roles** | Critical | Active (Shinobi) |
| 2 | **Parametrization** (Clients, Products, Prices) | Critical | Active |
| 3 | **Quotations** (Cotizaciones) | Critical | Active |
| 4 | **Negotiations** (Negociaciones) | Critical | Active |
| 5 | **Authorization Workflow** | Critical | Active |
| 6 | **Liquidation & Credit Notes** | High | Active (SAP integration) |
| 7 | **Document Repository** | High | Active |
| 8 | **Reporting & Exports** | High | Active |
| 9 | **ARP Simulator** *(discovered)* | High | Active |
| 10 | **Sales Management** *(discovered)* | Medium | Active |
| 11 | **Document Formats** *(discovered)* | Medium | Active |

---

## 🔑 Key Technical Decisions

1. **Spatie Laravel Permission** replaces Caffeinated Shinobi — same permission concept, better Laravel 12 support, active maintenance, and native Filament integration.
2. **Laravel Sanctum** replaces Laravel Passport for web session auth — simpler, no OAuth server overhead for a single-tenant app.
3. **Auth0 SSO is retained** — same Auth0 tenant (`novonordiskco.auth0.com`); only the Laravel SDK is upgraded.
4. **Database prefix `nvn_` is preserved** — no table renames during migration to allow parallel operation.
5. **Incremental module-by-module migration** — both systems can run simultaneously during transition.
6. **No REST API rebuild** — current API routes are mostly commented-out; FilamentPHP serves as the UI layer directly.

---

## 🧭 Recommended Reading Order

### For Developers (new to the project)
1. `current-system-architecture.md` → understand what exists
2. `backend-functional-specs.md` → understand business rules
3. `migration-action-plan.md` → understand the build order
4. `detailed-timeline.md` → understand deadlines

### For Technical Lead / Architect
1. `migration-action-plan.md` → phase dependencies
2. `current-system-architecture.md` → integration points
3. `backend-functional-specs.md` → validation and security rules
4. `detailed-timeline.md` → approval gates

### For Project Manager
1. `detailed-timeline.md` → schedule and milestones
2. `migration-action-plan.md` → phase deliverables
3. `README.md` (this file) → module map

---

## ⚠️ Critical Risks to Track

| Risk | Mitigation |
|------|-----------|
| Business logic loss during rewrite | `backend-functional-specs.md` documents every rule |
| Shinobi → Spatie permission mapping errors | Explicit migration SQL in `migration-action-plan.md` |
| SAP integration breakage | Integration specs in `backend-functional-specs.md` |
| Parallel system data divergence | Module cutover protocol in `migration-action-plan.md` |
| Auth0 token compatibility | Auth migration section in `migration-action-plan.md` |

---

*For questions about this documentation, contact the technical lead before starting development on any phase.*

