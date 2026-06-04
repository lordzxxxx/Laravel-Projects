@php
    $municipalityName = $municipalityName ?? config('portals.municipality_name', 'Impasug-ong');
@endphp
<nav class="fixed left-0 right-0 top-0 z-[1000] flex w-full flex-wrap items-center justify-between gap-4 bg-white/95 px-5 py-3.5 shadow-[0_2px_12px_rgba(27,94,32,0.08)] backdrop-blur-md md:px-8 lg:px-10" aria-label="Administration">
    <a href="{{ route('portal.landing') }}" class="flex min-w-0 max-w-[min(100%,28rem)] items-center gap-3 no-underline rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 md:gap-4">
        <img src="{{ asset('SYSTEMLOGO.png') }}" alt="" class="h-12 w-auto shrink-0 rounded-xl object-contain md:h-14" width="56" height="56" role="presentation">
        <div class="min-w-0 leading-tight">
            <span class="block text-base font-extrabold tracking-tight text-brand-dark md:text-lg">IMPASUGONG TOURISM</span>
            <span class="block text-[0.7rem] font-semibold uppercase tracking-wide text-brand-medium md:text-xs">Municipality administration — {{ $municipalityName }}</span>
        </div>
    </a>
    <div class="ml-auto flex items-center gap-3">
        <a href="{{ route('login') }}" class="inline-flex min-h-[2.75rem] items-center gap-2 rounded-xl border-2 border-brand-primary bg-gradient-to-br from-brand-dark to-brand-primary px-5 py-2.5 text-sm font-bold text-white shadow-md transition hover:opacity-95 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-dark">
            <i class="fas fa-shield-halved text-sm" aria-hidden="true"></i>
            Admin login
        </a>
    </div>
</nav>
