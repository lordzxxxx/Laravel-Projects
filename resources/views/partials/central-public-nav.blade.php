@php
    $active = $active ?? '';
    $linkBase = 'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors hover:bg-brand-soft';
    $linkDefault = 'text-brand-dark';
    $linkActive = 'border-b-2 border-brand-primary text-brand-primary bg-brand-soft/50';
@endphp
<style>
    @include('partials.navbar-tribal-shell-styles')
    :root {
        --app-topbar-height: 84px;
        --app-topbar-height-mobile: 72px;
        --app-main-top-offset: 100px;
    }
    @media (max-width: 768px) {
        :root { --app-main-top-offset: 88px; }
    }
    .public-nav-tribal { position: relative; overflow: hidden; }
    .public-nav-tribal > *:not(.navbar-tribal-accent) { position: relative; z-index: 2; }
    .public-nav-tribal .navbar-tribal-accent { position: absolute; inset: 0; overflow: hidden; pointer-events: none; z-index: 0; }
    .public-nav-tribal .navbar-tribal-accent__canvas {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        transform: none;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
        opacity: var(--nav-tribal-pattern-opacity, 0.8);
    }
    .public-nav-tribal::after {
        content: '';
        position: absolute; inset: 0; z-index: 1; pointer-events: none;
        background:
            linear-gradient(180deg, rgba(255,255,255,0.72) 0%, rgba(255,255,255,0.62) 50%, rgba(255,255,255,0.70) 100%),
            rgba(15, 23, 42, 0.02);
    }
    .public-nav-tribal a,
    .public-nav-tribal span,
    .public-nav-tribal li {
        text-shadow: 0 1px 0 rgba(255,255,255,0.55);
    }
    @include('partials.public-nav-burger-styles')
</style>
<nav id="centralPublicNav" class="public-nav-tribal public-nav-burgerable fixed left-0 right-0 top-0 z-[1000] flex w-full flex-col items-stretch justify-between bg-white/95 shadow-[0_2px_12px_rgba(27,94,32,0.08)] backdrop-blur-md md:flex-row md:items-center">
    @include('partials.navbar-tribal-accent')
    <div class="public-nav-burgerable__bar">
        <a href="{{ route('portal.landing') }}" class="flex min-w-0 items-center gap-3 no-underline md:gap-3.5">
            <img src="/SYSTEMLOGO.png" alt="IMPASUGONG TOURISM" class="h-11 w-auto shrink-0 rounded-lg md:h-[48px]">
            <div class="min-w-0 leading-tight">
                <span class="block text-base font-extrabold tracking-tight text-brand-dark md:text-lg">IMPASUGONG TOURISM</span>
                <span class="block text-[0.68rem] font-medium leading-none text-brand-medium md:text-[0.75rem]">| Impasugong Accommodations</span>
            </div>
        </a>
        @include('partials.nav-burger-toggle', ['targetId' => 'centralPublicNav'])
    </div>
    <div class="public-nav-burgerable__menu">
        <ul class="hidden list-none items-center gap-2 md:flex lg:gap-5">
            <li>
                <a href="{{ route('portal.landing') }}" class="{{ $linkBase }} {{ in_array($active, ['home', 'landing'], true) ? $linkActive : $linkDefault }}">
                    <i class="fas fa-house text-sm opacity-90" aria-hidden="true"></i> Home
                </a>
            </li>
            <li>
                <a href="{{ route('portal.accommodations.index') }}" class="{{ $linkBase }} {{ $active === 'browse' ? $linkActive : $linkDefault }}">
                    <i class="fas fa-compass text-sm opacity-90" aria-hidden="true"></i> Explore
                </a>
            </li>
            <li>
                <a href="{{ route('portal.about') }}" class="{{ $linkBase }} {{ $active === 'about' ? $linkActive : $linkDefault }}">
                    <i class="fas fa-circle-info text-sm opacity-90" aria-hidden="true"></i> About Us
                </a>
            </li>
        </ul>
        <div class="public-nav-burgerable__actions flex flex-wrap items-center gap-2.5">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg border-2 border-brand-primary bg-transparent px-4 py-2 text-sm font-semibold text-brand-dark transition-colors hover:bg-brand-primary hover:text-white">
                <i class="fas fa-sign-in-alt text-sm" aria-hidden="true"></i> Login
            </a>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-br from-brand-dark to-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-[0_3px_12px_rgba(46,125,50,0.25)] transition-all hover:opacity-95 hover:shadow-[0_4px_14px_rgba(46,125,50,0.3)]">
                <i class="fas fa-user-plus text-sm" aria-hidden="true"></i> Register
            </a>
        </div>
    </div>
</nav>
