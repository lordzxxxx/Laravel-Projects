# Single-DB ETL Runbook

## Commands

- `php artisan single-db:etl --dry-run`
- `php artisan single-db:etl --tenant=123`
- `php artisan single-db:etl --tables=users,accommodations,bookings,messages --chunk=200`
- `php artisan single-db:reconcile --tenant=123`

## Order of Import

Default order is intentionally dependency-safe:

1. `users`
2. `accommodations` (depends on users/owners)
3. `bookings` (depends on accommodations + users/clients)
4. `messages` (depends on users + bookings)

## Tracking Tables

- `single_db_legacy_id_maps`
  - maps `tenant_id + table_name + legacy_id -> new_id`
- `single_db_migration_checkpoints`
  - stores per-tenant/table ETL progress and completion timestamp

## Safety Notes

- Run dry-run first in staging.
- Import by tenant in batches for easier rollback.
- Re-run is incremental due to ID map/checkpoint tracking.
- Use `single-db:reconcile` after each tenant migration batch.
