<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
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

        if ($currentTenant && (int) ($user->tenant_id ?? 0) !== (int) $currentTenant->id) {
            return redirect('/')
                ->with('error', 'You do not have access to this tenant.');
        }

        // Default-deny: only `admin` may proceed. Unknown roles are rejected outright.
        if ($user->role !== 'admin') {
            if ($user->role === 'owner') {
                return redirect('/owner/dashboard')
                    ->with('error', 'This section is for administrators only.');
            }

            if ($user->role === 'client') {
                return redirect('/dashboard')
                    ->with('error', 'This section is for administrators only.');
            }

            return redirect('/')
                ->with('error', 'This section is for administrators only.');
        }

        return $next($request);
    }
}
