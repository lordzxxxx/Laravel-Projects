@php
    $current = $active ?? '';
    $portalDirectory = $portalDirectory ?? false;
    $usePortalGuestUrls = $portalDirectory || \App\Support\PortalDetector::isPublicPortal(request());

    if ($usePortalGuestUrls) {
        $guestDashboardHref = route('portal.guest.dashboard');
        $bookingsHref = route('portal.bookings.index');
        $wishlistHref = route('portal.wishlist.index');
    } else {
        $guestDashboardHref = '/dashboard';
        $bookingsHref = '/bookings';
        $wishlistHref = null;
    }
@endphp

<nav class="navbar portal-nav-minimal public-nav-tribal" id="appNavbar">
    @include('partials.navbar-tribal-accent')
    @include('partials.navbar-brand-block', [
        'brandHref' => $guestDashboardHref,
        'brandSubtitle' => '| Guest stays',
    ])

    @include('partials.nav-burger-toggle', ['targetId' => 'appNavbar'])

    <ul class="nav-links">
        <li><a href="{{ $guestDashboardHref }}" class="{{ in_array($current, ['dashboard', 'accommodations'], true) ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a></li>
        @if($usePortalGuestUrls)
            <li><a href="{{ route('portal.accommodations.index') }}" class="{{ $current === 'accommodations' ? 'active' : '' }}"><i class="fas fa-building"></i> Accommodations</a></li>
        @endif
        @if($wishlistHref)
            <li><a href="{{ $wishlistHref }}" class="{{ $current === 'wishlist' ? 'active' : '' }}"><i class="fas fa-heart"></i> Wishlist</a></li>
        @endif
        @if(Auth::user()->tenantClientMayManageOwnStays())
            <li><a href="{{ $bookingsHref }}" class="{{ $current === 'bookings' ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> My Bookings</a></li>
        @endif
        @if(Auth::user()->tenantClientMayUseMessaging())
            <li><a href="/messages" class="{{ $current === 'messages' ? 'active' : '' }}"><i class="fas fa-envelope"></i> Messages @if(($unreadMessagesCount ?? 0) > 0)<span style="display:inline-flex;align-items:center;justify-content:center;min-width:18px;height:18px;border-radius:999px;padding:0 5px;background:#EF4444;color:#fff;font-size:0.68rem;font-weight:700;margin-left:6px;">{{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}</span>@endif</a></li>
        @endif
        @if(Auth::user()->tenantClientMaySubmitUpdateTickets())
            <li><a href="/update-tickets" class="{{ $current === 'update-tickets' ? 'active' : '' }}"><i class="fas fa-life-ring"></i> Support</a></li>
        @endif
    </ul>

    <div class="nav-actions">
        @include('partials.notification-bell')
        <div class="user-menu" data-user-menu>
            <button type="button" class="user-display user-menu__button" aria-haspopup="menu" aria-expanded="false">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/avatars/' . Auth::user()->avatar . '?v=' . time()) }}" alt="{{ Auth::user()->name }}" class="user-avatar" style="object-fit: cover;">
                @else
                    <div class="user-avatar">{{ substr(Auth::user()->name, 0, 2) }}</div>
                @endif
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Guest</div>
                </div>
                <i class="fas fa-chevron-down user-menu__chevron" aria-hidden="true"></i>
            </button>

            <div class="user-menu__panel" role="menu">
                @if(Auth::user()->tenantClientMayEditOwnProfile())
                    <a role="menuitem" class="user-menu__item" href="/profile"><i class="fas fa-user"></i> Profile</a>
                    <div class="user-menu__sep" role="separator" aria-hidden="true"></div>
                @endif
                <form class="user-menu__form" action="/logout" method="POST">
                    @csrf
                    <button role="menuitem" type="submit" class="user-menu__item user-menu__item--danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

@once
    <script>
        (function () {
            if (window.__impaUserMenuInit) return;
            window.__impaUserMenuInit = true;

            function closeAll(exceptRoot) {
                document.querySelectorAll('[data-user-menu].is-open').forEach(function (root) {
                    if (exceptRoot && root === exceptRoot) return;
                    root.classList.remove('is-open');
                    var btn = root.querySelector('button[aria-expanded]');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                });
            }

            function toggleFromButton(btn) {
                var root = btn && btn.closest ? btn.closest('[data-user-menu]') : null;
                if (!root) return;
                var willOpen = !root.classList.contains('is-open');
                closeAll(root);
                root.classList.toggle('is-open', willOpen);
                btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            }

            window.__impaUserMenuToggle = toggleFromButton;

            function bindAll() {
                document.querySelectorAll('button.user-menu__button').forEach(function (btn) {
                    if (btn.__impaBound) return;
                    btn.__impaBound = true;
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleFromButton(btn);
                    });
                });
            }

            bindAll();
            document.addEventListener('DOMContentLoaded', bindAll);

            document.addEventListener('click', function (e) {
                var root = e.target && e.target.closest ? e.target.closest('[data-user-menu]') : null;
                if (!root) closeAll();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeAll();
            });
        })();
    </script>
@endonce