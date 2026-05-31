{{--
    Shared brand block (portal explore/about + admin/owner/client navbars).
    $brandHref — logo link target
    $brandSubtitle — accent line including leading "| " (e.g. "| Admin Dashboard")
    On tenant subdomains: uploaded logo or Love Impasugong default.
    On central hosts: SYSTEMLOGO + IMPASUGONG TOURISM.
--}}
@php
    $brandHref = $brandHref ?? url('/');
    $brandSubtitle = $brandSubtitle ?? '| Impasug-ong stays';
    $tenant = \App\Models\Tenant::current();
    $useTenantBrand = $tenant !== null;
    $brandLogoUrl = $useTenantBrand ? $tenant->brandLogoUrl() : asset('SYSTEMLOGO.png');
    $brandLogoFallback = $useTenantBrand ? \App\Models\Tenant::defaultBrandLogoUrl() : asset('SYSTEMLOGO.png');
    $brandAlt = $useTenantBrand ? $tenant->getAppTitle() : 'IMPASUGONG TOURISM';
    $brandTitle = $brandTitle ?? ($useTenantBrand ? $tenant->getAppTitle() : 'IMPASUGONG TOURISM');
@endphp
<a href="{{ $brandHref }}" class="nav-logo">
    <img src="{{ $brandLogoUrl }}" alt="{{ $brandAlt }}" onerror="this.onerror=null;this.src='{{ $brandLogoFallback }}';">
    <div class="nav-brand-text">
        <span class="nav-brand-title">{{ $brandTitle }}</span>
        <span class="nav-brand-subtitle">{{ $brandSubtitle }}</span>
    </div>
</a>
