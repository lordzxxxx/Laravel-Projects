@php
    use App\Support\PortalDetector;

    $active = $active ?? '';
    $navVariant = $navVariant ?? 'public';
    $publicOrigin = $navVariant === 'admin' ? PortalDetector::publicPortalOrigin(request()) : null;
    $exploreUrl = $publicOrigin ? $publicOrigin.'/explore/accommodations' : route('portal.accommodations.index');
    $aboutUrl = $publicOrigin ? $publicOrigin.'/about' : route('portal.about');
    $registerGuestUrl = $publicOrigin ? $publicOrigin.'/register/guest' : route('register.guest');
    $registerOwnerUrl = $publicOrigin ? $publicOrigin.'/register/owner' : route('register.owner');
    $linkBase = 'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors hover:bg-brand-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2';
    $linkDefault = 'text-brand-dark';
    $linkActive = 'border-b-2 border-brand-primary text-brand-primary bg-brand-soft/50';
    $registerHighlight = $registerHighlight ?? '';
    $guestRegisterClasses = $registerHighlight === 'guest'
        ? 'ring-2 ring-brand-primary bg-brand-soft/70 '
        : '';
    $hostRegisterClasses = $registerHighlight === 'host'
        ? 'ring-2 ring-brand-dark ring-offset-2 ring-offset-white dark:ring-offset-slate-900 '
        : '';
    $brandSubtitle = $navVariant === 'admin'
        ? '| Staff portal · port '.PortalDetector::adminPort()
        : '| '.($municipalityName ?? 'Impasug-ong').' stays';
@endphp
<style>
    @include('partials.navbar-tribal-shell-styles')
</style>
<nav class="public-nav-tribal fixed left-0 right-0 top-0 z-[1000] flex w-full flex-col items-stretch justify-between gap-3 bg-white/95 px-5 py-3 shadow-[0_2px_12px_rgba(27,94,32,0.08)] backdrop-blur-md md:flex-row md:items-center md:gap-8 md:px-8 md:py-3.5 lg:px-10">
    @include('partials.navbar-tribal-accent')
    @include('partials.navbar-brand-block', [
        'brandHref' => route('portal.landing'),
        'brandSubtitle' => $brandSubtitle,
    ])
    <ul class="hidden list-none items-center gap-2 md:flex lg:gap-5">
        <li>
            <a href="{{ $exploreUrl }}" class="{{ $linkBase }} {{ $active === 'browse' ? $linkActive : $linkDefault }}">
                <i class="fas fa-compass text-sm opacity-90"></i> Explore
            </a>
        </li>
        <li>
            <a href="{{ $aboutUrl }}" class="{{ $linkBase }} {{ $active === 'about' ? $linkActive : $linkDefault }}">
                <i class="fas fa-circle-info text-sm opacity-90"></i> About Us
            </a>
        </li>
    </ul>
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg border-2 border-brand-primary bg-transparent px-4 py-2 text-sm font-semibold text-brand-dark transition-colors hover:bg-brand-primary hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
            <i class="fas fa-sign-in-alt text-sm"></i> @if($navVariant === 'admin') Staff login @else Login @endif
        </a>
        <a href="{{ $registerGuestUrl }}" {{ $registerHighlight === 'guest' ? 'aria-current="page"' : '' }} class="{{ $guestRegisterClasses }}inline-flex items-center gap-2 rounded-lg border-2 border-brand-soft bg-white px-4 py-2 text-sm font-semibold text-brand-dark transition-colors hover:border-brand-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
            <i class="fas fa-user text-sm" aria-hidden="true"></i> Guest
        </a>
        <a href="{{ $registerOwnerUrl }}" {{ $registerHighlight === 'host' ? 'aria-current="page"' : '' }} class="{{ $hostRegisterClasses }}inline-flex items-center gap-2 rounded-lg bg-gradient-to-br from-brand-dark to-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-[0_3px_12px_rgba(46,125,50,0.25)] transition-all hover:opacity-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
            <i class="fas fa-home text-sm" aria-hidden="true"></i> Host
        </a>
    </div>
</nav>
