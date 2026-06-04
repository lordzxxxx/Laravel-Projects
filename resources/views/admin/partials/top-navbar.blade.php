@php
    $current = $active ?? '';
    $host = request()->getHost();
    $centralDomain = (string) config('app.domain', 'localhost');
    $isCentralHost = in_array($host, [$centralDomain, 'localhost', '127.0.0.1', '::1'], true);
    $isTenantScopedAdmin = auth()->check()
        && auth()->user()->isAdmin()
        && !empty(auth()->user()->tenant_id)
        && ! $isCentralHost;
    $isTenantContext = \App\Models\Tenant::checkCurrent() || $isTenantScopedAdmin;

    // Use host-local paths to avoid cross-domain route URL generation.
    $dashboardHref = $isTenantContext ? '/owner/dashboard' : '/admin/dashboard';
    $unitsHref = $isTenantContext ? '/owner/accommodations' : '/admin/tenants';
    $bookingsHref = '/owner/bookings';
    $reportsHref = '/owner/reports/monthly';
    $updatesHref = $isTenantContext ? '/settings/updates' : '/admin/system-updates';
    $updateTicketsHref = ! $isTenantContext ? '/admin/system-updates/tickets' : null;
    $messagesHref = $isTenantContext ? '/messages' : '/admin/messages';
    $settingsHref = '/profile';
    $landingHref = '/';
@endphp

@if($isTenantContext)
    @include('owner.partials.top-navbar', ['active' => $current])
@else
    <nav class="navbar portal-nav-minimal public-nav-tribal" id="appNavbar">
        @include('partials.navbar-tribal-accent')
        @include('partials.navbar-brand-block', [
            'brandHref' => $landingHref,
            'brandSubtitle' => '| Admin Dashboard',
        ])

        @include('partials.nav-burger-toggle', ['targetId' => 'appNavbar'])

        <ul class="nav-links">
            <li><a href="{{ $dashboardHref }}" class="{{ $current === 'dashboard' ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="{{ $unitsHref }}" class="{{ $current === 'tenants' ? 'active' : '' }}"><i class="fas fa-building-user"></i> Tulogans</a></li>
            <li><a href="{{ $updatesHref }}" class="{{ $current === 'updates' ? 'active' : '' }}"><i class="fas fa-cloud-download-alt"></i> Updates</a></li>
            @if($updateTicketsHref)
                <li><a href="{{ $updateTicketsHref }}" class="{{ $current === 'update-tickets' ? 'active' : '' }}"><i class="fas fa-life-ring"></i> Support</a></li>
            @endif
            <li><a href="{{ $messagesHref }}" class="{{ $current === 'messages' ? 'active' : '' }}"><i class="fas fa-envelope"></i> Messages @if(($unreadMessagesCount ?? 0) > 0)<span style="display:inline-flex;align-items:center;justify-content:center;min-width:18px;height:18px;border-radius:999px;padding:0 5px;background:#EF4444;color:#fff;font-size:0.68rem;font-weight:700;margin-left:6px;">{{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}</span>@endif</a></li>
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
                        <div class="user-role">{{ Auth::user()->role === 'client' ? 'Guest' : ucfirst(Auth::user()->role) }}</div>
                    </div>
                    <i class="fas fa-chevron-down user-menu__chevron" aria-hidden="true"></i>
                </button>

                <div class="user-menu__panel" role="menu">
                    <a role="menuitem" class="user-menu__item" href="{{ $settingsHref }}"><i class="fas fa-user"></i> Profile</a>
                    <div class="user-menu__sep" role="separator" aria-hidden="true"></div>
                    <form class="user-menu__form" action="/logout" method="POST">
                        @csrf
                        <button role="menuitem" type="submit" class="user-menu__item user-menu__item--danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
@endif

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

            // Expose a stable hook for inline/other callers.
            window.__impaUserMenuToggle = toggleFromButton;

            // Direct binding (more reliable than delegated in complex layouts).
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
