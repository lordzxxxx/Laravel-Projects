<?php

namespace App\Http\Middleware;

use App\Support\PortalDetector;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRequestUsesTenantPort
{
    public function handle(Request $request, Closure $next): Response
    {
        // Extract domain from host header (without port)
        $host = $request->getHost();

        // Handle IPv6 format [::1]:8000 and IPv4 format 127.0.0.1:8000
        // getHost() returns just the host without port, but handle edge cases
        $hostWithoutPort = $host;
        if (strpos($host, '[') === 0) {
            // IPv6 format: extract from [::1]
            $hostWithoutPort = substr($host, 1, strpos($host, ']') - 1);
        } elseif (strpos($host, ':') !== false && strpos($host, ':') === strrpos($host, ':')) {
            // IPv4 format with port: extract before last colon
            $hostWithoutPort = substr($host, 0, strrpos($host, ':'));
        }

        $centralDomain = env('CENTRAL_DOMAIN', 'localhost');

        // Check if it's a central domain (should not be for tenant routes)
        // Central domains: localhost, 127.0.0.1, ::1, or CENTRAL_DOMAIN
        $isCentralDomain = in_array($hostWithoutPort, [$centralDomain, '127.0.0.1', 'localhost', '::1'], true);

        if (! $isCentralDomain) {
            return $next($request);
        }

        // On localhost-style hosts the municipality / tenant app often runs on a different HTTP port than the
        // central admin app (see PortalDetector). Block only the admin portal port; allow public / tenant port(s).
        if (PortalDetector::isAdminPortal($request)) {
            abort(404);
        }

        $requestPort = PortalDetector::requestPort($request);
        $tenantPorts = array_values(array_unique(array_filter([
            PortalDetector::publicPort(),
            (int) env('TENANT_PORT', 0),
        ], static fn (int $p): bool => $p > 0)));

        if (in_array($requestPort, $tenantPorts, true)) {
            return $next($request);
        }

        abort(404);
    }
}
