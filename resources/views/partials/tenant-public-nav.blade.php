{{-- Tenant subdomain landing — same minimal nav shell as central portal (style only). --}}
@php
    $active = $active ?? 'landing';
    $tenant = $tenant ?? \App\Models\Tenant::current();
    $settings = $settings ?? ($tenant ? $tenant->landingSettings() : []);
    $brandSubtitle = $brandSubtitle ?? '| '.($settings['hero_subtitle'] ?? 'Accommodations');
    $currentUser = auth()->user();
    $canUseTenantPortal = false;

    if ($tenant && $currentUser) {
        if ($currentUser->isOwner()) {
            $canUseTenantPortal = (int) ($currentUser->tenant_id ?? 0) === (int) $tenant->id
                || (int) optional($currentUser->ownedTenant)->id === (int) $tenant->id;
            } elseif ($currentUser->isAdmin()) {
                $canUseTenantPortal = (int) ($currentUser->tenant_id ?? 0) === (int) $tenant->id;
            } elseif ($currentUser->isClient()) {
                // Municipality-wide guests (tenant_id null) may browse any tenant domain.
                $canUseTenantPortal = $currentUser->tenant_id === null
                    || (int) ($currentUser->tenant_id ?? 0) === (int) $tenant->id;
            }
        }

    $loginLabel = $settings['login_text'] ?? 'Login';
    $signupLabel = $settings['signup_text'] ?? 'Sign up';
@endphp
<style>
    @include('partials.navbar-tribal-shell-styles')
    @include('partials.portal-public-nav-minimal-styles')
    .portal-nav-minimal__actions form {
        display: inline-flex;
        margin: 0;
    }
    .portal-nav-minimal__actions button.portal-nav-minimal__action {
        cursor: pointer;
        font-family: inherit;
    }
    .portal-nav-minimal__action--muted {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: rgba(15, 23, 42, 0.65);
        cursor: default;
        pointer-events: none;
    }
</style>
<nav class="portal-nav-minimal public-nav-tribal fixed left-0 right-0 top-0 z-[1000] flex w-full flex-col" aria-label="Site">
    @include('partials.navbar-tribal-accent')
    <div class="portal-nav-minimal__inner">
        @include('partials.navbar-brand-block', [
            'brandHref' => url('/'),
            'brandSubtitle' => $brandSubtitle,
        ])
        <ul class="portal-nav-minimal__links">
            <li>
                <a
                    href="#properties"
                    class="portal-nav-minimal__link {{ $active === 'properties' ? 'is-active' : '' }}"
                ><i class="fas fa-building" aria-hidden="true"></i> Properties</a>
            </li>
        </ul>
        <div class="portal-nav-minimal__actions">
            @if($canUseTenantPortal)
                @if($currentUser->isClient())
                    <a href="{{ route('dashboard') }}" class="portal-nav-minimal__action portal-nav-minimal__action--outline"><i class="fas fa-search" aria-hidden="true"></i> Browse</a>
                @endif
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="portal-nav-minimal__action portal-nav-minimal__action--primary"><i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout</button>
                </form>
            @elseif(auth()->check())
                <span class="portal-nav-minimal__action portal-nav-minimal__action--muted"><i class="fas fa-triangle-exclamation" aria-hidden="true"></i> Wrong account</span>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="portal-nav-minimal__action portal-nav-minimal__action--primary"><i class="fas fa-right-left" aria-hidden="true"></i> Switch account</button>
                </form>
            @else
                <a href="/login" class="portal-nav-minimal__action portal-nav-minimal__action--text"><i class="fas fa-sign-in-alt" aria-hidden="true"></i> {{ $loginLabel }}</a>
                <a href="/register" class="portal-nav-minimal__action portal-nav-minimal__action--primary"><i class="fas fa-user-plus" aria-hidden="true"></i> {{ $signupLabel }}</a>
            @endif
        </div>
    </div>
    <ul class="portal-nav-minimal__mobile-links" aria-label="Sections">
        <li>
            <a
                href="#properties"
                class="portal-nav-minimal__link {{ $active === 'properties' ? 'is-active' : '' }}"
            ><i class="fas fa-building" aria-hidden="true"></i> Properties</a>
        </li>
    </ul>
</nav>
