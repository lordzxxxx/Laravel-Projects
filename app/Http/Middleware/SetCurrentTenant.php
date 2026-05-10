<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\SingleDbMigrationMode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (SingleDbMigrationMode::readsEnabled()) {
            $this->bindTenantWhenMissing($request);

            return $next($request);
        }

        if (app()->environment('testing')) {
            $landlordDb = (string) config('database.connections.landlord.database', '');

            if ($landlordDb === ':memory:' || $landlordDb === '') {
                return $next($request);
            }
        }

        $this->bindTenantWhenMissing($request);

        return $next($request);
    }

    /**
     * Ensure Spatie current tenant is set for code that calls Tenant::current(),
     * including single-database unified mode (connection switching may still be a no-op).
     */
    private function bindTenantWhenMissing(Request $request): void
    {
        if (Tenant::checkCurrent()) {
            return;
        }

        $user = $request->user();

        if (! $user) {
            return;
        }

        if ($user->isOwner()) {
            $tenant = $user->ensureTenant();
            if ($tenant) {
                $tenant->makeCurrent();
            }

            return;
        }

        if ($user->isAdmin() && $user->tenant_id) {
            $tenant = Tenant::query()->find($user->tenant_id);
            if ($tenant) {
                $tenant->makeCurrent();
            }
        }
    }
}
