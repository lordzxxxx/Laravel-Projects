@php
    use App\Support\PortalDetector;

    $active = $active ?? '';
    $navLayout = $navLayout ?? 'default';
    $navVariant = $navVariant ?? 'public';
    $publicOrigin = $navVariant === 'admin' ? PortalDetector::publicPortalOrigin(request()) : null;
    $homeUrl = $publicOrigin ? rtrim($publicOrigin, '/').'/' : route('portal.landing');
    $exploreUrl = $publicOrigin ? $publicOrigin.'/explore/accommodations' : route('portal.accommodations.index');
    $aboutUrl = $publicOrigin ? $publicOrigin.'/about' : route('portal.about');
    $registerOwnerUrl = $publicOrigin ? $publicOrigin.'/register/owner' : route('register.owner');
    $registerGuestUrl = $publicOrigin ? $publicOrigin.'/register/guest' : route('register.guest');
    $linkBase = 'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors hover:bg-brand-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2';
    $linkDefault = 'text-brand-dark';
    $linkActive = 'border-b-2 border-brand-primary text-brand-primary bg-brand-soft/50';
    $registerHighlight = $registerHighlight ?? '';
    $hostRegisterClasses = $registerHighlight === 'host'
        ? 'ring-2 ring-brand-dark ring-offset-2 ring-offset-white dark:ring-offset-slate-900 '
        : '';
    $brandSubtitle = $navVariant === 'admin'
        ? '| Staff portal · port '.PortalDetector::adminPort()
        : '| '.($municipalityName ?? 'Impasug-ong').' stays';
    $loginLabel = $navVariant === 'admin' ? 'Staff login' : 'Login';
@endphp
@if ($navLayout === 'minimal')
<style>
    @include('partials.navbar-tribal-shell-styles')
    @include('partials.portal-public-nav-minimal-styles')
</style>
@include('partials.portal-nav-minimal-burger')
<nav id="portalPublicNavbar" class="portal-nav-minimal portal-nav-minimal--burger public-nav-tribal fixed left-0 right-0 top-0 z-[1000] flex w-full flex-col" aria-label="Site">
    @include('partials.navbar-tribal-accent')
    <div class="portal-nav-minimal__inner">
        @include('partials.navbar-brand-block', [
            'brandHref' => route('portal.landing'),
            'brandSubtitle' => $brandSubtitle,
        ])
        <ul class="portal-nav-minimal__links">
            <li>
                <a
                    href="{{ $homeUrl }}"
                    class="portal-nav-minimal__link {{ in_array($active, ['home', 'landing'], true) ? 'is-active' : '' }}"
                    @if(in_array($active, ['home', 'landing'], true)) aria-current="page" @endif
                ><i class="fas fa-house" aria-hidden="true"></i> Home</a>
            </li>
            <li>
                <a
                    href="{{ $exploreUrl }}"
                    class="portal-nav-minimal__link {{ $active === 'browse' ? 'is-active' : '' }}"
                    @if($active === 'browse') aria-current="page" @endif
                ><i class="fas fa-compass" aria-hidden="true"></i> Explore</a>
            </li>
            <li>
                <a
                    href="{{ $aboutUrl }}"
                    class="portal-nav-minimal__link {{ $active === 'about' ? 'is-active' : '' }}"
                    @if($active === 'about') aria-current="page" @endif
                ><i class="fas fa-circle-info" aria-hidden="true"></i> About</a>
            </li>
        </ul>
        <div class="portal-nav-minimal__actions portal-nav-minimal__actions--header-desktop">
            <a href="{{ route('login') }}" class="portal-nav-minimal__action portal-nav-minimal__action--text" aria-label="{{ $loginLabel }}">
                <i class="fas fa-sign-in-alt" aria-hidden="true"></i> {{ $loginLabel }}
            </a>
            <a href="{{ $registerGuestUrl }}" class="portal-nav-minimal__action portal-nav-minimal__action--primary" aria-label="Sign up">
                <i class="fas fa-user-plus" aria-hidden="true"></i> Sign up
            </a>
            <a
                href="{{ $registerOwnerUrl }}"
                class="portal-nav-minimal__action portal-nav-minimal__action--primary portal-nav-minimal__action--host-desktop {{ $registerHighlight === 'host' ? 'is-highlighted' : '' }}"
                @if($registerHighlight === 'host') aria-current="page" @endif
            ><i class="fas fa-home" aria-hidden="true"></i> Host</a>
        </div>
        <button type="button" class="portal-nav-minimal__toggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="portalPublicNavMenu"
                onclick="var n=document.getElementById('portalPublicNavbar');if(!n)return;var o=n.classList.toggle('nav-open');this.setAttribute('aria-expanded',o?'true':'false');">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>
    </div>
    <ul id="portalPublicNavMenu" class="portal-nav-minimal__mobile-links" aria-label="Sections">
        <li>
            <a
                href="{{ $homeUrl }}"
                class="portal-nav-minimal__link {{ in_array($active, ['home', 'landing'], true) ? 'is-active' : '' }}"
                @if(in_array($active, ['home', 'landing'], true)) aria-current="page" @endif
            ><i class="fas fa-house" aria-hidden="true"></i> Home</a>
        </li>
        <li>
            <a
                href="{{ $exploreUrl }}"
                class="portal-nav-minimal__link {{ $active === 'browse' ? 'is-active' : '' }}"
                @if($active === 'browse') aria-current="page" @endif
            ><i class="fas fa-compass" aria-hidden="true"></i> Explore</a>
        </li>
        <li>
            <a
                href="{{ $aboutUrl }}"
                class="portal-nav-minimal__link {{ $active === 'about' ? 'is-active' : '' }}"
                @if($active === 'about') aria-current="page" @endif
            ><i class="fas fa-circle-info" aria-hidden="true"></i> About</a>
        </li>
        <li>
            <a
                href="{{ $registerOwnerUrl }}"
                class="portal-nav-minimal__link portal-nav-minimal__action--host-mobile {{ $registerHighlight === 'host' ? 'is-active' : '' }}"
                @if($registerHighlight === 'host') aria-current="page" @endif
            ><i class="fas fa-home" aria-hidden="true"></i> Host</a>
        </li>
        <li class="portal-nav-minimal__item--auth-mobile">
            <a href="{{ route('login') }}" class="portal-nav-minimal__link">
                <i class="fas fa-sign-in-alt" aria-hidden="true"></i> {{ $loginLabel }}
            </a>
        </li>
        <li class="portal-nav-minimal__item--auth-mobile">
            <a href="{{ $registerGuestUrl }}" class="portal-nav-minimal__link portal-nav-minimal__link--signup">
                <i class="fas fa-user-plus" aria-hidden="true"></i> Sign up
            </a>
        </li>
    </ul>
</nav>
@include('partials.portal-nav-minimal-burger-script')
@else
<style>
    @include('partials.navbar-tribal-shell-styles')
    :root {
        --app-topbar-height: 84px;
        --app-topbar-height-mobile: 72px;
        --app-main-top-offset: 100px;
        --portal-public-nav-offset: var(--app-main-top-offset);
        --portal-content-below-nav: calc(var(--app-topbar-height, 84px) + clamp(1.25rem, 2vw, 1.875rem));
    }
    @media (max-width: 768px) {
        :root {
            --app-main-top-offset: 88px;
            --portal-content-below-nav: calc(var(--app-topbar-height-mobile, 72px) + clamp(1.25rem, 2vw, 1.875rem));
        }
    }
</style>
<nav class="public-nav-tribal fixed left-0 right-0 top-0 z-[1000] flex w-full flex-col items-stretch justify-between bg-white/95 shadow-[0_2px_12px_rgba(27,94,32,0.08)] backdrop-blur-md md:flex-row md:items-center">
    @include('partials.navbar-tribal-accent')
    @include('partials.navbar-brand-block', [
        'brandHref' => route('portal.landing'),
        'brandSubtitle' => $brandSubtitle,
    ])
    <ul class="hidden list-none items-center gap-2 md:flex lg:gap-5">
        <li>
            <a href="{{ $homeUrl }}" class="{{ $linkBase }} {{ in_array($active, ['home', 'landing'], true) ? $linkActive : $linkDefault }}">
                <i class="fas fa-house text-sm opacity-90" aria-hidden="true"></i> Home
            </a>
        </li>
        <li>
            <a href="{{ $exploreUrl }}" class="{{ $linkBase }} {{ $active === 'browse' ? $linkActive : $linkDefault }}">
                <i class="fas fa-compass text-sm opacity-90" aria-hidden="true"></i> Explore
            </a>
        </li>
        <li>
            <a href="{{ $aboutUrl }}" class="{{ $linkBase }} {{ $active === 'about' ? $linkActive : $linkDefault }}">
                <i class="fas fa-circle-info text-sm opacity-90" aria-hidden="true"></i> About Us
            </a>
        </li>
    </ul>
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg border-2 border-brand-primary bg-transparent px-4 py-2 text-sm font-semibold text-brand-dark transition-colors hover:bg-brand-primary hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
            <i class="fas fa-sign-in-alt text-sm"></i> {{ $loginLabel }}
        </a>
        <a href="{{ $registerOwnerUrl }}" {{ $registerHighlight === 'host' ? 'aria-current="page"' : '' }} class="{{ $hostRegisterClasses }}inline-flex items-center gap-2 rounded-lg bg-gradient-to-br from-brand-dark to-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-[0_3px_12px_rgba(46,125,50,0.25)] transition-all hover:opacity-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
            <i class="fas fa-home text-sm" aria-hidden="true"></i> Host
        </a>
    </div>
</nav>
@endif
