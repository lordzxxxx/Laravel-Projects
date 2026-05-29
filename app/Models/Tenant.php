<?php

namespace App\Models;

use App\Observers\TenantObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;

#[ObservedBy([TenantObserver::class])]
class Tenant extends BaseTenant
{
    /**
     * Tenant configuration rows live on the landlord database (never on per-tenant DBs).
     */
    protected $connection = 'landlord';

    public const PLAN_BASIC = 'basic';

    public const PLAN_PLUS = 'plus';

    public const PLAN_PRO = 'pro';

    /** Promotional: Premium feature set; optional `promo_max_listings` (null = unlimited) and `promo_price` (null = ₱0 in catalog). */
    public const PLAN_PROMO = 'promo';

    public const ONBOARDING_AWAITING_PAYMENT = 'awaiting_payment';

    public const ONBOARDING_PENDING_APPROVAL = 'pending_approval';

    public const ONBOARDING_APPROVED = 'approved';

    public const ONBOARDING_REJECTED = 'rejected';

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'domain_enabled',
        'domain_disabled_at',
        'app_port',
        'database',
        'db_host',
        'db_port',
        'db_username',
        'db_password',
        'owner_user_id',
        'plan',
        'promo_max_listings',
        'promo_price',
        'subscription_status',
        'trial_ends_at',
        'current_period_starts_at',
        'current_period_ends_at',
        'metadata',
        'app_title',
        'primary_color',
        'accent_color',
        'logo_path',
        'gcash_qr_path',
        'locale',
        'feature_bookings',
        'feature_messaging',
        'feature_reviews',
        'feature_payments',
        'database_provisioned',
        'database_provisioned_at',
        'provisioning_error',
        'onboarding_status',
        'payment_reference',
        'onboarding_payment_channel',
        'municipality_business_permit_path',
        'municipality_mayors_permit_path',
        'municipality_barangay_clearance_path',
        'municipality_valid_id_path',
        'municipality_requirements_submitted_at',
        'municipality_admin_review_notes',
        'municipality_compliance_verified_at',
        'municipality_compliance_notes',
        'onboarding_gcash_proof_path',
        'onboarding_gcash_submitted_at',
        'onboarding_stripe_session_id',
        'payment_submitted_at',
        'onboarding_approved_at',
        'onboarding_approved_by',
        'bandwidth_usage_bytes',
        'bandwidth_quota_bytes',
        'bandwidth_last_recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'current_period_starts_at' => 'datetime',
            'current_period_ends_at' => 'datetime',
            'domain_enabled' => 'boolean',
            'domain_disabled_at' => 'datetime',
            'app_port' => 'integer',
            'db_port' => 'integer',
            'db_username' => 'encrypted',
            'db_password' => 'encrypted',
            'onboarding_stripe_session_id' => 'encrypted',
            'database_provisioned' => 'boolean',
            'database_provisioned_at' => 'datetime',
            'payment_submitted_at' => 'datetime',
            'onboarding_gcash_submitted_at' => 'datetime',
            'onboarding_approved_at' => 'datetime',
            'municipality_requirements_submitted_at' => 'datetime',
            'municipality_compliance_verified_at' => 'datetime',
            'metadata' => 'array',
            'feature_bookings' => 'boolean',
            'feature_messaging' => 'boolean',
            'feature_reviews' => 'boolean',
            'feature_payments' => 'boolean',
            'bandwidth_usage_bytes' => 'integer',
            'bandwidth_quota_bytes' => 'integer',
            'bandwidth_last_recorded_at' => 'datetime',
            'promo_max_listings' => 'integer',
            'promo_price' => 'decimal:2',
        ];
    }

    public function getPaymentReferenceAttribute($value): ?string
    {
        if (! is_string($value) || $value === '') {
            return $value;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return $value;
        }
    }

    public function setPaymentReferenceAttribute($value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['payment_reference'] = null;

            return;
        }

        $this->attributes['payment_reference'] = (string) $value;
    }

    /**
     * Share of quota used (0–100), or null when no quota is set.
     */
    public function bandwidthUsagePercent(): ?float
    {
        $quota = (int) ($this->bandwidth_quota_bytes ?? 0);
        if ($quota < 1) {
            return null;
        }

        $used = (int) ($this->bandwidth_usage_bytes ?? 0);

        return round(min(100, ($used / $quota) * 100), 1);
    }

    public function publicUrl(): string
    {
        $host = env('TENANCY_BASE_HOST', parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost');
        $port = ':'.env('CENTRAL_PORT', 8000);

        if ($this->domain) {
            return 'http://'.$this->domain.$port;
        }

        return 'http://'.$host.$port;
    }

    /**
     * True when the HTTP host is the central landlord app (owner signup / onboarding).
     * Must align with PortTenantFinder and routes/web.php central hosts so Spatie's global
     * tenant resolution never mis-classifies central registration as a tenant-subdomain signup.
     */
    public static function isRequestHostForCentralLandlordApp(Request $request): bool
    {
        $host = $request->getHost();
        $centralDomain = (string) env(
            'CENTRAL_DOMAIN',
            parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost'
        );

        foreach (['localhost', '127.0.0.1', '::1', $centralDomain] as $allowed) {
            if ($allowed !== '' && strcasecmp($host, $allowed) === 0) {
                return true;
            }
        }

        return false;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function onboardingApprovedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'onboarding_approved_by');
    }

    public function isRegistrationFullyApproved(): bool
    {
        return (string) ($this->onboarding_status ?? self::ONBOARDING_APPROVED) === self::ONBOARDING_APPROVED
            && (bool) $this->database_provisioned;
    }

    public function mockSubscriptionAmount(): float
    {
        $details = self::getPlanDetails();
        $plan = (string) ($this->plan ?? self::PLAN_BASIC);
        $basicPrice = (float) ($details[self::PLAN_BASIC]['price'] ?? 299);

        if ($plan === self::PLAN_PROMO && $this->promo_price !== null) {
            $amount = (float) $this->promo_price;
        } else {
            $amount = (float) ($details[$plan]['price'] ?? $basicPrice);
        }

        // Onboarding Stripe requires at least ₱1; promo catalog defaults to ₱0 without admin-set promo_price.
        return $amount >= 1.0 ? $amount : $basicPrice;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function tenantUsers(): HasMany
    {
        return $this->hasMany(TenantUser::class);
    }

    public function clientUsers(): HasMany
    {
        return $this->hasMany(ClientUser::class);
    }

    public function accommodations(): HasMany
    {
        return $this->hasMany(Accommodation::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function updateTickets(): HasMany
    {
        return $this->hasMany(UpdateTicket::class, 'tenant_id');
    }

    public function tenantUpdates(): HasMany
    {
        return $this->hasMany(TenantUpdate::class, 'tenant_id');
    }

    public function hasActiveSubscription(): bool
    {
        return (string) ($this->subscription_status ?? '') === 'active';
    }

    public function maxListings(): ?int
    {
        if ($this->isRegistrationFullyApproved()) {
            return null;
        }

        // Active subscribers may create as many accommodations as needed; plan tiers gate features elsewhere.
        if ($this->hasActiveSubscription()) {
            return null;
        }

        return match ($this->normalizedPlan()) {
            self::PLAN_BASIC => 3,
            self::PLAN_PLUS => 10,
            self::PLAN_PRO => null,
            self::PLAN_PROMO => $this->promo_max_listings,
            default => 3,
        };
    }

    /**
     * Check if tenant has access to a specific feature based on subscription plan
     */
    public function hasFeature(string $feature): bool
    {
        if ($this->isRegistrationFullyApproved()) {
            return true;
        }

        if (! $this->hasActiveSubscription()) {
            return false;
        }

        return match ($this->normalizedPlan()) {
            self::PLAN_BASIC => in_array($feature, [
                'bookings',
                'basic_reporting',
            ]),
            self::PLAN_PLUS => in_array($feature, [
                'bookings',
                'messaging',
                'advanced_reporting',
                'analytics_dashboard',
            ]),
            self::PLAN_PRO, self::PLAN_PROMO => in_array($feature, [
                'bookings',
                'messaging',
                'reviews',
                'advanced_reporting',
                'analytics_dashboard',
                'priority_support',
                'featured_listings',
            ]),
            default => false,
        };
    }

    /**
     * Get an array of all available features for this plan
     */
    public function getAvailableFeatures(): array
    {
        return match ($this->normalizedPlan()) {
            self::PLAN_BASIC => [
                'bookings' => true,
                'messaging' => false,
                'reviews' => false,
                'basic_reporting' => true,
                'advanced_reporting' => false,
                'analytics_dashboard' => false,
                'priority_support' => false,
                'featured_listings' => false,
            ],
            self::PLAN_PLUS => [
                'bookings' => true,
                'messaging' => true,
                'reviews' => false,
                'basic_reporting' => true,
                'advanced_reporting' => true,
                'analytics_dashboard' => true,
                'priority_support' => false,
                'featured_listings' => false,
            ],
            self::PLAN_PRO, self::PLAN_PROMO => [
                'bookings' => true,
                'messaging' => true,
                'reviews' => true,
                'basic_reporting' => true,
                'advanced_reporting' => true,
                'analytics_dashboard' => true,
                'priority_support' => true,
                'featured_listings' => true,
            ],
            default => [],
        };
    }

    private function normalizedPlan(): string
    {
        $plan = (string) $this->plan;

        if (str_starts_with($plan, 'custom:')) {
            // Custom plans default to premium capabilities unless explicitly modeled later.
            return self::PLAN_PRO;
        }

        if ($plan === self::PLAN_PROMO) {
            return self::PLAN_PROMO;
        }

        return $plan;
    }

    /**
     * Get plan details with features and pricing
     */
    public static function getPlanDetails(): array
    {
        return [
            self::PLAN_BASIC => [
                'name' => 'Basic Plan',
                'price' => 299,
                'currency' => '₱',
                'max_listings' => 3,
                'features' => [
                    '3 property listings',
                    'Basic reporting',
                    'Booking management',
                ],
            ],
            self::PLAN_PLUS => [
                'name' => 'Standard Plan',
                'price' => 499,
                'currency' => '₱',
                'max_listings' => 10,
                'features' => [
                    'Up to 10 listings',
                    'Advanced reporting',
                    'Analytics dashboard',
                ],
            ],
            self::PLAN_PRO => [
                'name' => 'Premium Plan',
                'price' => 799,
                'currency' => '₱',
                'max_listings' => null, // unlimited
                'features' => [
                    'Unlimited listings',
                    'Priority support',
                    'Featured listing promotion',
                    'Advanced analytics',
                ],
            ],
            self::PLAN_PROMO => [
                'name' => 'Promo (custom)',
                'price' => 0,
                'currency' => '₱',
                'max_listings' => null,
                'features' => [
                    'Promotional / partner pricing',
                    'Premium feature set',
                    'Custom listing cap and monthly price (admin)',
                ],
            ],
        ];
    }

    /**
     * Human-readable plan label for admin UI and emails.
     */
    public static function planLabel(string $plan): string
    {
        return match ($plan) {
            self::PLAN_BASIC => 'Basic',
            self::PLAN_PLUS => 'Standard',
            self::PLAN_PRO => 'Premium',
            self::PLAN_PROMO => 'Promo (custom)',
            default => ucfirst($plan),
        };
    }

    /**
     * Registration + billing labels for owner UI (replaces plan / listing-cap messaging).
     *
     * @return array{registration: string, billing: string, tone: string}
     */
    public function businessStatusParts(): array
    {
        $onboarding = (string) ($this->onboarding_status ?? self::ONBOARDING_APPROVED);
        $registration = match ($onboarding) {
            self::ONBOARDING_AWAITING_PAYMENT => 'Awaiting payment',
            self::ONBOARDING_PENDING_APPROVAL => 'Pending approval',
            self::ONBOARDING_APPROVED => 'Approved',
            self::ONBOARDING_REJECTED => 'Not approved',
            default => ucfirst(str_replace('_', ' ', $onboarding)),
        };

        $subscription = (string) ($this->subscription_status ?? '');
        $billing = match ($subscription) {
            'trialing' => 'Active',
            'active' => 'Active',
            'past_due' => 'Past due',
            'canceled', 'cancelled' => 'Canceled',
            'inactive' => 'Inactive',
            default => $subscription !== ''
                ? ucfirst(str_replace('_', ' ', $subscription))
                : 'Unknown',
        };

        $tone = 'neutral';
        if ($onboarding === self::ONBOARDING_REJECTED) {
            $tone = 'danger';
        } elseif (in_array($onboarding, [self::ONBOARDING_PENDING_APPROVAL, self::ONBOARDING_AWAITING_PAYMENT], true)) {
            $tone = 'warning';
        } elseif (in_array($subscription, ['past_due', 'canceled', 'cancelled', 'inactive'], true)) {
            $tone = 'warning';
        } elseif ($onboarding === self::ONBOARDING_APPROVED && $subscription === 'active') {
            $tone = 'success';
        }

        return [
            'registration' => $registration,
            'billing' => $billing,
            'tone' => $tone,
        ];
    }

    public function canCreateAccommodation(int $currentCount): bool
    {
        if (! $this->hasActiveSubscription()) {
            return false;
        }

        if (! $this->hasFeature('bookings')) {
            return false;
        }

        $maxListings = $this->maxListings();

        return is_null($maxListings) || $currentCount < $maxListings;
    }

    public function landingSettings(): array
    {
        $metadata = is_array($this->metadata) ? $this->metadata : [];
        $landing = is_array($metadata['landing'] ?? null) ? $metadata['landing'] : [];
        $defaults = $this->defaultLandingSettings();
        $merged = array_merge($defaults, $landing);

        $fromLandingPrimary = isset($landing['primary_color']) ? trim((string) $landing['primary_color']) : '';
        $fromColumnPrimary = trim((string) ($this->primary_color ?? ''));
        $merged['primary_color'] = $fromLandingPrimary !== ''
            ? $fromLandingPrimary
            : ($fromColumnPrimary !== '' ? $fromColumnPrimary : $defaults['primary_color']);

        $fromLandingAccent = isset($landing['accent_color']) ? trim((string) $landing['accent_color']) : '';
        $fromColumnAccent = trim((string) ($this->accent_color ?? ''));
        $merged['accent_color'] = $fromLandingAccent !== ''
            ? $fromLandingAccent
            : ($fromColumnAccent !== '' ? $fromColumnAccent : $defaults['accent_color']);

        return $merged;
    }

    public function updateLandingSettings(array $settings): void
    {
        $metadata = is_array($this->metadata) ? $this->metadata : [];
        $metadata['landing'] = array_merge($this->defaultLandingSettings(), $settings);

        $this->update(['metadata' => $metadata]);
    }

    private function defaultLandingSettings(): array
    {
        $ownerName = $this->owner?->name ?? 'Owner';

        return [
            'hero_title' => $this->name.' Stays',
            'hero_subtitle' => 'Book trusted accommodations managed by '.$ownerName.'.',
            'cta_text' => 'Browse Accommodations',
            'cta_url' => '/dashboard',
            'login_section_title' => 'Access Your Account',
            'login_section_subtitle' => 'Use login if you already have an account, or sign up as a new user.',
            'login_text' => 'Login',
            'signup_text' => 'Sign Up',
            'about_title' => 'About Our Property Network',
            'about_text' => 'We offer comfortable stays and responsive support for travelers who want a smooth booking experience.',
            'primary_color' => '#00AA77',
            'accent_color' => '#0B1FD9',
            'hero_image_url' => asset('SYSTEMLOGO.png'),
        ];
    }

    /**
     * Get the app title/display name for the tenant
     * Falls back to tenant name if app_title is not set
     */
    public function getAppTitle(): string
    {
        return $this->app_title ?: $this->name;
    }

    /**
     * Get the primary theme color
     */
    public function getPrimaryColor(): string
    {
        return $this->primary_color ?? '#2E7D32';
    }

    /**
     * Get the accent theme color
     */
    public function getAccentColor(): string
    {
        return $this->accent_color ?? '#43A047';
    }

    /**
     * Default brand mark when no custom logo is uploaded.
     */
    public static function defaultBrandLogoUrl(): string
    {
        return asset('images/love-impasugong-watermark.png');
    }

    /**
     * Uploaded logo URL, or null if none.
     */
    public function getLogoUrl(): ?string
    {
        return $this->logo_path ? asset('storage/'.$this->logo_path) : null;
    }

    /**
     * Logo URL for tenant-branded surfaces (uploaded logo or Love Impasugong default).
     */
    public function brandLogoUrl(): string
    {
        return $this->getLogoUrl() ?? self::defaultBrandLogoUrl();
    }

    public function getGcashQrUrl(): ?string
    {
        return $this->gcash_qr_path ? asset('storage/'.$this->gcash_qr_path) : null;
    }

    public function getOnboardingGcashProofUrlAttribute(): ?string
    {
        return $this->onboarding_gcash_proof_path
            ? route('secure-media.onboarding-proof', ['tenant' => $this], false)
            : null;
    }

    /**
     * Check if a specific feature is enabled
     */
    public function isFeatureEnabled(string $feature): bool
    {
        $featureKey = 'feature_'.$feature;
        if (! property_exists($this, $featureKey)) {
            return false;
        }

        return (bool) $this->{$featureKey};
    }

    /**
     * Get all enabled features
     */
    public function getEnabledFeatures(): array
    {
        return [
            'bookings' => $this->feature_bookings ?? true,
            'messaging' => $this->feature_messaging ?? true,
            'reviews' => $this->feature_reviews ?? true,
            'payments' => $this->feature_payments ?? true,
        ];
    }

    /**
     * Get the tenant's preferred locale
     */
    public function getLocale(): string
    {
        return $this->locale ?? 'en';
    }
}
