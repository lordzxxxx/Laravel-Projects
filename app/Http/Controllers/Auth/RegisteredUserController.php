<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantLifecycleLog;
use App\Models\User;
use App\Services\CentralAdminNotifier;
use App\Support\MunicipalityDocumentUploads;
use App\Support\PortalDetector;
use App\Support\TenantLogoProcessor;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View|RedirectResponse
    {
        if (Tenant::checkCurrent() && ! Tenant::isRequestHostForCentralLandlordApp(request())) {
            return view('tenant.auth.register', [
                'tenant' => Tenant::current(),
            ]);
        }

        if (Tenant::isRequestHostForCentralLandlordApp(request())) {
            return view('auth.register-choice');
        }

        return view('auth.register-wizard');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $tenant = Tenant::current();
        // Spatie resolves a current tenant from the request host; that must not override
        // central owner registration when the host is the landlord app (see Tenant::isRequestHostForCentralLandlordApp).
        $isTenantSignup = ! Tenant::isRequestHostForCentralLandlordApp($request) && Tenant::checkCurrent();
        $provisionedTenant = null;

        if (! $isTenantSignup) {
            $requestedRole = (string) $request->input('role', '');
            if ($requestedRole === '' || ! in_array($requestedRole, [User::ROLE_CLIENT, User::ROLE_OWNER], true)) {
                $request->merge(['role' => User::ROLE_OWNER]);
            }
        }

        $role = $isTenantSignup ? User::ROLE_CLIENT : (string) $request->input('role');

        // Wizard sends both color pickers and text hex fields; validate the canonical hex the user edited last.
        foreach (['primary_color', 'accent_color'] as $colorKey) {
            $hexKey = $colorKey.'_hex';
            $hex = (string) $request->input($hexKey, '');
            if (preg_match('/^#[0-9A-F]{6}$/i', $hex)) {
                $request->merge([$colorKey => strtoupper($hex)]);
            }
        }

        // Validate basic fields
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => $isTenantSignup ? ['nullable', 'in:client'] : ['nullable', 'in:client,owner'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        // Add customization validation for owners (includes resolved default owner on central)
        $isOwnerRegistration = ! $isTenantSignup && $role === User::ROLE_OWNER;
        if ($isOwnerRegistration) {
            // Broken / oversized PHP uploads still appear as a file key with isValid() === false;
            // that triggers "failed to upload" unless we drop the entry and treat as no logo.
            if ($request->hasFile('logo_path') && ! $request->file('logo_path')->isValid()) {
                $request->files->remove('logo_path');
            }

            $rules = array_merge($rules, [
                'subscription_plan' => ['nullable', 'in:basic,plus,pro,promo'],
                'app_title' => ['nullable', 'string', 'max:255'],
                'primary_color' => ['nullable', 'regex:/^#[0-9A-F]{6}$/i'],
                'accent_color' => ['nullable', 'regex:/^#[0-9A-F]{6}$/i'],
                'locale' => ['nullable', 'in:en,es,fr,de'],
                'logo_path' => ['nullable', 'image', 'max:10240', 'mimes:jpeg,jpg,png,gif,webp,bmp'],
                'feature_bookings' => ['nullable', 'in:0,1'],
                'feature_messaging' => ['nullable', 'in:0,1'],
                'feature_reviews' => ['nullable', 'in:0,1'],
                'feature_payments' => ['nullable', 'in:0,1'],
            ]);
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            // Keep guest accounts municipality-wide so one login works across tenant domains.
            'tenant_id' => null,
            'phone' => $request->phone,
        ]);

        $user->syncRbacFromLegacyRole();

        if ($isTenantSignup && $tenant) {
            // Keep it best-effort: some installations may require per-tenant permission sync even for tenant_id null.
            try {
                $user->syncEffectiveTenantPermissions($tenant);
            } catch (\Throwable) {
            }
        }

        if (! $isTenantSignup && $user->isOwner()) {
            $customizationData = null;
            if ($isOwnerRegistration) {
                $customizationData = [
                    'subscription_plan' => $request->input('subscription_plan'),
                    'app_title' => $request->input('app_title'),
                    'primary_color' => $request->input('primary_color', '#2E7D32'),
                    'accent_color' => $request->input('accent_color', '#43A047'),
                    'locale' => $request->input('locale', 'en'),
                    'feature_bookings' => $request->boolean('feature_bookings', true),
                    'feature_messaging' => $request->boolean('feature_messaging', true),
                    'feature_reviews' => $request->boolean('feature_reviews', true),
                    'feature_payments' => $request->boolean('feature_payments', true),
                    'logo_path' => null,
                ];

                if ($request->hasFile('logo_path')) {
                    $customizationData['logo_path'] = TenantLogoProcessor::store($request->file('logo_path'));
                }
            }

            $provisionedTenant = $user->ensureTenant($customizationData);

            if ($provisionedTenant) {
                try {
                    TenantLifecycleLog::create([
                        'tenant_id' => $provisionedTenant->id,
                        'actor_user_id' => $user->id,
                        'action' => 'tenant.onboarding.started',
                        'reason' => 'Owner registered; municipality requirement documents submitted for review.',
                        'before_state' => [
                            'owner_email' => $user->email,
                        ],
                        'after_state' => [
                            'tenant_name' => $provisionedTenant->name,
                            'tenant_slug' => $provisionedTenant->slug,
                            'onboarding_status' => $provisionedTenant->onboarding_status,
                        ],
                    ]);
                } catch (\Throwable $e) {
                    Log::warning('tenant_lifecycle_log.failed_after_owner_registration', [
                        'tenant_id' => $provisionedTenant->id,
                        'user_id' => $user->id,
                        'message' => $e->getMessage(),
                    ]);
                }

                try {
                    app(CentralAdminNotifier::class)->notifyNewOwnerRegistered($provisionedTenant, $user);
                } catch (\Throwable) {
                    // Non-fatal: registration already completed.
                }
            }
        }

        event(new Registered($user));

        if ($isTenantSignup) {
            Auth::login($user);

            return redirect()->to('/')
                ->with('success', 'Welcome to '.$tenant->name.'! Your account has been created. Browse our accommodations below.');
        }

        if ($user->isOwner() && $provisionedTenant) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('owner.onboarding.status')
                ->with('success', 'Account created. Your documents have been submitted for municipality review.');
        }

        Auth::login($user);

        return redirect($user->getDashboardRoute())
            ->with('success', 'Welcome to Impasugong Accommodations! Your account has been created.');
    }

    public function createGuest(): View|RedirectResponse
    {
        abort_unless(PortalDetector::isKnownCentralPortal(request()) || app()->runningUnitTests(), 404);

        if (! PortalDetector::isPublicPortal(request()) && ! app()->runningUnitTests()) {
            return redirect()->away(PortalDetector::publicPortalOrigin(request()).'/register/guest');
        }

        return view('auth.register-guest');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeGuest(Request $request): RedirectResponse
    {
        abort_unless(PortalDetector::isPublicPortal($request) || app()->runningUnitTests(), 404);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => 'Provide your complete legal name.',
            'email.required' => 'An email address is required for secure account recovery and booking notices.',
            'email.email' => 'Enter a correctly formatted organizational or personal email address.',
            'email.unique' => 'This email address already has access. Choose sign-in if that profile belongs to you.',
            'password.required' => 'Choose a secure password.',
            'phone.max' => 'Shorten your phone entry to fewer than twenty characters.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_CLIENT,
            'tenant_id' => null,
            'phone' => $request->phone,
        ]);

        $user->syncRbacFromLegacyRole();

        try {
            $user->syncPermissions(User::defaultClientSpatiePermissions());
        } catch (\Throwable) {
            // Municipality guests may operate without tightly-scoped tenant teams when tenant_id is null.
        }

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('portal.guest.dashboard')
            ->with('success', 'Welcome! Explore verified stays across '.$this->portalMunicipalityLabel().'.');
    }

    public function createOwner(): View|RedirectResponse
    {
        abort_unless(PortalDetector::isKnownCentralPortal(request()) || app()->runningUnitTests(), 404);

        if (! PortalDetector::isPublicPortal(request()) && ! app()->runningUnitTests()) {
            return redirect()->away(PortalDetector::publicPortalOrigin(request()).'/register/owner');
        }

        return view('auth.register-owner');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeOwner(Request $request): RedirectResponse
    {
        abort_unless(PortalDetector::isPublicPortal($request) || app()->runningUnitTests(), 404);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'app_title' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Provide your full legal name as the authorised operator.',
            'email.required' => 'Official correspondence requires a reachable business email address.',
            'email.unique' => 'This email already exists. Recover access instead of creating a duplicate record.',
            'password.required' => 'Create an administrator-caliber password.',
            'app_title.max' => 'Shorten your business name to fewer than two hundred fifty characters.',
        ]);

        MunicipalityDocumentUploads::validate($request);
        $documentPaths = MunicipalityDocumentUploads::storeAll($request);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => User::ROLE_OWNER,
                'tenant_id' => null,
                'phone' => $request->phone,
            ]);

            $user->syncRbacFromLegacyRole();

            $customizationData = [
                'subscription_plan' => null,
                'app_title' => $request->input('app_title'),
                'primary_color' => '#2E7D32',
                'accent_color' => '#43A047',
                'locale' => 'en',
                'feature_bookings' => true,
                'feature_messaging' => true,
                'feature_reviews' => true,
                'feature_payments' => true,
                'logo_path' => null,
            ];

            $provisionedTenant = $user->ensureTenant($customizationData);

            if (! $provisionedTenant instanceof Tenant) {
                throw new \RuntimeException('owner_tenant_provision_failed');
            }

            $provisionedTenant->forceFill([
                ...$documentPaths,
                'municipality_requirements_submitted_at' => now(),
                'onboarding_status' => Tenant::ONBOARDING_PENDING_APPROVAL,
            ])->save();
        } catch (\RuntimeException $e) {
            MunicipalityDocumentUploads::deleteStoredPaths(array_values($documentPaths));

            if ($e->getMessage() === 'owner_tenant_provision_failed') {
                return redirect()->route('portal.landing')
                    ->with('error', 'Registration could not be finalized. Contact the municipality tourism coordination desk so they can reconcile your workspace.');
            }

            throw $e;
        } catch (\Throwable $e) {
            MunicipalityDocumentUploads::deleteStoredPaths(array_values($documentPaths));

            throw $e;
        }

        try {
            TenantLifecycleLog::create([
                'tenant_id' => $provisionedTenant->id,
                'actor_user_id' => $user->id,
                'action' => 'tenant.onboarding.started',
                'reason' => 'Owner submitted municipality requirement documents.',
                'before_state' => [
                    'owner_email' => $user->email,
                ],
                'after_state' => [
                    'tenant_name' => $provisionedTenant->name,
                    'onboarding_status' => $provisionedTenant->onboarding_status,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::warning('tenant_lifecycle_log.failed_after_owner_registration', [
                'tenant_id' => $provisionedTenant->id,
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);
        }

        try {
            app(CentralAdminNotifier::class)->notifyNewOwnerRegistered($provisionedTenant, $user);
        } catch (\Throwable) {
            //
        }

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('owner.onboarding.status')
            ->with('success', 'Application received. Municipality staff will review your documents.');
    }

    private function portalMunicipalityLabel(): string
    {
        return (string) config('portals.municipality_name', 'Impasug-ong');
    }
}
