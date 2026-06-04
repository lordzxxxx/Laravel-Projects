<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsClient
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect('/login');
        }

        $currentTenant = Tenant::current();

        // Client accounts may access any tenant domain (tenant scoping happens at the data/query layer).
        // Do not block them based on `tenant_id`, since traveller accounts can be municipality-wide.

        // Default-deny: only `client` may proceed. Unknown roles are rejected outright.
        if ($user->role !== 'client') {
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard')
                    ->with('error', 'This section is for clients only.');
            }

            if ($user->role === 'owner') {
                return redirect('/owner/dashboard')
                    ->with('error', 'This section is for clients only.');
            }

            return redirect('/')
                ->with('error', 'This section is for clients only.');
        }

        return $next($request);
    }
}
