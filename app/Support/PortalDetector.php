<?php

namespace App\Support;

use Illuminate\Http\Request;

final class PortalDetector
{
    public static function centralHosts(): array
    {
        $centralDomain = env('CENTRAL_DOMAIN', parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost');

        return array_values(array_unique([
            $centralDomain,
            'localhost',
            '127.0.0.1',
            '::1',
        ]));
    }

    public static function hostWithoutPort(string $host): string
    {
        $hostWithoutPort = $host;
        if ($host !== '' && $host[0] === '[') {
            $end = strpos($host, ']');

            return $end !== false ? substr($host, 1, $end - 1) : $host;
        }
        if (str_contains($host, ':') && strrpos($host, ':') === strpos($host, ':')) {
            return substr($host, 0, (int) strrpos($host, ':'));
        }

        return $hostWithoutPort;
    }

    public static function isCentralHost(Request $request): bool
    {
        $centralDomain = env('CENTRAL_DOMAIN', 'localhost');
        $domainWithoutPort = self::hostWithoutPort($request->getHost());

        return in_array($domainWithoutPort, [$centralDomain, '127.0.0.1', 'localhost', '::1'], true);
    }

    public static function adminPort(): int
    {
        return (int) config('portals.admin_port', 8000);
    }

    public static function publicPort(): int
    {
        return (int) config('portals.public_port', 8005);
    }

    public static function requestPort(Request $request): int
    {
        return (int) $request->getPort();
    }

    public static function isAdminPortal(Request $request): bool
    {
        return self::isCentralHost($request) && self::requestPort($request) === self::adminPort();
    }

    public static function isPublicPortal(Request $request): bool
    {
        return self::isCentralHost($request) && self::requestPort($request) === self::publicPort();
    }

    public static function isKnownCentralPortal(Request $request): bool
    {
        return self::isCentralHost($request)
            && in_array(self::requestPort($request), [self::adminPort(), self::publicPort()], true);
    }

    /**
     * Base URL for the public municipality portal (different HTTP port from admin).
     */
    public static function publicPortalOrigin(Request $request): string
    {
        $host = self::hostWithoutPort($request->getHttpHost());

        return sprintf('%s://%s:%d', $request->getScheme(), $host, self::publicPort());
    }
}
