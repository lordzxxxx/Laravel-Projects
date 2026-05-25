@php
    use App\Support\PortalDetector;

    $active = $active ?? '';
    $navVariant = $navVariant ?? 'public';
    $isAdminHeader = $navVariant === 'admin' || PortalDetector::isAdminPortal(request());
    $publicOrigin = $isAdminHeader ? PortalDetector::publicPortalOrigin(request()) : null;
    $exploreUrl = $publicOrigin ? $publicOrigin.'/explore/accommodations' : route('portal.accommodations.index');
    $aboutUrl = $publicOrigin ? $publicOrigin.'/about' : route('portal.about');
    $registerGuestUrl = $publicOrigin ? $publicOrigin.'/register/guest' : route('register.guest');
    $registerOwnerUrl = $publicOrigin ? $publicOrigin.'/register/owner' : route('register.owner');
    $linkBase = 'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors hover:bg-brand-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2';
    $linkDefault = 'text-brand-dark';
    $linkActive = 'border-b-2 border-brand-primary text-brand-primary bg-brand-soft/50';
@endphp
<nav class="portal-public-nav fixed left-0 right-0 top-0 z-[1000] flex w-full flex-col items-stretch gap-3 overflow-x-hidden border-b-2 border-brand-soft bg-white/95 px-4 py-3 shadow-[0_2px_12px_rgba(27,94,32,0.08)] backdrop-blur-md sm:px-5 md:flex-row md:items-center md:justify-between md:gap-8 md:px-8 md:py-3.5 lg:px-10">
    <a href="{{ route('portal.landing') }}" class="portal-public-nav__brand flex min-w-0 items-center justify-center gap-2.5 no-underline rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 sm:justify-start md:gap-3.5">
        <img src="/SYSTEMLOGO.png" alt="IMPASUGONG TOURISM" class="h-12 w-auto shrink-0 rounded-xl object-contain sm:h-14 md:h-[3.75rem] lg:h-16">
        <div class="portal-public-nav__brand-text min-w-0 leading-tight">
            <span class="block truncate text-sm font-extrabold tracking-tight text-brand-dark sm:text-base md:text-lg">IMPASUGONG TOURISM</span>
            @if($isAdminHeader)
                <span class="block text-[0.68rem] font-medium leading-none text-brand-medium md:text-[0.75rem]">| Staff portal · port {{ PortalDetector::adminPort() }}</span>
            @endif
        </div>
    </a>
    <ul class="order-3 flex w-full list-none items-center justify-center gap-2 overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden md:order-none md:w-auto md:justify-start md:gap-3 lg:gap-5">
        <li>
            <a href="{{ $exploreUrl }}" class="{{ $linkBase }} whitespace-nowrap {{ $active === 'browse' ? $linkActive : $linkDefault }}">
                <i class="fas fa-compass text-sm opacity-90"></i> Explore
            </a>
        </li>
        @unless($isAdminHeader)
            <li>
                <a href="{{ $aboutUrl }}" class="{{ $linkBase }} whitespace-nowrap {{ $active === 'about' ? $linkActive : $linkDefault }}">
                    <i class="fas fa-circle-info text-sm opacity-90"></i> About Us
                </a>
            </li>
        @endunless
    </ul>
    <div class="order-2 flex shrink-0 flex-nowrap items-center justify-center gap-2 sm:gap-2.5 md:order-none md:ml-auto md:justify-end">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg border-2 border-brand-primary bg-transparent px-3 py-2 text-sm font-semibold text-brand-dark transition-colors hover:bg-brand-primary hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 sm:px-4">
            <i class="fas fa-sign-in-alt text-sm"></i> @if($isAdminHeader) Staff login @else Login @endif
        </a>
        @include('partials.register-choice-menu', [
            'guestUrl' => $registerGuestUrl,
            'hostUrl' => $registerOwnerUrl,
        ])
    </div>
</nav>
