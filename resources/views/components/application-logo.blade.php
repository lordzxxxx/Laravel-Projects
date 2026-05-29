@php
    $currentTenant = \App\Models\Tenant::checkCurrent() ? \App\Models\Tenant::current() : null;
    $logoUrl = $currentTenant ? $currentTenant->brandLogoUrl() : asset('SYSTEMLOGO.png');
    $logoFallback = $currentTenant ? \App\Models\Tenant::defaultBrandLogoUrl() : asset('SYSTEMLOGO.png');
@endphp

<img src="{{ $logoUrl }}" alt="{{ $currentTenant?->getAppTitle() ?? config('app.name', 'ImpaStay') }}" {{ $attributes->merge(['class' => 'object-contain']) }} onerror="this.onerror=null;this.src='{{ $logoFallback }}';">
