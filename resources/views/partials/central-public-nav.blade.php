@php
    $active = $active ?? '';
    $linkBase = 'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors hover:bg-brand-soft min-h-[44px]';
    $linkDefault = 'text-brand-dark';
    $linkActive = 'border-b-2 border-brand-primary text-brand-primary bg-brand-soft/50';
@endphp
<style>
    @include('partials.navbar-tribal-shell-styles')
    @include('partials.portal-public-nav-minimal-styles')
    :root {
        --app-topbar-height: 84px;
        --app-topbar-height-mobile: 72px;
        --app-main-top-offset: 100px;
        --portal-public-nav-offset: var(--app-main-top-offset);
    }
    @media (max-width: 768px) {
        :root {
            --app-main-top-offset: 5.75rem;
            --app-topbar-height: 5.75rem;
        }
    }
    .public-nav-tribal.central-public-nav { position: relative; overflow: hidden; }
    .public-nav-tribal.central-public-nav > *:not(.navbar-tribal-accent) { position: relative; z-index: 2; }
    .central-public-nav__desktop-links { display: none; }
    @media (min-width: 768px) {
        .central-public-nav__desktop-links { display: flex; }
        .central-public-nav .portal-nav-minimal__mobile-links { display: none !important; }
    }
</style>
<nav class="portal-nav-minimal public-nav-tribal central-public-nav fixed left-0 right-0 top-0 z-[1000] flex w-full flex-col" aria-label="Site">
    @include('partials.navbar-tribal-accent')
    <div class="portal-nav-minimal__inner">
        <a href="{{ route('portal.landing') }}" class="flex min-w-0 items-center gap-3 no-underline">
            <img src="/SYSTEMLOGO.png" alt="IMPASUGONG TOURISM" class="h-11 w-auto shrink-0 rounded-lg md:h-12">
            <div class="min-w-0 leading-tight">
                <span class="block text-base font-extrabold tracking-tight text-brand-dark md:text-lg">IMPASUGONG TOURISM</span>
                <span class="block text-[0.68rem] font-medium leading-none text-brand-medium md:text-[0.75rem]">| Impasugong Accommodations</span>
            </div>
        </a>
        <ul class="central-public-nav__desktop-links list-none items-center gap-2 lg:gap-5">
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
        <div class="portal-nav-minimal__actions">
            <a href="{{ route('login') }}" class="portal-nav-minimal__action portal-nav-minimal__action--text">
                <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Login
            </a>
            <a href="{{ route('register') }}" class="portal-nav-minimal__action portal-nav-minimal__action--primary">
                <i class="fas fa-user-plus" aria-hidden="true"></i> Register
            </a>
        </div>
    </div>
    <ul class="portal-nav-minimal__mobile-links" aria-label="Sections">
        <li>
            <a href="{{ route('portal.landing') }}" class="portal-nav-minimal__link {{ in_array($active, ['home', 'landing'], true) ? 'is-active' : '' }}">
                <i class="fas fa-house" aria-hidden="true"></i> Home
            </a>
        </li>
        <li>
            <a href="{{ route('portal.accommodations.index') }}" class="portal-nav-minimal__link {{ $active === 'browse' ? 'is-active' : '' }}">
                <i class="fas fa-compass" aria-hidden="true"></i> Explore
            </a>
        </li>
        <li>
            <a href="{{ route('portal.about') }}" class="portal-nav-minimal__link {{ $active === 'about' ? 'is-active' : '' }}">
                <i class="fas fa-circle-info" aria-hidden="true"></i> About
            </a>
        </li>
    </ul>
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg border-2 border-brand-primary bg-transparent px-4 py-2 text-sm font-semibold text-brand-dark transition-colors hover:bg-brand-primary hover:text-white">
            <i class="fas fa-sign-in-alt text-sm" aria-hidden="true"></i> Login
        </a>
        @include('partials.register-choice-menu')
    </div>
</nav>
