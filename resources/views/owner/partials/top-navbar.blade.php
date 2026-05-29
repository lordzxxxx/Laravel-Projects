<nav class="navbar" id="appNavbar">
    @include('partials.navbar-tribal-accent')
    @php
        $currentTenant = \App\Models\Tenant::current();
        $current = $active ?? '';
        $authUser = Auth::user();
        $displayRole = $authUser?->tenantCustomRole?->name ?: ucfirst((string) $authUser?->role);
        $dashboardHref = '/owner/dashboard';
        $reportsHref = '/owner/reports/monthly';
        $unitsHref = '/owner/accommodations';
        $bookingsHref = '/owner/bookings';
        $updatesHref = route('settings.updates.index', [], false);
        $usersHref = '/owner/users';
        $messagesHref = '/messages';
        $profileHref = '/profile';
        $logoutHref = '/logout';
        $isOwnerRole = $authUser?->isOwner() ?? false;
        $isTenantManagerRole = $isOwnerRole || ($authUser?->isAdmin() && \App\Models\Tenant::checkCurrent());
        $canSeeDashboard = $isTenantManagerRole || $authUser?->hasAnyPermission([
            \App\Models\User::PERM_USERS_VIEW,
            \App\Models\User::PERM_ACCOMMODATIONS_CREATE,
            \App\Models\User::PERM_ACCOMMODATIONS_UPDATE,
            \App\Models\User::PERM_ACCOMMODATIONS_DELETE,
            \App\Models\User::PERM_BOOKINGS_MANAGE,
            \App\Models\User::PERM_MESSAGES_MANAGE,
            \App\Models\User::PERM_REPORTS_VIEW,
        ]);
        $canSeeReports = $isTenantManagerRole || $authUser?->hasPermission(\App\Models\User::PERM_REPORTS_VIEW);
        $canSeeUnits = $isTenantManagerRole || $authUser?->hasAnyPermission([
            \App\Models\User::PERM_ACCOMMODATIONS_CREATE,
            \App\Models\User::PERM_ACCOMMODATIONS_UPDATE,
            \App\Models\User::PERM_ACCOMMODATIONS_DELETE,
        ]);
        $canSeeBookings = $isTenantManagerRole || $authUser?->hasPermission(\App\Models\User::PERM_BOOKINGS_MANAGE);
        $canSeeUsers = $isTenantManagerRole || $authUser?->hasPermission(\App\Models\User::PERM_USERS_VIEW);
        $canSeeUpdates = $isTenantManagerRole || $authUser?->hasPermission(\App\Models\User::PERM_REPORTS_VIEW);
        $canSeeMessages = $isTenantManagerRole || $authUser?->hasPermission(\App\Models\User::PERM_MESSAGES_MANAGE);
    @endphp

    @include('partials.navbar-brand-block', [
        'brandHref' => $dashboardHref,
        'brandSubtitle' => $currentTenant
            ? '| '.($currentTenant->domain ?: $currentTenant->slug)
            : '| Owner portal',
    ])

    <button type="button" class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false"
            onclick="var n=document.getElementById('appNavbar');var o=n.classList.toggle('nav-open');this.setAttribute('aria-expanded',o?'true':'false');">
        <i class="fas fa-bars"></i>
    </button>

    <ul class="nav-links">
        @if($canSeeDashboard)
            <li><a href="{{ $dashboardHref }}" class="{{ $current === 'dashboard' || request()->routeIs('owner.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a></li>
        @endif
        @if($canSeeReports)
            <li><a href="{{ $reportsHref }}" class="{{ $current === 'reports' || request()->routeIs('owner.reports.*') ? 'active' : '' }}"><i class="fas fa-chart-column"></i> Reports</a></li>
        @endif
        @if($canSeeUnits)
            <li><a href="{{ $unitsHref }}" class="{{ $current === 'accommodations' || request()->routeIs('owner.accommodations.*') ? 'active' : '' }}"><i class="fas fa-building"></i> My Units</a></li>
        @endif
        @if($canSeeBookings)
            <li><a href="{{ $bookingsHref }}" class="{{ $current === 'bookings' || request()->routeIs('owner.bookings.*') ? 'active' : '' }}"><i class="fas fa-calendar-check"></i> Bookings</a></li>
        @endif
        @if($canSeeUsers)
            <li><a href="{{ $usersHref }}" class="{{ $current === 'users' || request()->routeIs('owner.users.*') ? 'active' : '' }}"><i class="fas fa-users-cog"></i> Users</a></li>
        @endif
        @if($canSeeUpdates)
            <li><a href="{{ $updatesHref }}" class="{{ $current === 'updates' || request()->routeIs('owner.settings.updates.*', 'settings.updates.*', 'admin.updates.*') ? 'active' : '' }}"><i class="fas fa-cloud-download-alt"></i> Updates</a></li>
        @endif
        @if($canSeeMessages)
            @php $msgUnread = (int) ($unreadMessagesCount ?? 0); @endphp
            <li><a href="{{ $messagesHref }}" class="{{ $current === 'messages' || request()->routeIs('messages.*') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Messages <span class="nav-msg-count-badge {{ $msgUnread === 0 ? 'is-empty' : '' }}" @if($msgUnread > 0) aria-label="{{ $msgUnread }} unread messages" @else aria-hidden="true" @endif>@if($msgUnread > 0){{ $msgUnread > 99 ? '99+' : $msgUnread }}@else{!! '&nbsp;' !!}@endif</span></a></li>
        @endif
    </ul>

    <div class="nav-actions">
        @include('partials.notification-bell')
        <div class="user-menu" data-user-menu>
            <button type="button" class="user-display user-menu__button" aria-haspopup="menu" aria-expanded="false">
                @if($authUser?->avatar)
                    <img src="{{ asset('storage/avatars/' . $authUser->avatar . '?v=' . time()) }}" alt="{{ $authUser->name }}" class="user-avatar" style="object-fit: cover;">
                @else
                    <div class="user-avatar">{{ substr((string) $authUser?->name, 0, 2) }}</div>
                @endif
                <div class="user-info">
                    <div class="user-name">{{ $authUser?->name }}</div>
                    <div class="user-role">{{ $displayRole }}</div>
                </div>
                <i class="fas fa-chevron-down user-menu__chevron" aria-hidden="true"></i>
            </button>

            <div class="user-menu__panel" role="menu">
                <a role="menuitem" class="user-menu__item" href="{{ route('owner.landing.edit', [], false) }}"><i class="fas fa-palette"></i> Landing &amp; logo</a>
                <a role="menuitem" class="user-menu__item" href="{{ $profileHref }}"><i class="fas fa-user"></i> Profile</a>
                <div class="user-menu__sep" role="separator" aria-hidden="true"></div>
                <form class="user-menu__form" action="{{ $logoutHref }}" method="POST">
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
