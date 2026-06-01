@php
    use App\Models\Tenant;

    $stripClass = trim('partner-logos-strip '.($class ?? ''));
    $tenant = $tenant ?? null;

    $centerLogo = asset('SYSTEMLOGO.png');
    $centerFallback = asset('SYSTEMLOGO.png');
    $centerAlt = 'IMPASUGONG TOURISM';

    if ($tenant?->getLogoUrl()) {
        $centerLogo = $tenant->getLogoUrl();
        $centerFallback = Tenant::defaultBrandLogoUrl();
        $centerAlt = $tenant->name;
    }
@endphp
@include('partials.partner-logos-strip-styles')
<div class="{{ $stripClass }}" role="group" aria-label="Partner logos">
    <img src="{{ asset('images/love-impasugong-transparent.png') }}" alt="Love Impasugong" decoding="async">
    <img
        src="{{ $centerLogo }}"
        alt="{{ $centerAlt }}"
        decoding="async"
        @class(['tenant-brand-logo' => (bool) $tenant?->getLogoUrl()])
        onerror="this.onerror=null;this.src='{{ $centerFallback }}';"
    >
    <img src="{{ asset('Lgu Socmed Template-02 2.png') }}" alt="LGU Impasugong" decoding="async">
</div>
