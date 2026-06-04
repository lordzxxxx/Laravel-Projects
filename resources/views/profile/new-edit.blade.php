<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.responsive-page-head', ['pageTitle' => 'Profile — Impasugong Accommodations', 'includeTypographyInline' => false])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.tenant-favicon')
    @include('partials.appearance-boot')
    @php
        $authUser = auth()->user();
        $isTenantAdminContext = $authUser && $authUser->isAdmin() && \App\Models\Tenant::checkCurrent();
        $isCentralAdminPortal = $authUser && $authUser->isAdmin() && ! $isTenantAdminContext;
        $useLegacyProfileNav = $authUser && ! $authUser->isOwner() && ! $authUser->isClient() && ! $authUser->isAdmin();
        $u = $authUser;
        $notify = $u->notification_preferences ?? [];
        $appearance = $u->normalizedAppearancePreferences();
        $onTenantPortal = ! \App\Support\PortalDetector::isCentralHost(request());
        $canEditTenantLanding = $onTenantPortal && ($u->isOwner() || $u->isAdmin());
        $roleLabels = ['admin' => 'Administrator', 'owner' => 'Property Owner', 'client' => 'Guest'];
        $roleIcons = ['admin' => 'fa-user-shield', 'owner' => 'fa-user-tie', 'client' => 'fa-user'];
        $usesOwnerShell = auth()->user()?->isOwner() || $isTenantAdminContext;
        $isGuestClient = auth()->user()?->isClient() && ! $usesOwnerShell && ! $isCentralAdminPortal;
    @endphp
    <style>
        @include('owner.partials.owner-page-fonts')
        @if($useLegacyProfileNav)
            @include('partials.ui-foundation-styles')
        @endif

        :root {
            @include('partials.tenant-theme-css-vars')
            --profile-offset: var(--app-main-top-offset, 108px);
        }

        * { box-sizing: border-box; }

        @if($useLegacyProfileNav)
        .navbar {
            background: var(--app-surface-bg, #fff);
            padding: 0 40px;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo img { width: 45px; height: 45px; object-fit: contain; }
        .nav-logo span { font-size: 1.2rem; font-weight: 700; color: var(--brand-800, #457359); }
        .nav-links { display: flex; gap: 25px; list-style: none; padding: 0; margin: 0; }
        .nav-links a { text-decoration: none; color: var(--ink-600); font-weight: 500; padding: 8px 12px; border-radius: 8px; }
        .nav-links a:hover, .nav-links a.active { background: var(--brand-50); color: var(--brand-800); }
        .nav-actions { display: flex; gap: 15px; align-items: center; }
        .nav-btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; background: var(--brand-700); color: #fff; }
        :root { --profile-offset: 86px; }
        @endif

        @if($isCentralAdminPortal)
            @include('admin.partials.central-portal-background-styles')
        @endif

        body.owner-nav-page.profile-page { --profile-offset: var(--owner-content-offset, var(--app-content-offset, 108px)); }
        body.profile-page:has(.navbar.client-top-nav),
        body.profile-page.client-nav-page { --profile-offset: var(--client-nav-offset, var(--app-content-offset, calc(var(--app-topbar-height, 4rem) + clamp(1.25rem, 2vw, 1.875rem)))); }

        body.profile-page:not(.client-nav-page):not(.owner-nav-page):not(.admin-central-portal) {
            margin: 0;
            min-height: 100vh;
            background: var(--app-page-bg, #f4f8f5);
            color: var(--ink-800, #1f2937);
        }

        body.profile-page.client-nav-page,
        body.profile-page.owner-nav-page,
        body.profile-page.admin-central-portal {
            margin: 0;
            min-height: 100vh;
            color: var(--ink-800, #1f2937);
        }

        .profile-main {
            width: min(1600px, 100%);
            margin: 0 auto;
            padding: var(--profile-offset) clamp(16px, 2.5vw, 36px) 32px;
            min-height: calc(100vh - var(--profile-offset));
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .page-header p { margin-top: 0.35rem; max-width: 42rem; }
        .page-header a { color: var(--chrome-icon-color, var(--brand-700)); font-weight: 600; text-decoration: none; }
        .page-header a:hover { text-decoration: underline; }

        .profile-flash {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .profile-flash.success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .profile-flash.error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .profile-flash ul { margin: 0; padding-left: 1.1rem; }

        .profile-grid {
            flex: 1;
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr);
            gap: 1.25rem;
            align-items: start;
            min-height: 0;
        }

        .profile-stack {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            min-height: 0;
        }

        .profile-panel {
            background: var(--app-surface-bg, #fff);
            border: 1px solid var(--app-surface-border, #e5e7eb);
            border-radius: 14px;
            box-shadow: var(--shadow-sm, 0 1px 2px rgba(15, 23, 42, 0.05));
            overflow: hidden;
        }

        .profile-panel__head {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--app-surface-border, #e5e7eb);
            background: var(--app-surface-muted-bg, #f8fafc);
        }

        .profile-panel__head h2 {
            margin: 0;
            color: var(--ink-600, #475569);
        }

        .profile-panel__head p {
            margin: 0.35rem 0 0;
            font-size: 0.8125rem;
            color: var(--ink-500, #64748b);
            line-height: 1.45;
        }

        .profile-panel__body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .profile-user {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-bottom: 1rem;
            margin-bottom: 0.25rem;
            border-bottom: 1px solid var(--app-surface-border, #e5e7eb);
        }

        .profile-user__avatar {
            width: 4.5rem;
            height: 4.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--chrome-avatar-bg, var(--brand-700));
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid var(--app-surface-bg, #fff);
            box-shadow: var(--shadow-sm);
        }

        .profile-user__avatar img { width: 100%; height: 100%; object-fit: cover; }

        .profile-user__body { min-width: 0; flex: 1; }

        .profile-user__name {
            margin: 0 0 0.2rem;
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--ink-900);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-user__email {
            margin: 0 0 0.5rem;
            font-size: 0.8125rem;
            color: var(--ink-500);
        }

        .profile-user__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
        }

        .meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            background: var(--app-surface-muted-bg, #f8fafc);
            border: 1px solid var(--app-surface-border, #e5e7eb);
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--ink-600);
        }

        .role-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .role-chip.admin { background: #eef2ff; color: #4338ca; }
        .role-chip.owner { background: var(--brand-50, #ecfdf5); color: var(--brand-800, #14532d); }
        .role-chip.client { background: var(--ink-100); color: var(--ink-700); }

        .field label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--ink-700);
            margin-bottom: 0.4rem;
        }

        .field label .req { color: #b91c1c; }

        .field input[type="text"],
        .field input[type="email"],
        .field input[type="tel"],
        .field input[type="password"],
        .field textarea {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1px solid var(--app-surface-border, #d1d5db);
            border-radius: 10px;
            font-size: 0.9375rem;
            background: var(--app-surface-bg, #fff);
            color: var(--ink-900);
            font-family: inherit;
        }

        .field textarea { resize: vertical; min-height: 5.5rem; line-height: 1.5; }

        .field input:focus,
        .field textarea:focus {
            outline: none;
            border-color: var(--chrome-focus-ring, var(--brand-700));
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--chrome-focus-ring, #457359) 18%, transparent);
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .avatar-upload {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.85rem;
            border: 1px dashed var(--app-surface-border, #d1d5db);
            border-radius: 12px;
            background: var(--app-surface-muted-bg, #f8fafc);
        }

        .avatar-upload__preview {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--chrome-avatar-bg, var(--brand-700));
            color: #fff;
            font-weight: 700;
            overflow: hidden;
            flex-shrink: 0;
        }

        .avatar-upload__preview img { width: 100%; height: 100%; object-fit: cover; }

        .avatar-upload__meta { flex: 1; min-width: 0; }
        .avatar-upload__meta p { margin: 0; font-size: 0.8125rem; color: var(--ink-500); line-height: 1.4; }
        .avatar-upload__meta p:first-child { font-weight: 600; color: var(--ink-700); margin-bottom: 0.15rem; }
        .avatar-upload input[type="file"] { display: none; }

        .btn-upload {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 0.5rem;
            padding: 0.45rem 0.75rem;
            border: 1px solid var(--app-surface-border);
            border-radius: 8px;
            background: var(--app-surface-bg);
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--ink-700);
            cursor: pointer;
        }

        .btn-upload:hover { background: var(--app-surface-muted-bg); }

        .notify-list { display: grid; gap: 0.5rem; }

        .notify-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border: 1px solid var(--app-surface-border);
            border-radius: 10px;
            background: var(--app-surface-bg);
            cursor: pointer;
        }

        .notify-item__label {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--ink-700);
        }

        .notify-item__icon {
            width: 2rem;
            height: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: var(--chrome-icon-bg, var(--brand-50));
            color: var(--chrome-icon-color, var(--brand-700));
            font-size: 0.8rem;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 2.25rem;
            height: 1.25rem;
            flex-shrink: 0;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .switch .slider {
            position: absolute;
            inset: 0;
            background: var(--ink-300, #cbd5e1);
            border-radius: 999px;
            transition: 0.2s ease;
            cursor: pointer;
        }
        .switch .slider::before {
            content: '';
            position: absolute;
            height: 1rem;
            width: 1rem;
            left: 2px;
            top: 2px;
            background: #fff;
            border-radius: 50%;
            transition: 0.2s ease;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.15);
        }
        .switch input:checked + .slider { background: var(--chrome-active-bg, var(--brand-700)); }
        .switch input:checked + .slider::before { transform: translateX(1rem); }

        .profile-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: var(--app-surface-bg);
            border: 1px solid var(--app-surface-border);
            border-radius: 14px;
            box-shadow: var(--shadow-sm);
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.35rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            background: var(--chrome-active-bg, var(--brand-700));
            color: #fff;
        }

        .btn-save:disabled { opacity: 0.6; cursor: not-allowed; }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.7rem 1.1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            border: 1px solid var(--app-surface-border);
            background: var(--app-surface-bg);
            color: var(--ink-700);
        }

        .profile-panel--danger { border-color: rgba(185, 28, 28, 0.25); }
        .profile-panel--danger .profile-panel__head h2 { color: #b91c1c; }

        .danger-box {
            padding: 1rem;
            border-radius: 10px;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .danger-box p {
            margin: 0 0 1rem;
            font-size: 0.8125rem;
            color: #7f1d1d;
            line-height: 1.45;
        }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.65rem 1.1rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            background: #b91c1c;
            color: #fff;
        }

        .field-error {
            color: #b91c1c;
            font-size: 0.8125rem;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .profile-grid__danger { grid-column: 1 / -1; }

        @include('partials.appearance-preferences-styles')

        @media (max-width: 1024px) {
            .profile-grid { grid-template-columns: 1fr; }
            .profile-grid__danger { grid-column: 1; }
        }

        @media (max-width: 768px) {
            input, select, textarea, button { max-width: 100%; }
            .field { max-width: 100% !important; }
        }

        @media (max-width: 640px) {
            .profile-main {
                padding-left: clamp(12px, 3vw, 16px);
                padding-right: clamp(12px, 3vw, 16px);
                padding-bottom: 1.25rem;
                gap: 0.75rem;
            }

            .page-header {
                margin-bottom: 0;
            }

            .page-header h1 {
                font-size: 1.25rem;
            }

            .page-header p {
                font-size: 0.8125rem;
            }

            .owner-page-hero,
            .guest-profile-hero {
                padding-bottom: 0.35rem;
            }

            .owner-page-hero__title,
            .guest-profile-hero__title {
                font-size: 1.25rem;
            }

            .owner-page-hero__lede,
            .guest-profile-hero__lede {
                font-size: 0.8125rem;
                line-height: 1.4;
            }

            .profile-grid,
            .profile-stack {
                gap: 0.65rem;
            }

            .profile-panel {
                border-radius: 0.625rem;
            }

            .profile-panel__head {
                padding: 0.65rem 0.75rem;
            }

            .profile-panel__head h2 {
                font-size: 0.75rem;
            }

            .profile-panel__head p {
                font-size: 0.6875rem;
                margin-top: 0.2rem;
            }

            .profile-panel__body {
                padding: 0.75rem;
                gap: 0.65rem;
            }

            .profile-user {
                flex-direction: row;
                align-items: center;
                gap: 0.65rem;
                padding-bottom: 0.65rem;
                margin-bottom: 0;
            }

            .profile-user__avatar {
                width: 3rem;
                height: 3rem;
                font-size: 0.9375rem;
            }

            .profile-user__name {
                font-size: 0.9375rem;
                gap: 0.35rem;
            }

            .profile-user__email {
                font-size: 0.75rem;
                margin-bottom: 0.35rem;
            }

            .profile-user__meta {
                gap: 0.25rem;
            }

            .meta-pill {
                padding: 0.2rem 0.45rem;
                font-size: 0.625rem;
            }

            .role-chip {
                font-size: 0.5625rem;
                padding: 0.15rem 0.4rem;
            }

            .avatar-upload {
                flex-direction: row;
                align-items: center;
                gap: 0.65rem;
                padding: 0.6rem;
            }

            .avatar-upload__preview {
                width: 2.75rem;
                height: 2.75rem;
                font-size: 0.75rem;
            }

            .avatar-upload__meta p {
                font-size: 0.6875rem;
            }

            .btn-upload {
                margin-top: 0.35rem;
                padding: 0.35rem 0.55rem;
                font-size: 0.6875rem;
            }

            .field-row { grid-template-columns: 1fr; gap: 0.65rem; }

            .field label {
                font-size: 0.75rem;
                margin-bottom: 0.3rem;
            }

            .field input[type="text"],
            .field input[type="email"],
            .field input[type="tel"],
            .field input[type="password"],
            .field textarea {
                padding: 0.5rem 0.65rem;
                font-size: 0.8125rem;
                border-radius: 0.5rem;
            }

            .field textarea {
                min-height: 4.25rem;
            }

            .notify-item {
                padding: 0.5rem 0.65rem;
                gap: 0.5rem;
            }

            .notify-item__label {
                font-size: 0.75rem;
                gap: 0.45rem;
            }

            .notify-item__icon {
                width: 1.65rem;
                height: 1.65rem;
                font-size: 0.6875rem;
            }

            .appearance-theme-card {
                padding: 0.65rem;
                gap: 0.45rem;
                border-radius: 0.5rem;
            }

            .appearance-swatch {
                height: 18px;
            }

            .appearance-theme-name {
                font-size: 0.8125rem;
            }

            .appearance-theme-desc {
                font-size: 0.6875rem;
            }

            .appearance-mode-option > span {
                padding: 0.4rem 0.65rem;
                font-size: 0.75rem;
            }

            .profile-actions {
                padding: 0.65rem 0.75rem;
                gap: 0.5rem;
            }

            .profile-actions .btn-save,
            .profile-actions .btn-secondary {
                width: 100%;
                justify-content: center;
                padding: 0.55rem 0.85rem;
                font-size: 0.8125rem;
            }

            .btn-secondary,
            .btn-danger {
                padding: 0.55rem 0.85rem;
                font-size: 0.8125rem;
            }

            .danger-box {
                padding: 0.75rem;
            }

            .danger-box p {
                font-size: 0.75rem;
                margin-bottom: 0.75rem;
            }

            .profile-flash {
                padding: 0.6rem 0.75rem;
                font-size: 0.8125rem;
            }
        }

        @media (max-width: 400px) {
            .owner-page-hero__lede,
            .guest-profile-hero__lede,
            .page-header p {
                display: none;
            }

            .profile-user__meta .meta-pill:last-child {
                display: none;
            }
        }

        @if($usesOwnerShell)
            @include('owner.partials.top-navbar-styles')
        @elseif(auth()->user()?->isClient())
            @include('client.partials.top-navbar-styles')
            @include('client.partials.guest-profile-styles')
        @elseif(auth()->user()?->isAdmin())
            @include('admin.partials.top-navbar-styles')
        @endif
    </style>
</head>
<body class="{{ trim(collect([
    'profile-page',
    auth()->user()?->isClient() ? 'client-nav-page font-sans text-gray-800' : '',
    (auth()->user()?->isOwner() || $isTenantAdminContext) ? 'owner-nav-page' : '',
    $isCentralAdminPortal ? 'admin-central-portal' : '',
])->filter()->implode(' ')) }}">
    @if(auth()->user()?->isOwner() || $isTenantAdminContext)
        @include('owner.partials.top-navbar', ['active' => 'settings'])
    @elseif(auth()->user()?->isClient())
        @include('client.partials.top-navbar', ['active' => 'settings'])
    @elseif(auth()->user()?->isAdmin())
        @include('admin.partials.top-navbar', ['active' => 'settings'])
    @else
        <nav class="navbar">
            <a href="{{ route('landing') }}" class="nav-logo">
                <img src="/SYSTEMLOGO.png" alt="ImpaStay Logo">
                <span>Impasugong</span>
            </a>
            @auth
            <ul class="nav-links">
                @if(Auth::user()->role === 'admin')
                    @php
                        $adminDashboardHref = \App\Models\Tenant::checkCurrent() ? '/owner/dashboard' : '/admin/dashboard';
                        $adminTenantsHref = \App\Models\Tenant::checkCurrent() ? '/owner/accommodations' : '/admin/tenants';
                    @endphp
                    <li><a href="{{ $adminDashboardHref }}">Dashboard</a></li>
                    <li><a href="{{ $adminTenantsHref }}">Tenants</a></li>
                @else
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @endif
                <li><a href="{{ route('messages.index', [], false) }}">Messages</a></li>
                <li><a href="{{ route('profile.edit') }}" class="active">Settings</a></li>
            </ul>
            <div class="nav-actions">
                <form action="/logout" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
            </div>
            @endauth
        </nav>
    @endif

    <main class="profile-main {{ $isGuestClient ? 'client-guest-main client-guest-main--wide guest-profile-main' : ($usesOwnerShell ? 'main-content with-owner-nav owner-app-main' : '') }}">
        @if($isGuestClient)
            <header class="guest-profile-hero">
                <p class="guest-profile-hero__eyebrow">Account</p>
                <h1 class="guest-profile-hero__title">Profile</h1>
                <p class="guest-profile-hero__lede">Your account details, notifications, and how this portal looks.</p>
            </header>
        @elseif($usesOwnerShell)
            <header class="owner-page-hero">
                <p class="owner-page-hero__eyebrow">Account</p>
                <h1 class="owner-page-hero__title">Profile</h1>
                <p class="owner-page-hero__lede">
                    @if($canEditTenantLanding)
                        Your account details and notification preferences. Theme and display mode are on
                        <a href="{{ route('owner.landing.edit', [], false) }}">Landing &amp; logo</a>.
                    @else
                        Your account details and notification preferences.
                    @endif
                </p>
            </header>
        @else
            <div class="page-header">
                <h1>
                    <span class="page-title-icon"><i class="fa-solid fa-user-gear"></i></span>
                    <span>Profile</span>
                </h1>
                <p>
                    @if($canEditTenantLanding)
                        Your account details and notification preferences. Theme and display mode are on
                        <a href="{{ route('owner.landing.edit', [], false) }}">Landing &amp; logo</a>.
                    @elseif($onTenantPortal)
                        Your account details and notification preferences.
                    @else
                        Your account details, notifications, and how the portal looks.
                    @endif
                </p>
            </div>
        @endif

        @if(session('status') && str_contains(session('status'), 'profile'))
            <div class="profile-flash success" role="status">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('status') === 'profile-updated' ? 'Profile saved successfully.' : session('status') }}</span>
            </div>
        @endif

        @if(session('status') === 'password-updated')
            <div class="profile-flash success" role="status">
                <i class="fa-solid fa-circle-check"></i>
                <span>Password updated successfully.</span>
            </div>
        @endif

        @if(session('success'))
            <div class="profile-flash success" role="status">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any() && ! $errors->updatePassword->any())
            <div class="profile-flash error" role="alert">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-grid">
            <div class="profile-stack">
                <form method="POST" action="/profile" enctype="multipart/form-data" data-loading-form class="profile-stack">
                    @csrf
                    @method('patch')

                    <section class="profile-panel">
                        <div class="profile-panel__head">
                            <h2>Personal information</h2>
                            <p>Name, contact details, and profile photo.</p>
                        </div>
                        <div class="profile-panel__body">
                            <div class="profile-user">
                                @if($u->avatar)
                                    <div class="profile-user__avatar">
                                        <img src="{{ '/storage/avatars/' . $u->avatar }}" alt="{{ $u->name }}"
                                             onerror="this.onerror=null;this.parentElement.innerHTML='{{ strtoupper(substr($u->name, 0, 2)) }}';">
                                    </div>
                                @else
                                    <div class="profile-user__avatar">{{ strtoupper(substr($u->name, 0, 2)) }}</div>
                                @endif
                                <div class="profile-user__body">
                                    <h3 class="profile-user__name">
                                        {{ $u->name }}
                                        <span class="role-chip {{ $u->role }}">
                                            <i class="fa-solid {{ $roleIcons[$u->role] ?? 'fa-user' }}"></i>
                                            {{ $roleLabels[$u->role] ?? ucfirst($u->role) }}
                                        </span>
                                    </h3>
                                    <p class="profile-user__email">{{ $u->email }}</p>
                                    <div class="profile-user__meta">
                                        <span class="meta-pill"><i class="fa-solid fa-calendar-plus"></i> Joined {{ $u->created_at?->format('M Y') ?? '—' }}</span>
                                        @if($u->last_login)
                                            <span class="meta-pill"><i class="fa-solid fa-clock"></i> {{ $u->last_login->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="avatar-upload">
                                @if($u->avatar)
                                    <div class="avatar-upload__preview upload-avatar">
                                        <img src="{{ '/storage/avatars/' . $u->avatar }}" alt="Avatar"
                                             onerror="this.onerror=null;this.parentElement.innerHTML='{{ strtoupper(substr($u->name, 0, 2)) }}';">
                                    </div>
                                @else
                                    <div class="avatar-upload__preview upload-avatar">{{ strtoupper(substr($u->name, 0, 2)) }}</div>
                                @endif
                                <div class="avatar-upload__meta">
                                    <p>Profile photo</p>
                                    <p>JPEG, PNG, or GIF · max 2&nbsp;MB</p>
                                    <label for="avatar" class="btn-upload"><i class="fa-solid fa-camera"></i> Change photo</label>
                                    <input type="file" id="avatar" name="avatar" accept="image/*">
                                </div>
                            </div>

                            <div class="field-row">
                                <div class="field">
                                    <label for="name">Full name <span class="req">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $u->name) }}" required autocomplete="name">
                                </div>
                                <div class="field">
                                    <label for="email">Email <span class="req">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $u->email) }}" required autocomplete="email">
                                </div>
                            </div>

                            <div class="field-row">
                                <div class="field">
                                    <label for="phone">Phone</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $u->phone) }}" placeholder="+63 912 345 6789">
                                </div>
                                <div class="field">
                                    <label for="address">Address</label>
                                    <input type="text" id="address" name="address" value="{{ old('address', $u->address) }}" placeholder="Street, city, province">
                                </div>
                            </div>

                            <div class="field">
                                <label for="bio">Bio</label>
                                <textarea id="bio" name="bio" placeholder="A short note about you…">{{ old('bio', $u->bio) }}</textarea>
                            </div>
                        </div>
                    </section>

                    <section class="profile-panel">
                        <div class="profile-panel__head">
                            <h2>Notifications</h2>
                            <p>Choose which updates you receive.</p>
                        </div>
                        <div class="profile-panel__body">
                            <div class="notify-list">
                                <label class="notify-item">
                                    <span class="notify-item__label">
                                        <span class="notify-item__icon"><i class="fa-solid fa-calendar-check"></i></span>
                                        Booking status updates
                                    </span>
                                    <span class="switch">
                                        <input type="checkbox" name="notify_booking_updates" value="1" {{ old('notify_booking_updates', $notify['booking_updates'] ?? true) ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </span>
                                </label>
                                <label class="notify-item">
                                    <span class="notify-item__label">
                                        <span class="notify-item__icon"><i class="fa-solid fa-comment-dots"></i></span>
                                        New message alerts
                                    </span>
                                    <span class="switch">
                                        <input type="checkbox" name="notify_messages" value="1" {{ old('notify_messages', $notify['messages'] ?? true) ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </span>
                                </label>
                                <label class="notify-item">
                                    <span class="notify-item__label">
                                        <span class="notify-item__icon"><i class="fa-solid fa-bullhorn"></i></span>
                                        Promotions &amp; updates
                                    </span>
                                    <span class="switch">
                                        <input type="checkbox" name="notify_marketing" value="1" {{ old('notify_marketing', $notify['marketing'] ?? false) ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </section>

                    @if($authUser?->showsProfileAppearancePreferences())
                        <section class="profile-panel">
                            <div class="profile-panel__head">
                                <h2>Appearance</h2>
                                <p>Color theme and display mode for this portal.</p>
                            </div>
                            <div class="profile-panel__body">
                                @include('partials.appearance-preferences-fields', ['appearance' => $appearance])
                            </div>
                        </section>
                    @endif

                    <div class="profile-actions">
                        <button type="submit" class="btn-save" data-loading-button data-loading-label="Saving…">
                            <i class="fa-solid fa-check"></i> Save changes
                        </button>
                    </div>
                </form>
            </div>

            <div class="profile-stack">
                <section class="profile-panel">
                    <div class="profile-panel__head">
                        <h2>Password</h2>
                        <p>Use a strong password unique to this account.</p>
                    </div>
                    <div class="profile-panel__body">
                        <form method="POST" action="/password" data-loading-form>
                            @csrf
                            @method('put')

                            <div class="field">
                                <label for="current_password">Current password</label>
                                <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
                                @if ($errors->updatePassword->has('current_password'))
                                    <div class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $errors->updatePassword->first('current_password') }}</div>
                                @endif
                            </div>

                            <div class="field">
                                <label for="password">New password</label>
                                <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="At least 8 characters">
                                @if ($errors->updatePassword->has('password'))
                                    <div class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $errors->updatePassword->first('password') }}</div>
                                @endif
                            </div>

                            <div class="field">
                                <label for="password_confirmation">Confirm password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                                @if ($errors->updatePassword->has('password_confirmation'))
                                    <div class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $errors->updatePassword->first('password_confirmation') }}</div>
                                @endif
                            </div>

                            <button type="submit" class="btn-secondary" data-loading-button data-loading-label="Updating…">
                                <i class="fa-solid fa-key"></i> Update password
                            </button>
                        </form>
                    </div>
                </section>
            </div>

            <div class="profile-grid__danger">
                <section class="profile-panel profile-panel--danger">
                    <div class="profile-panel__head">
                        <h2>Delete account</h2>
                        <p>Permanently remove your account and all data. This cannot be undone.</p>
                    </div>
                    <div class="profile-panel__body">
                        <div class="danger-box">
                            <p>Once deleted, your profile, bookings history, and messages tied to this account are permanently removed.</p>
                            <form method="POST" action="/profile" onsubmit="return confirm('Delete your account permanently? This cannot be undone.');">
                                @csrf
                                @method('delete')
                                <div class="field" style="max-width: 20rem; margin-bottom: 1rem;">
                                    <label for="password_delete">Confirm with password</label>
                                    <input type="password" id="password_delete" name="password" required autocomplete="current-password">
                                </div>
                                <button type="submit" class="btn-danger">
                                    <i class="fa-solid fa-trash"></i> Delete account
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('avatar')?.addEventListener('change', function (e) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (ev) {
                document.querySelectorAll('.profile-user__avatar, .upload-avatar').forEach(function (el) {
                    el.innerHTML = '<img src="' + ev.target.result + '" alt="Preview">';
                });
            };
            reader.readAsDataURL(file);
        });

        document.querySelectorAll('form[data-loading-form]').forEach(function (form) {
            form.addEventListener('submit', function () {
                var button = form.querySelector('[data-loading-button]');
                if (!button) return;
                button.disabled = true;
                var label = button.getAttribute('data-loading-label') || 'Saving…';
                var icon = button.querySelector('i');
                if (icon) {
                    icon.className = 'fa-solid fa-circle-notch fa-spin';
                }
                button.lastChild && button.childNodes.length > 1
                    ? (button.childNodes[button.childNodes.length - 1].textContent = ' ' + label)
                    : (button.textContent = label);
            });
        });

        if (@json($authUser?->showsProfileAppearancePreferences()) && window.ImpaAppearance && typeof window.ImpaAppearance.initProfilePreview === 'function') {
            window.ImpaAppearance.initProfilePreview();
        }
    </script>
</body>
</html>
