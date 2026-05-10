<?php

namespace App\Http\Middleware;

use App\Support\PortalDetector;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts matched routes by HTTP port (admin vs public municipality portal).
 */
class EnsureCentralPortalPort
{
    public function handle(Request $request, Closure $next, string $mode = 'any'): Response
    {
        if (! PortalDetector::isCentralHost($request)) {
            abort(404);
        }

        if (app()->runningUnitTests()) {
            return $next($request);
        }

        $port = PortalDetector::requestPort($request);
        $admin = PortalDetector::adminPort();
        $public = PortalDetector::publicPort();

        if ($mode === 'admin' && $port !== $admin) {
            abort(404);
        }

        if ($mode === 'public' && $port !== $public) {
            abort(404);
        }

        if ($mode === 'any' && ! in_array($port, [$admin, $public], true)) {
            abort(404);
        }

        return $next($request);
    }
}
