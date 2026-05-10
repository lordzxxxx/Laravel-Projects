<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Tenant;
use App\Support\PortalDetector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    private const TENANT_SESSION_KEY = 'ensure_valid_tenant_session_tenant_id';

    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $intendedParam = $request->query('intended');
        if (is_string($intendedParam) && $intendedParam !== '') {
            $decoded = rawurldecode($intendedParam);
            if ($decoded !== '' && $this->isSafeTenantIntendedUrl($request, $decoded)) {
                $request->session()->put('url.intended', $decoded);
            }
        }

        if (Tenant::checkCurrent()) {
            $portal = $request->query('portal');
            // Unit owners use the same staff portal rules as tenant admins; `owner` is a UI alias.
            if (! in_array($portal, ['admin', 'client', 'owner'], true)) {
                $portal = null;
            }

            return view('tenant.auth.login', [
                'tenant' => Tenant::current(),
                'portal' => $portal,
            ]);
        }

        if (! PortalDetector::isCentralHost($request)) {
            return view('auth.login-public');
        }

        if (PortalDetector::isAdminPortal($request)) {
            return view('auth.login-admin');
        }

        if (PortalDetector::isPublicPortal($request)) {
            return view('auth.login-public');
        }

        return view('auth.login-public');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $portal = $request->input('portal');
        if ($portal === 'owner') {
            $portal = 'admin';
        }
        if (! in_array($portal, ['admin', 'client'], true)) {
            $portal = null;
        }

        $request->authenticate();

        $currentTenant = Tenant::current();
        $user = $request->user();

        if ($user && ! $user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Your account is inactive and cannot access the workspace. Ask your supervisor or delegated administrator to review your eligibility.',
            ])->onlyInput('email');
        }

        if (! $currentTenant && $user?->isClient()) {
            if (! PortalDetector::isPublicPortal($request)) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Lodging-linked guest profiles must sign in through that operator\'s dedicated website or mobile experience. Municipality-wide traveller accounts should use their hospitality gateway entry.',
                ])->onlyInput('email');
            }
        }

        if (PortalDetector::isCentralHost($request) && PortalDetector::isKnownCentralPortal($request)) {
            $adminPortal = PortalDetector::isAdminPortal($request);
            $publicPortal = PortalDetector::isPublicPortal($request);

            if ($adminPortal && $user && ! ($user->isAdmin() && $user->tenant_id === null)) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'This console is exclusively for accredited municipality administrators and supervisory roles. Travellers and lodging operators must use their designated hospitality entry.',
                ])->onlyInput('email');
            }

            if ($publicPortal && $user?->isAdmin() && $user->tenant_id === null) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Administrator credentials must be used on the secure administration sign-in pathway provided to municipal staff—not on traveller or lodging-operator pages.',
                ])->onlyInput('email');
            }

            if ($publicPortal && $user?->isClient() && $user->tenant_id !== null) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your profile is associated with a lodging operator. Continue through that operator\'s branded sign-in page to access services linked to them.',
                ])->onlyInput('email');
            }
        }

        // In tenant context, enforce tenant membership for all role types.
        if ($currentTenant && $user) {
            if ($portal === 'client' && ($user->isOwner() || $user->isAdmin())) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'You selected a guest or traveller path. Property owners and tenant administrators should choose the management sign-in option for this property.',
                ])->onlyInput('email');
            }

            if ($portal === 'admin' && $user->isClient()) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'You selected a property management path. Traveller and guest accounts should select the guest sign-in option for this property.',
                ])->onlyInput('email');
            }

            $belongsToCurrentTenant = false;

            if ($user->isOwner()) {
                $belongsToCurrentTenant = (int) ($user->tenant_id ?? 0) === (int) $currentTenant->id
                    || (int) optional($user->ownedTenant)->id === (int) $currentTenant->id;
            } elseif ($user->isAdmin() || $user->isClient()) {
                $belongsToCurrentTenant = (int) ($user->tenant_id ?? 0) === (int) $currentTenant->id;
            }

            if (! $belongsToCurrentTenant) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'This account is not provisioned for this property. Verify you are using the correct organization link, or request an invitation from the property administrator.',
                ])->onlyInput('email');
            }
        }

        // Prevent central admins from authenticating on a tenant subdomain.
        if ($currentTenant && $user?->isAdmin() && (int) ($user->tenant_id ?? 0) !== (int) $currentTenant->id) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'This administrative profile is not linked to this property. Use the national or municipal administration console, or the correct property domain.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Update last login
        $user?->updateLastLogin();

        // Keep tenant session marker in sync so tenant.session middleware doesn't immediately log users out.
        if ($currentTenant) {
            $request->session()->put(self::TENANT_SESSION_KEY, $currentTenant->getKey());
        } else {
            $request->session()->forget(self::TENANT_SESSION_KEY);
        }

        // In tenant mode, redirect to the correct tenant dashboard by role (honour url.intended, e.g. after landing CTA → login).
        if ($currentTenant) {
            if ($user?->isOwner() || $user?->isAdmin()) {
                return $this->tenantSafeIntendedRedirect($request, '/owner/dashboard');
            }

            return $this->tenantSafeIntendedRedirect($request, '/dashboard');
        }

        return redirect()->intended($request->user()->getDashboardRoute());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $isTenantContext = Tenant::checkCurrent();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $isTenantContext ? redirect()->to('/') : redirect('/');
    }

    private function tenantSafeIntendedRedirect(Request $request, string $fallback): RedirectResponse
    {
        $intended = (string) $request->session()->get('url.intended', '');

        if ($intended !== '' && ! $this->isSafeTenantIntendedUrl($request, $intended)) {
            $request->session()->forget('url.intended');

            return redirect()->to($fallback);
        }

        return redirect()->intended($fallback);
    }

    private function isSafeTenantIntendedUrl(Request $request, string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        $scheme = parse_url($url, PHP_URL_SCHEME);

        // Relative intended URLs are safe on the current host.
        if (! is_string($host) || $host === '') {
            return true;
        }

        if (strcasecmp($host, $request->getHost()) !== 0) {
            return false;
        }

        $requestPort = (int) $request->getPort();
        $defaultPort = (($scheme ?: $request->getScheme()) === 'https') ? 443 : 80;
        $targetPort = is_numeric($port) ? (int) $port : $defaultPort;

        return $targetPort === $requestPort;
    }
}
