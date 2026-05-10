<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.tenant-favicon')
    <title>Profile Settings - Impasugong Accommodations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @php
            $authUser = auth()->user();
            $isTenantAdminContext = $authUser && $authUser->isAdmin() && \App\Models\Tenant::checkCurrent();
            $useLegacyProfileNav = $authUser && ! $authUser->isOwner() && ! $authUser->isClient() && ! $authUser->isAdmin();
        @endphp

        :root {
            @include('partials.tenant-theme-css-vars')
            --gray-50: #F9FAFB; --gray-100: #F3F4F6; --gray-200: #E5E7EB;
            --gray-300: #D1D5DB; --gray-400: #9CA3AF; --gray-500: #6B7280;
            --gray-600: #4B5563; --gray-700: #374151; --gray-800: #1F2937;
            --gray-900: #111827;
            --emerald-50: #ECFDF5; --emerald-100: #D1FAE5; --emerald-200: #A7F3D0;
            --emerald-300: #6EE7B7; --emerald-500: #10B981; --emerald-600: #059669;
            --emerald-700: #047857; --emerald-800: #065F46; --emerald-900: #064E3B;
            --slate-50: #F8FAFC; --slate-100: #F1F5F9; --slate-200: #E2E8F0;
            --slate-300: #CBD5E1; --slate-400: #94A3B8; --slate-500: #64748B;
            --slate-600: #475569; --slate-700: #334155; --slate-800: #1E293B;
            --rose-50: #FFF1F2; --rose-200: #FECDD3; --rose-700: #BE123C;
            --amber-50: #FFFBEB; --amber-200: #FDE68A; --amber-700: #B45309;
            --indigo-50: #EEF2FF; --indigo-200: #C7D2FE; --indigo-700: #4338CA;
        }

        * { box-sizing: border-box; }

        @if($useLegacyProfileNav)
        .navbar {
            background: var(--white);
            padding: 0 40px;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 20px rgba(27, 94, 32, 0.1);
            position: fixed;
            width: 100%;
            top: 0; left: 0; right: 0;
            z-index: 1000;
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo img { width: 45px; height: 45px; border-radius: 0; border: none; object-fit: contain; }
        .nav-logo span { font-size: 1.2rem; font-weight: 700; color: var(--green-dark); }
        .nav-links { display: flex; gap: 25px; list-style: none; padding: 0; margin: 0; }
        .nav-links a { text-decoration: none; color: var(--gray-600); font-weight: 500; padding: 8px 12px; border-radius: 8px; transition: all 0.2s; }
        .nav-links a:hover, .nav-links a.active { background: var(--green-soft); color: var(--green-dark); }
        .nav-actions { display: flex; gap: 15px; align-items: center; }
        .nav-btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.2s; cursor: pointer; border: none; }
        .nav-btn.primary { background: var(--green-primary); color: var(--white); }
        .nav-btn.primary:hover { background: var(--green-dark); transform: translateY(-1px); }
        @endif

        @if(auth()->user()?->isOwner() || $isTenantAdminContext)
            @include('owner.partials.top-navbar-styles')
        @elseif(auth()->user()?->isClient())
            @include('client.partials.top-navbar-styles')
        @elseif(auth()->user()?->isAdmin())
            @include('admin.partials.top-navbar-styles')
        @endif

        body {
            font-family: var(--client-nav-font, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif);
            background:
                radial-gradient(1200px 600px at -10% -20%, rgba(16,185,129,0.08), transparent 60%),
                radial-gradient(900px 500px at 110% 0%, rgba(5,150,105,0.06), transparent 55%),
                linear-gradient(180deg, #F8FAFC 0%, #F4F8F5 100%);
            min-height: 100vh;
            color: var(--gray-800);
            margin: 0;
        }

        /* Layout */
        .main-container {
            width: min(1200px, 100%);
            margin: 0 auto;
            padding-top: var(--client-nav-offset);
            padding-left: clamp(14px, 2vw, 28px);
            padding-right: clamp(14px, 2vw, 28px);
            padding-bottom: 40px;
            min-height: calc(100vh - var(--client-nav-offset));
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 14px;
            font-size: 0.825rem;
            color: var(--slate-500);
        }
        .breadcrumb a {
            color: var(--emerald-700);
            text-decoration: none;
            font-weight: 500;
        }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb i { font-size: 0.65rem; color: var(--slate-400); }

        /* Page header (compact) */
        /* Canonical page title (matches /admin pages) */
        .page-title {
            display: flex; align-items: flex-start; justify-content: space-between;
            gap: 16px; margin-bottom: 22px; flex-wrap: wrap;
        }
        .page-title h1 {
            display: flex; align-items: center; gap: 0.875rem;
            font-size: 1.75rem;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.2;
            letter-spacing: -0.015em;
            margin: 0;
        }
        .page-title h1 .icon-wrap {
            display: inline-flex; align-items: center; justify-content: center;
            width: 44px; height: 44px;
            border-radius: 0.75rem;
            background: #ECFDF5;
            color: #047857;
            border: 1px solid #D1FAE5;
            font-size: 18px;
            flex-shrink: 0;
        }
        .page-title p {
            color: #64748B;
            font-size: 0.875rem;
            line-height: 1.6;
            margin: 0.5rem 0 0 3.65rem;
        }

        /* Surfaces */
        .surface {
            background: #FFFFFF;
            border: 1px solid var(--slate-200);
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        /* Hero (identity) card */
        .identity-card {
            position: relative;
            padding: 22px;
            margin-bottom: 22px;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(16,185,129,0.06), rgba(5,150,105,0.02)),
                #FFFFFF;
            border: 1px solid var(--emerald-100);
        }
        .identity-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(800px 200px at 0% 0%, rgba(16,185,129,0.10), transparent 60%);
            pointer-events: none;
        }
        .identity-row {
            display: flex; align-items: center; gap: 18px;
            position: relative;
        }
        .identity-avatar {
            width: 84px; height: 84px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--emerald-500), var(--emerald-700));
            color: #FFFFFF;
            font-weight: 700; font-size: 1.6rem; letter-spacing: 0.02em;
            border: 4px solid #FFFFFF;
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.25);
            overflow: hidden;
            flex-shrink: 0;
        }
        .identity-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .identity-info { min-width: 0; flex: 1; }
        .identity-info h2 {
            font-size: 1.25rem; font-weight: 700;
            color: var(--slate-800); margin: 0 0 4px;
            display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
        }
        .identity-info .email {
            color: var(--slate-500); font-size: 0.9rem; margin: 0 0 10px;
            display: flex; align-items: center; gap: 6px;
        }
        .identity-info .email i { color: var(--slate-400); font-size: 0.8rem; }
        .identity-meta {
            display: flex; flex-wrap: wrap; gap: 8px;
            font-size: 0.78rem; color: var(--slate-500);
        }
        .meta-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 10px; border-radius: 999px;
            background: var(--slate-50); color: var(--slate-600);
            border: 1px solid var(--slate-200);
            font-weight: 500;
        }
        .meta-pill i { font-size: 0.7rem; color: var(--slate-400); }

        /* Role chip — aligned to system palette */
        .role-chip {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 10px; border-radius: 999px;
            font-size: 0.7rem; font-weight: 700; letter-spacing: 0.04em;
            text-transform: uppercase;
            border: 1px solid transparent;
        }
        .role-chip i { font-size: 0.65rem; }
        .role-chip.admin {
            background: var(--indigo-50); color: var(--indigo-700);
            border-color: var(--indigo-200);
        }
        .role-chip.owner {
            background: var(--emerald-50); color: var(--emerald-800);
            border-color: var(--emerald-200);
        }
        .role-chip.client {
            background: var(--slate-100); color: var(--slate-700);
            border-color: var(--slate-200);
        }

        /* Settings sections */
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
        }
        .section-card { padding: 24px; }

        .section-header {
            display: flex; align-items: flex-start; gap: 12px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--slate-100);
            margin-bottom: 20px;
        }
        .section-header .ico {
            width: 36px; height: 36px;
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--emerald-50); color: var(--emerald-700);
            border-radius: 10px; border: 1px solid var(--emerald-100);
            font-size: 0.9rem; flex-shrink: 0;
        }
        .section-header.danger .ico {
            background: var(--rose-50); color: var(--rose-700);
            border-color: var(--rose-200);
        }
        .section-header h2 {
            font-size: 1.02rem; font-weight: 700;
            color: var(--slate-800); margin: 0 0 3px;
        }
        .section-header p {
            font-size: 0.85rem; color: var(--slate-500); margin: 0;
        }

        /* Form */
        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: var(--slate-700);
            font-size: 0.82rem;
        }
        .form-group label .req { color: var(--rose-700); }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="password"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--slate-200);
            border-radius: 10px;
            font-size: 0.92rem;
            color: var(--slate-800);
            background: #FFFFFF;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
            font-family: inherit;
        }
        .form-group input::placeholder,
        .form-group textarea::placeholder { color: var(--slate-400); }

        .form-group .field-with-icon {
            position: relative;
            display: block;
        }
        .form-group .field-with-icon .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            font-size: 0.85rem;
            line-height: 1;
            pointer-events: none;
            z-index: 2;
        }
        /* Reserve space on the left for the icon — placed AFTER the input shorthand
           so the inline-start padding is not reset by the `padding: 10px 14px` rule above. */
        .form-group .field-with-icon input[type="text"],
        .form-group .field-with-icon input[type="email"],
        .form-group .field-with-icon input[type="tel"],
        .form-group .field-with-icon input[type="password"] {
            padding-left: 38px;
        }

        .form-group input:hover,
        .form-group textarea:hover,
        .form-group select:hover { border-color: var(--slate-300); }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--emerald-500);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
            background: #FFFFFF;
        }
        .form-group textarea { resize: vertical; min-height: 96px; line-height: 1.5; }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        /* Avatar upload */
        .avatar-upload {
            display: flex; align-items: center; gap: 18px;
            padding: 16px;
            background: var(--slate-50);
            border: 1px dashed var(--slate-200);
            border-radius: 12px;
            margin-bottom: 18px;
        }
        .avatar-upload .upload-avatar {
            width: 72px; height: 72px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--emerald-500), var(--emerald-700));
            color: #FFFFFF; font-weight: 700; font-size: 1.5rem;
            border: 3px solid #FFFFFF;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.18);
            overflow: hidden;
            flex-shrink: 0;
        }
        .avatar-upload .upload-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-upload .upload-meta { flex: 1; min-width: 0; }
        .avatar-upload .upload-meta .upload-title {
            font-size: 0.92rem; font-weight: 600; color: var(--slate-700); margin: 0 0 2px;
        }
        .avatar-upload .upload-meta .upload-hint {
            font-size: 0.78rem; color: var(--slate-500); margin: 0;
        }
        .avatar-upload input[type="file"] { display: none; }
        .avatar-upload-btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 14px;
            background: #FFFFFF;
            color: var(--emerald-700);
            border: 1px solid var(--emerald-200);
            border-radius: 9px;
            font-weight: 600;
            font-size: 0.82rem;
            cursor: pointer;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.1s ease;
            margin-top: 8px;
        }
        .avatar-upload-btn:hover {
            background: var(--emerald-50);
            border-color: var(--emerald-300);
            color: var(--emerald-800);
        }
        .avatar-upload-btn:active { transform: translateY(1px); }
        .avatar-upload-btn i { font-size: 0.78rem; }

        /* Notification toggles */
        .notify-card {
            padding: 14px 16px;
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: 12px;
        }
        .notify-card .notify-label {
            font-size: 0.82rem; font-weight: 600; color: var(--slate-700);
            margin: 0 0 10px;
        }
        .notify-list { display: grid; gap: 8px; }
        .notify-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 12px;
            background: #FFFFFF;
            border: 1px solid var(--slate-200);
            border-radius: 9px;
            font-size: 0.88rem;
            color: var(--slate-700);
            cursor: pointer;
            transition: border-color 0.15s ease, background-color 0.15s ease;
        }
        .notify-item:hover { border-color: var(--slate-300); }
        .notify-item .notify-text {
            display: flex; align-items: center; gap: 10px;
        }
        .notify-item .notify-text .ico {
            width: 28px; height: 28px;
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--emerald-50); color: var(--emerald-700);
            border-radius: 8px; font-size: 0.78rem;
        }
        .notify-item .notify-text .notify-name {
            font-weight: 600; color: var(--slate-700);
        }

        /* Toggle switch */
        .switch {
            position: relative; display: inline-block;
            width: 38px; height: 22px; flex-shrink: 0;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .switch .slider {
            position: absolute; cursor: pointer;
            inset: 0; background: var(--slate-300);
            transition: 0.2s ease; border-radius: 999px;
        }
        .switch .slider::before {
            content: ''; position: absolute;
            height: 16px; width: 16px;
            left: 3px; top: 3px;
            background: #FFFFFF;
            transition: 0.2s ease; border-radius: 999px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.2);
        }
        .switch input:checked + .slider { background: var(--emerald-500); }
        .switch input:checked + .slider::before { transform: translateX(16px); }
        .switch input:focus-visible + .slider {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        }

        /* Buttons */
        .btn-group { display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.1s ease, box-shadow 0.15s ease;
            border: 1px solid transparent;
            font-family: inherit;
            line-height: 1;
        }
        .btn:active { transform: translateY(1px); }
        .btn i { font-size: 0.82rem; }

        .btn-primary {
            background: var(--emerald-600);
            color: #FFFFFF;
            border-color: var(--emerald-600);
            box-shadow: 0 1px 2px rgba(16, 185, 129, 0.25);
        }
        .btn-primary:hover { background: var(--emerald-700); border-color: var(--emerald-700); }

        .btn-secondary {
            background: #FFFFFF;
            color: var(--slate-700);
            border-color: var(--slate-200);
        }
        .btn-secondary:hover { background: var(--slate-50); border-color: var(--slate-300); }

        .btn-danger {
            background: #FFFFFF;
            color: var(--rose-700);
            border-color: var(--rose-200);
        }
        .btn-danger:hover {
            background: var(--rose-50);
            border-color: var(--rose-200);
        }
        .btn-danger.solid {
            background: var(--rose-700);
            color: #FFFFFF;
            border-color: var(--rose-700);
        }
        .btn-danger.solid:hover { background: #9F1239; border-color: #9F1239; }

        /* Field-level error */
        .field-error {
            color: var(--rose-700);
            font-size: 0.78rem;
            margin-top: 6px;
            display: flex; align-items: center; gap: 6px;
        }
        .field-error i { font-size: 0.72rem; }

        /* Messages */
        .message {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-weight: 500;
            font-size: 0.88rem;
            border: 1px solid transparent;
        }
        .message i { margin-top: 2px; flex-shrink: 0; }
        .message.success {
            background: var(--emerald-50); color: var(--emerald-800);
            border-color: var(--emerald-200);
        }
        .message.error {
            background: var(--rose-50); color: var(--rose-700);
            border-color: var(--rose-200);
        }
        .message ul { margin: 0; padding-left: 18px; }

        /* Danger zone styling */
        .danger-zone {
            background: var(--rose-50);
            border: 1px solid var(--rose-200);
            border-radius: 12px;
            padding: 18px;
            margin-top: 4px;
        }
        .danger-zone .dz-title {
            display: flex; align-items: center; gap: 8px;
            color: var(--rose-700); font-weight: 700; font-size: 0.92rem; margin: 0 0 6px;
        }
        .danger-zone .dz-text {
            color: #7F1D1D; font-size: 0.85rem; margin: 0 0 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            @if($useLegacyProfileNav)
            .navbar { padding: 0 18px; height: 60px; }
            .nav-links { display: none; }
            @endif
            .main-container {
                padding-top: calc(var(--client-nav-offset) - 8px);
                padding-left: 12px;
                padding-right: 12px;
                padding-bottom: 28px;
            }
            .form-row { grid-template-columns: 1fr; }
            .identity-row { flex-direction: column; align-items: flex-start; text-align: left; }
            .identity-avatar { width: 72px; height: 72px; font-size: 1.4rem; }
            .avatar-upload { flex-direction: column; align-items: flex-start; }
            .page-title h1 { font-size: 1.4rem; }
            .page-title p { margin-left: 0; }
            .section-card { padding: 18px; }
        }

        body.owner-nav-page .main-container.with-owner-nav {
            padding-top: 100px;
        }

        @media (min-width: 1100px) {
            .settings-grid {
                grid-template-columns: minmax(0, 1.15fr) minmax(0, 0.85fr);
                align-items: start;
            }
            .settings-grid .section-card.full-width {
                grid-column: 1 / -1;
            }
        }
    </style>
</head>
<body class="{{ (auth()->user()?->isOwner() || $isTenantAdminContext) ? 'owner-nav-page' : '' }}">
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
                    <button type="submit" class="nav-btn primary">Logout</button>
                </form>
            </div>
            @endauth
        </nav>
    @endif

    <div class="main-container {{ (auth()->user()?->isOwner() || $isTenantAdminContext) ? 'with-owner-nav' : '' }}">
        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <a href="{{ route('landing') }}"><i class="fa-solid fa-house"></i> Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Profile Settings</span>
        </div>

        {{-- Page title --}}
        <div class="page-title">
            <div>
                <h1>
                    <span class="icon-wrap"><i class="fa-solid fa-user-gear"></i></span>
                    Profile Settings
                </h1>
                <p>Manage your account information, security, and notification preferences.</p>
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('status') && str_contains(session('status'), 'profile'))
            <div class="message success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('status') === 'profile-updated' ? 'Your profile has been updated successfully.' : session('status') }}</span>
            </div>
        @endif

        @if(session('status') === 'password-updated')
            <div class="message success">
                <i class="fa-solid fa-circle-check"></i>
                <span>Your password has been updated successfully.</span>
            </div>
        @endif

        @if(session('success'))
            <div class="message success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any() && ! $errors->updatePassword->any())
            <div class="message error">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Identity hero --}}
        @php
            $u = Auth::user();
            $roleLabels = ['admin' => 'Administrator', 'owner' => 'Property Owner', 'client' => 'Guest'];
            $roleIcons = ['admin' => 'fa-user-shield', 'owner' => 'fa-user-tie', 'client' => 'fa-user'];
        @endphp
        <div class="surface identity-card">
            <div class="identity-row">
                @if($u->avatar)
                    <div class="identity-avatar">
                        <img src="{{ '/storage/avatars/' . $u->avatar }}" alt="{{ $u->name }}"
                             onerror="this.onerror=null;this.parentElement.innerHTML='{{ strtoupper(substr($u->name, 0, 2)) }}';">
                    </div>
                @else
                    <div class="identity-avatar">{{ strtoupper(substr($u->name, 0, 2)) }}</div>
                @endif
                <div class="identity-info">
                    <h2>
                        {{ $u->name }}
                        <span class="role-chip {{ $u->role }}">
                            <i class="fa-solid {{ $roleIcons[$u->role] ?? 'fa-user' }}"></i>
                            {{ $roleLabels[$u->role] ?? ucfirst($u->role) }}
                        </span>
                    </h2>
                    <p class="email"><i class="fa-solid fa-envelope"></i> {{ $u->email }}</p>
                    <div class="identity-meta">
                        <span class="meta-pill">
                            <i class="fa-solid fa-calendar-plus"></i>
                            Joined {{ $u->created_at?->format('M Y') ?? '—' }}
                        </span>
                        @if($u->last_login)
                            <span class="meta-pill">
                                <i class="fa-solid fa-clock"></i>
                                Last login {{ $u->last_login->diffForHumans() }}
                            </span>
                        @endif
                        @if($u->phone)
                            <span class="meta-pill">
                                <i class="fa-solid fa-phone"></i>
                                {{ $u->phone }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="settings-grid">
            {{-- Personal Information --}}
            <div class="surface section-card">
                <div class="section-header">
                    <span class="ico"><i class="fa-solid fa-id-card"></i></span>
                    <div>
                        <h2>Personal Information</h2>
                        <p>Update your personal details and contact information.</p>
                    </div>
                </div>

                <form method="POST" action="/profile" enctype="multipart/form-data" data-loading-form>
                    @csrf
                    @method('patch')

                    <div class="avatar-upload">
                        @if($u->avatar)
                            <div class="upload-avatar">
                                <img src="{{ '/storage/avatars/' . $u->avatar }}" alt="Avatar"
                                     onerror="this.onerror=null;this.parentElement.innerHTML='{{ strtoupper(substr($u->name, 0, 2)) }}';">
                            </div>
                        @else
                            <div class="upload-avatar">{{ strtoupper(substr($u->name, 0, 2)) }}</div>
                        @endif
                        <div class="upload-meta">
                            <p class="upload-title">Profile photo</p>
                            <p class="upload-hint">JPEG, PNG, or GIF. Up to 2&nbsp;MB. Recommended 200×200&nbsp;px.</p>
                            <label for="avatar" class="avatar-upload-btn">
                                <i class="fa-solid fa-camera"></i>
                                Change photo
                            </label>
                            <input type="file" id="avatar" name="avatar" accept="image/*">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full name <span class="req">*</span></label>
                            <div class="field-with-icon">
                                <i class="fa-solid fa-user field-icon"></i>
                                <input type="text" id="name" name="name" value="{{ old('name', $u->name) }}" required autocomplete="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address <span class="req">*</span></label>
                            <div class="field-with-icon">
                                <i class="fa-solid fa-envelope field-icon"></i>
                                <input type="email" id="email" name="email" value="{{ old('email', $u->email) }}" required autocomplete="email">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone number</label>
                            <div class="field-with-icon">
                                <i class="fa-solid fa-phone field-icon"></i>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $u->phone) }}" placeholder="e.g. +63 912 345 6789">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <div class="field-with-icon">
                                <i class="fa-solid fa-location-dot field-icon"></i>
                                <input type="text" id="address" name="address" value="{{ old('address', $u->address) }}" placeholder="Street, city, province">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" placeholder="Tell us a little about yourself…">{{ old('bio', $u->bio) }}</textarea>
                    </div>

                    @php
                        $notify = $u->notification_preferences ?? [];
                    @endphp

                    <div class="notify-card">
                        <p class="notify-label">Notification preferences</p>
                        <div class="notify-list">
                            <label class="notify-item">
                                <span class="notify-text">
                                    <span class="ico"><i class="fa-solid fa-calendar-check"></i></span>
                                    <span>
                                        <span class="notify-name">Booking status updates</span>
                                    </span>
                                </span>
                                <span class="switch">
                                    <input type="checkbox" name="notify_booking_updates" value="1" {{ old('notify_booking_updates', $notify['booking_updates'] ?? true) ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </span>
                            </label>
                            <label class="notify-item">
                                <span class="notify-text">
                                    <span class="ico"><i class="fa-solid fa-comment-dots"></i></span>
                                    <span>
                                        <span class="notify-name">New message alerts</span>
                                    </span>
                                </span>
                                <span class="switch">
                                    <input type="checkbox" name="notify_messages" value="1" {{ old('notify_messages', $notify['messages'] ?? true) ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </span>
                            </label>
                            <label class="notify-item">
                                <span class="notify-text">
                                    <span class="ico"><i class="fa-solid fa-bullhorn"></i></span>
                                    <span>
                                        <span class="notify-name">Promotions and product updates</span>
                                    </span>
                                </span>
                                <span class="switch">
                                    <input type="checkbox" name="notify_marketing" value="1" {{ old('notify_marketing', $notify['marketing'] ?? false) ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" data-loading-button data-loading-label="Saving…">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Security --}}
            <div class="surface section-card">
                <div class="section-header">
                    <span class="ico"><i class="fa-solid fa-shield-halved"></i></span>
                    <div>
                        <h2>Security</h2>
                        <p>Use a strong password unique to this account.</p>
                    </div>
                </div>

                @if (session('status') === 'password-updated')
                    <div class="message success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Password updated successfully.</span>
                    </div>
                @endif

                <form method="POST" action="/password" data-loading-form>
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="current_password">Current password</label>
                        <div class="field-with-icon">
                            <i class="fa-solid fa-lock field-icon"></i>
                            <input type="password" id="current_password" name="current_password" required autocomplete="current-password" placeholder="Your current password">
                        </div>
                        @if ($errors->updatePassword->has('current_password'))
                            <div class="field-error">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                {{ $errors->updatePassword->first('current_password') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="password">New password</label>
                        <div class="field-with-icon">
                            <i class="fa-solid fa-key field-icon"></i>
                            <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="At least 8 characters">
                        </div>
                        @if ($errors->updatePassword->has('password'))
                            <div class="field-error">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                {{ $errors->updatePassword->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm new password</label>
                        <div class="field-with-icon">
                            <i class="fa-solid fa-key field-icon"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter the new password">
                        </div>
                        @if ($errors->updatePassword->has('password_confirmation'))
                            <div class="field-error">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                {{ $errors->updatePassword->first('password_confirmation') }}
                            </div>
                        @endif
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" data-loading-button data-loading-label="Updating…">
                            <i class="fa-solid fa-rotate"></i>
                            Update password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Danger zone --}}
            <div class="surface section-card full-width">
                <div class="section-header danger">
                    <span class="ico"><i class="fa-solid fa-triangle-exclamation"></i></span>
                    <div>
                        <h2>Danger zone</h2>
                        <p>Permanently remove your account and all associated data.</p>
                    </div>
                </div>

                <div class="danger-zone">
                    <p class="dz-title"><i class="fa-solid fa-circle-exclamation"></i> Delete account</p>
                    <p class="dz-text">Once your account is deleted, all of its resources and data will be permanently removed. This action cannot be undone.</p>

                    <form method="POST" action="/profile" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                        @csrf
                        @method('delete')

                        <div class="form-group" style="max-width: 420px;">
                            <label for="password_delete">Confirm with your password</label>
                            <div class="field-with-icon">
                                <i class="fa-solid fa-lock field-icon"></i>
                                <input type="password" id="password_delete" name="password" required autocomplete="current-password" placeholder="Your current password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger solid">
                            <i class="fa-solid fa-trash"></i>
                            Delete account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('avatar')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (ev) {
                document.querySelectorAll('.identity-avatar, .upload-avatar').forEach(function (el) {
                    el.innerHTML = '<img src="' + ev.target.result + '" alt="Preview">';
                });
            };
            reader.readAsDataURL(file);
        });
    </script>
</body>
</html>
