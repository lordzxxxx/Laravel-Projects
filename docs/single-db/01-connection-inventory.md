# Single-DB Migration Inventory

This file catalogs runtime and operational touchpoints that currently assume landlord + per-tenant databases.

## Runtime Connection Routing

- `config/multitenancy.php`
  - Defines tenant finder, tenant switch tasks, and landlord/tenant connection names.
- `config/database.php`
  - Defines `landlord` and mutable `tenant` database connections.
- `app/Multitenancy/Tasks/SwitchTenantDatabaseConnectionTask.php`
  - Rewrites `database.connections.tenant` at runtime with tenant-specific DB credentials.
- `app/Multitenancy/TenantFinder/PortTenantFinder.php`
  - Resolves the current tenant from request host/port context.
- `app/Http/Middleware/SetCurrentTenant.php`
  - Sets tenant context for owner-authenticated requests.

## Model Connection Traits

- `app/Models/Concerns/UsesTenantConnectionForTenantData.php`
  - Routes models to tenant DB when tenant context/user tenant exists.
- `app/Models/Concerns/UsesTenantConnectionWithLandlordFallback.php`
  - Uses landlord for central requests and tenant DB for tenant app requests.
- `app/Models/Concerns/UsesPermissionTablesConnection.php`
  - Routes Spatie permission tables between landlord/tenant.

## Commands and Operational Flows

- `routes/console.php`
  - `tenants:migrate`: runs tenant migration path against each tenant DB.
  - `tenants:provision-db`: creates MySQL schema/user/grants and migrates tenant DB.
  - `tenants:sync-rbac`: migrates tenant schema and reseeds tenant RBAC.
  - `tenants:reprovision`: re-provisions tenant DBs.
- `app/Console/Commands/*` tenant provisioning and validation commands
  - `CheckTenantProvisioning`, `ReprovisionTenantDatabase`, `CreateTenantAdminAccount`.

## Risk-Heavy Areas for Single-DB Cutover

- Tenant DB credential lifecycle stored on `tenants` (`database`, `db_host`, `db_username`, etc.).
- Runtime DB connection rewrites in `SwitchTenantDatabaseConnectionTask`.
- Tenant queue awareness and context binding from Spatie multitenancy config.
- Provisioning/deprovisioning flows that execute landlord SQL (`CREATE DATABASE`, `GRANT`).
- Traits silently routing reads/writes to different DBs.

## Cutover Readiness Checklist (Inventory Stage)

- [ ] Every trait/model path mapped to single-db behavior toggle.
- [ ] Every tenant command tagged as keep, replace, or deprecate.
- [ ] Every cross-tenant/central query path identified for verification.
- [ ] Every queue job checked for tenant context assumptions.
