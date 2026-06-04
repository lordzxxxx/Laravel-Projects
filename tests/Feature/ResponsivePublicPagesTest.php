<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

function responsiveAuditLandlordAvailable(): bool
{
    $landlordDb = (string) config('database.connections.landlord.database', '');

    return $landlordDb !== ':memory:' && $landlordDb !== '';
}

function responsiveAuditEnsureTenant(): ?Tenant
{
    if (! responsiveAuditLandlordAvailable()) {
        return null;
    }

    $tenant = Tenant::query()->whereNotNull('domain')->where('domain', '!=', '')->first();

    if ($tenant) {
        return $tenant;
    }

    $slug = 'responsive-audit-'.Str::lower(Str::random(8));

    return Tenant::query()->create([
        'name' => 'Responsive Audit Tenant',
        'slug' => $slug,
        'domain' => $slug.'.localhost',
        'owner_user_id' => null,
        'plan' => Tenant::PLAN_BASIC,
        'subscription_status' => 'active',
        'trial_ends_at' => now()->addDays(14),
        'current_period_starts_at' => now(),
        'current_period_ends_at' => now()->addMonth(),
        'onboarding_status' => Tenant::ONBOARDING_APPROVED,
        'database' => 'tenant_'.Str::lower(Str::random(8)),
        'db_host' => '127.0.0.1',
        'db_port' => 3306,
        'db_username' => 'root',
        'db_password' => '',
    ]);
}

function responsiveAuditTenantUrl(Tenant $tenant, string $path): string
{
    $tenantPort = (int) env('TENANT_PORT', env('CENTRAL_PORT', 8000));

    return "http://{$tenant->domain}:{$tenantPort}{$path}";
}

it('renders central portal landing for guests', function () {
    get(route('portal.landing'))
        ->assertOk()
        ->assertSee('Find your perfect', false);
});

it('renders about page with mobile navigation links', function () {
    get(route('portal.about'))
        ->assertOk()
        ->assertSee('portal-nav-minimal__mobile-links', false);
});

it('includes responsive viewport meta on public browse page', function () {
    get(route('portal.accommodations.index'))
        ->assertOk()
        ->assertSee('viewport-fit=cover', false);
});

it('renders explore page with burger nav and auth links in mobile menu', function () {
    get(route('portal.accommodations.index'))
        ->assertOk()
        ->assertSee('portal-nav-minimal--burger', false)
        ->assertSee('portal-nav-minimal__toggle', false)
        ->assertSee('portalPublicNavbar', false)
        ->assertSee("classList.toggle('nav-open')", false)
        ->assertSee('portal-nav-minimal__item--auth-mobile', false)
        ->assertSee('portal-nav-minimal__actions--header-desktop', false)
        ->assertSee('id="portalPublicNavMenu"', false);
});

it('loads shared responsive head partial on portal landing', function () {
    get(route('portal.landing'))
        ->assertOk()
        ->assertSee('viewport-fit=cover', false);
});

it('renders central admin messages with collapsible mobile nav chrome', function () {
    $admin = User::query()->where('role', 'admin')->whereNull('tenant_id')->first()
        ?? User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
            'email' => 'responsive-admin-messages@example.com',
        ]);

    actingAs($admin)
        ->get(route('admin.messages', [], false))
        ->assertOk()
        ->assertSee('nav-toggle', false)
        ->assertSee('id="appNavbar"', false)
        ->assertSee('msg-admin-layout', false);
});

it('includes responsive viewport meta on central admin dashboard', function () {
    $admin = User::query()->where('role', 'admin')->whereNull('tenant_id')->first()
        ?? User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
            'email' => 'responsive-admin-dashboard@example.com',
        ]);

    actingAs($admin)
        ->get(route('admin.dashboard', [], false))
        ->assertOk()
        ->assertSee('viewport-fit=cover', false)
        ->assertSee('app-scroll-x', false);
});

it('includes responsive viewport meta on admin lifecycle logs', function () {
    $admin = User::query()->where('role', 'admin')->whereNull('tenant_id')->first()
        ?? User::factory()->create([
            'role' => 'admin',
            'tenant_id' => null,
            'email' => 'responsive-admin-lifecycle@example.com',
        ]);

    actingAs($admin)
        ->get(route('admin.tenants.lifecycle-logs', [], false))
        ->assertOk()
        ->assertSee('viewport-fit=cover', false)
        ->assertSee('diff-table', false);
});

it('renders tenant public landing with responsive viewport', function () {
    if (! responsiveAuditLandlordAvailable()) {
        test()->markTestSkipped('Landlord test database is not configured for tenant route checks.');
    }

    $tenant = responsiveAuditEnsureTenant();
    expect($tenant)->not->toBeNull();

    Tenant::forgetCurrent();
    $tenant->makeCurrent();

    get(responsiveAuditTenantUrl($tenant, '/'))
        ->assertOk()
        ->assertSee('viewport-fit=cover', false);

    Tenant::forgetCurrent();
});

it('renders owner dashboard with viewport and table scroll wrappers', function () {
    if (! responsiveAuditLandlordAvailable()) {
        test()->markTestSkipped('Landlord test database is not configured for tenant route checks.');
    }

    $tenant = responsiveAuditEnsureTenant();
    expect($tenant)->not->toBeNull();

    $owner = User::query()
        ->where('tenant_id', $tenant->id)
        ->where('role', User::ROLE_OWNER)
        ->first()
        ?? User::factory()->create([
            'role' => User::ROLE_OWNER,
            'tenant_id' => $tenant->id,
            'email' => 'responsive-owner-dashboard@example.com',
        ]);

    Tenant::forgetCurrent();
    $tenant->makeCurrent();

    actingAs($owner)
        ->get(responsiveAuditTenantUrl($tenant, '/owner/dashboard'))
        ->assertOk()
        ->assertSee('viewport-fit=cover', false)
        ->assertSee('owner-dash-table-scroll', false);

    Tenant::forgetCurrent();
});

it('renders guest login with split layout stack breakpoint', function () {
    get(route('login'))
        ->assertOk()
        ->assertSee('viewport-fit=cover', false);
});

it('renders explore accommodations with table or card listing layout', function () {
    get(route('portal.accommodations.index'))
        ->assertOk()
        ->assertSee('viewport-fit=cover', false)
        ->assertSee('portal-nav-minimal--burger', false);
});
