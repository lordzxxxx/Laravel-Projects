# Single-DB Rollout and Cutover Checklist

## Rollout Stages

1. Enable migration mode with legacy behavior:
   - `SINGLE_DB_MIGRATION_ENABLED=true`
   - `SINGLE_DB_READS=false`
   - `SINGLE_DB_WRITES=false`
2. Run ETL + reconcile repeatedly until no backlog.
3. Enable read shadowing:
   - `SINGLE_DB_SHADOW_READS=true`
4. Enable single-db reads:
   - `SINGLE_DB_READS=true`
5. Enable single-db writes:
   - `SINGLE_DB_WRITES=true`

## Commands

- `php artisan single-db:status`
- `php artisan single-db:etl --chunk=200`
- `php artisan single-db:reconcile`
- `php artisan single-db:verify-shadow`
- `php artisan single-db:final-delta`
- `php artisan single-db:cutover-readiness`

## Cutover Window Steps

1. Pause queue workers.
2. Run `single-db:final-delta`.
3. Confirm `single-db:cutover-readiness` is green.
4. Flip reads+writes flags to true.
5. Resume queue workers.
6. Monitor error logs and shadow mismatch counts.

## Rollback

If severe issues occur:

1. Set `SINGLE_DB_READS=false`.
2. Keep writes frozen while assessing data divergence.
3. Re-run ETL/reconcile as needed.
4. Restore legacy provisioning/switching flags if rollback is long-lived.
