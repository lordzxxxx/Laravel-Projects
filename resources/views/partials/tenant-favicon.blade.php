@include('partials.navigation-stability-meta')
@php
    $tenantFaviconTenant = $tenant ?? \App\Models\Tenant::current();
    $tenantFaviconHref = $tenantFaviconTenant?->brandLogoUrl();
@endphp
@if ($tenantFaviconHref)
    @include('partials.favicon-links', ['href' => $tenantFaviconHref])
@else
    @include('partials.favicon-links', ['faviconStem' => 'love'])
@endif
