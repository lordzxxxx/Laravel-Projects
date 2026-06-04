<?php

use App\Models\User;
use Illuminate\Database\QueryException;

it('includes viewport meta on admin dashboard for responsive layouts', function () {
    try {
        User::query()->count();
    } catch (QueryException $exception) {
        $this->markTestSkipped('Landlord test database is not available in this environment.');
    }

    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
        'tenant_id' => null,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', [], false));

    $response->assertOk();
    $response->assertSee('width=device-width', false);
    $response->assertSee('viewport-fit=cover', false);
});

it('uses responsive table wrapper on admin landing plans page', function () {
    try {
        User::query()->count();
    } catch (QueryException $exception) {
        $this->markTestSkipped('Landlord test database is not available in this environment.');
    }

    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
        'tenant_id' => null,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.update-tickets.index', [], false));

    $response->assertOk();
    $response->assertSee('app-table-responsive', false);
    $response->assertSee('app-data-table', false);
});

it('uses tiered explore stay card styles on portal accommodations index', function () {
    $response = $this->get(route('portal.accommodations.index', [], false));

    $response->assertOk();
    $response->assertSee('explore-stay-card', false);
    $response->assertSee('--app-card-media-ratio', false);
    $response->assertSee('var(--app-card-title', false);
});

it('includes owner mobile nav bar alignment on tenant owner dashboard', function () {
    try {
        User::query()->count();
    } catch (QueryException $exception) {
        $this->markTestSkipped('Landlord test database is not available in this environment.');
    }

    $owner = User::factory()->create([
        'role' => User::ROLE_OWNER,
    ]);

    $tenant = \App\Models\Tenant::query()->create([
        'name' => 'Owner Nav Test Tenant',
        'slug' => 'owner-nav-'.strtolower(\Illuminate\Support\Str::random(8)),
        'domain' => 'owner-nav-test.localhost',
        'owner_user_id' => null,
        'plan' => \App\Models\Tenant::PLAN_BASIC,
        'subscription_status' => 'active',
        'trial_ends_at' => now()->addDays(14),
        'current_period_starts_at' => now(),
        'current_period_ends_at' => now()->addMonth(),
        'onboarding_status' => \App\Models\Tenant::ONBOARDING_APPROVED,
        'database' => 'tenant_owner_nav',
        'db_host' => '127.0.0.1',
        'db_port' => 3306,
        'db_username' => 'root',
        'db_password' => '',
    ]);
    $owner->update(['tenant_id' => $tenant->id]);

    $accommodation = \App\Models\Accommodation::create([
        'owner_id' => $owner->id,
        'tenant_id' => $tenant->id,
        'name' => 'Dashboard Edit Link Unit',
        'type' => 'airbnb',
        'description' => 'Test unit for dashboard edit link',
        'address' => 'Poblacion, Impasugong',
        'barangay' => 'Poblacion',
        'price_per_night' => 1000,
        'bedrooms' => 1,
        'bathrooms' => 1,
        'max_guests' => 2,
        'is_available' => true,
        'is_verified' => true,
    ]);

    $response = $this->actingAs($owner)->get('/owner/dashboard');

    $response->assertOk();
    $response->assertSee('/owner/accommodations/'.$accommodation->id.'/edit', false);
    $response->assertSee('owner-nav-page', false);
    $response->assertSee('body.owner-nav-page .navbar.portal-nav-minimal.public-nav-tribal:not(.nav-open)', false);
    $response->assertSee('padding-inline: clamp(1rem, 4vw, 1.25rem)', false);
    $response->assertSee('class="owner-dash-kpis"', false);
    $response->assertSee('avail-cal--compact', false);
    $response->assertSee('availability-day available', false);
    $response->assertSee('> Bookings</h2>', false);
    $response->assertDontSee('Revenue &amp; bookings', false);
    $response->assertDontSee('Revenue (PHP)', false);
    $response->assertDontSee('</style>html.dark body.owner-nav-page', false);
});

it('renders owner accommodation edit with full-width form layout', function () {
    try {
        User::query()->count();
    } catch (QueryException $exception) {
        $this->markTestSkipped('Landlord test database is not available in this environment.');
    }

    $owner = User::factory()->create([
        'role' => User::ROLE_OWNER,
    ]);

    $tenant = \App\Models\Tenant::query()->create([
        'name' => 'Owner Edit Layout Tenant',
        'slug' => 'owner-edit-'.strtolower(\Illuminate\Support\Str::random(8)),
        'domain' => 'owner-edit-test.localhost',
        'owner_user_id' => null,
        'plan' => \App\Models\Tenant::PLAN_BASIC,
        'subscription_status' => 'active',
        'trial_ends_at' => now()->addDays(14),
        'current_period_starts_at' => now(),
        'current_period_ends_at' => now()->addMonth(),
        'onboarding_status' => \App\Models\Tenant::ONBOARDING_APPROVED,
        'database' => 'tenant_owner_edit',
        'db_host' => '127.0.0.1',
        'db_port' => 3306,
        'db_username' => 'root',
        'db_password' => '',
    ]);
    $owner->update(['tenant_id' => $tenant->id]);

    $accommodation = \App\Models\Accommodation::create([
        'owner_id' => $owner->id,
        'tenant_id' => $tenant->id,
        'name' => 'Edit Layout Test Unit',
        'type' => 'airbnb',
        'description' => 'Test unit for edit layout',
        'address' => 'Poblacion, Impasugong',
        'barangay' => 'Poblacion',
        'price_per_night' => 1000,
        'bedrooms' => 1,
        'bathrooms' => 1,
        'max_guests' => 2,
        'is_available' => true,
        'is_verified' => true,
    ]);

    $response = $this->actingAs($owner)->get('/owner/accommodations/'.$accommodation->id.'/edit');

    $response->assertOk();
    $response->assertSee('owner-accommodation-edit', false);
    $response->assertSee('owner-edit-form__grid', false);
    $response->assertSee('owner-edit-section', false);
    $response->assertSee('Listing preview', false);
    $response->assertSee('Edit Layout Test Unit', false);
    $response->assertDontSee('max-width: min(56rem', false);
});

it('renders owner messages inbox with full-width owner shell layout', function () {
    try {
        User::query()->count();
    } catch (QueryException $exception) {
        $this->markTestSkipped('Landlord test database is not available in this environment.');
    }

    $owner = User::factory()->create([
        'role' => User::ROLE_OWNER,
    ]);

    $tenant = \App\Models\Tenant::query()->create([
        'name' => 'Messages Layout Tenant',
        'slug' => 'msg-layout-'.strtolower(\Illuminate\Support\Str::random(8)),
        'domain' => 'msg-layout-test.localhost',
        'owner_user_id' => null,
        'plan' => \App\Models\Tenant::PLAN_BASIC,
        'subscription_status' => 'active',
        'trial_ends_at' => now()->addDays(14),
        'current_period_starts_at' => now(),
        'current_period_ends_at' => now()->addMonth(),
        'onboarding_status' => \App\Models\Tenant::ONBOARDING_APPROVED,
        'database' => 'tenant_msg_layout',
        'db_host' => '127.0.0.1',
        'db_port' => 3306,
        'db_username' => 'root',
        'db_password' => '',
    ]);
    $owner->update(['tenant_id' => $tenant->id]);

    $response = $this->actingAs($owner)->get('/messages');

    $response->assertOk();
    $response->assertSee('owner-messages-main', false);
    $response->assertSee('owner-messages-workspace', false);
    $response->assertSee('owner-page-hero__title', false);
});
