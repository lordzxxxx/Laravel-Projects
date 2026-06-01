@php
    $tenant = $tenant ?? \App\Models\Tenant::current();
    $centerLogo = asset('SYSTEMLOGO.png');
    $centerFallback = asset('SYSTEMLOGO.png');
    $centerAlt = 'IMPASUGONG TOURISM';

    if ($tenant?->getLogoUrl()) {
        $centerLogo = $tenant->getLogoUrl();
        $centerFallback = \App\Models\Tenant::defaultBrandLogoUrl();
        $centerAlt = $tenant->name;
    }
@endphp
{{-- Matches http://localhost:8000/login (auth/login-admin hero logos) --}}
<div class="flex flex-wrap items-center gap-x-2 gap-y-3 sm:gap-x-3" role="group" aria-label="Partner logos">
    <img
        src="{{ asset('images/love-impasugong-transparent.png') }}"
        alt=""
        class="h-24 w-auto object-contain sm:h-28 lg:h-36"
        decoding="async"
        role="presentation"
    >
    <img
        src="{{ $centerLogo }}"
        alt="{{ $centerAlt }}"
        @class([
            'h-24 w-auto object-contain sm:h-28 lg:h-36',
            'tenant-brand-logo' => (bool) $tenant?->getLogoUrl(),
        ])
        decoding="async"
        onerror="this.onerror=null;this.src='{{ $centerFallback }}';"
    >
    <img
        src="{{ asset('Lgu Socmed Template-02 2.png') }}"
        alt=""
        class="h-20 w-auto object-contain sm:h-24 lg:h-32"
        decoding="async"
        role="presentation"
    >
</div>
