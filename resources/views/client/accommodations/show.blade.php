@php
    $authUser = auth()->user();
    $currentTenant = \App\Models\Tenant::current();
    $isTenantManager = $authUser && (
        $authUser->isOwner()
        || ($authUser->isAdmin() && $currentTenant && ((int) $authUser->tenant_id === (int) $currentTenant->id || $authUser->tenant_id === null))
    );
    $portalDirectory = $portalDirectory ?? false;
    $showClientNav = $authUser?->isClient() === true;
    $showPortalPublicNav = $portalDirectory && ! auth()->check();
    $showLegacyNav = ! $isTenantManager && ! $showClientNav && ! $showPortalPublicNav;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    @if($showPortalPublicNav)
        @include('partials.central-public-head', ['pageTitle' => $accommodation->name.' | IMPASUGONG TOURISM'])
    @else
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        @include('partials.tenant-favicon')
        <title>{{ $accommodation->name }} - Impasugong Accommodations</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        @if($showPortalPublicNav)
            @include('partials.central-portal-shell-styles')
        @else
            @include('partials.typography-system')
        @endif
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @if(! $showPortalPublicNav)
        :root {
            @include('partials.tenant-theme-css-vars')
            --gray-50: #F9FAFB; --gray-100: #F3F4F6; --gray-200: #E5E7EB;
            --gray-300: #D1D5DB; --gray-400: #9CA3AF; --gray-500: #6B7280;
            --gray-600: #4B5563; --gray-700: #374151; --gray-800: #1F2937;
            --blue-500: #3B82F6;
            --orange-500: #F97316;
        }
        @endif
        
        @if($showLegacyNav)
        /* Legacy fixed nav (guest / non–client) */
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
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo img { width: 45px; height: 45px; border-radius: 0; border: none; object-fit: contain; }
        .nav-logo span { font-size: 1.2rem; font-weight: 700; color: var(--green-dark); }
        .nav-links { display: flex; gap: 25px; list-style: none; }
        .nav-links a { text-decoration: none; color: var(--gray-600); font-weight: 500; padding: 8px 12px; border-radius: 8px; transition: all 0.3s; }
        .nav-links a:hover, .nav-links a.active { background: var(--green-soft); color: var(--green-dark); }
        .nav-actions { display: flex; gap: 15px; align-items: center; }
        @endif

        @if($isTenantManager)
            @include('owner.partials.top-navbar-styles')
        @elseif($showClientNav)
            @include('client.partials.top-navbar-styles')
        @endif

        body.explore-portal-page {
            display: flex;
            flex-direction: column;
        }

        body:not(.client-nav-page):not(.explore-portal-page):not(.owner-nav-page) {
            background: linear-gradient(135deg, var(--green-white) 0%, var(--cream) 50%, var(--green-soft) 100%);
            min-height: 100vh;
            color: var(--gray-800);
        }

        .btn {
            padding: 0.55rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.8125rem;
            text-decoration: none;
            transition: filter 0.15s ease, background 0.15s ease;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
        .btn-primary { background: var(--green-primary, #457359); color: #fff; }
        .btn-primary:hover { filter: brightness(1.06); }
        .btn-secondary { background: var(--green-soft, #e8f0eb); color: var(--green-dark, #14532d); }

        .explore-stay-show {
            width: 100%;
            max-width: none;
            margin: 0;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: clamp(1rem, 2vw, 1.5rem);
            flex: 1;
            --stay-max: var(--app-content-max, 78rem);
        }

        .explore-stay-show__crumb,
        .explore-stay-hero,
        .explore-stay-show__content,
        .explore-stay-show__calendar {
            width: 100%;
            max-width: var(--stay-max);
            margin-left: auto;
            margin-right: auto;
        }

        body.explore-portal-page .explore-stay-show.portal-public-main,
        body.explore-portal-page .explore-stay-show.main-container {
            padding: var(--portal-content-below-nav, calc(var(--app-topbar-height, 84px) + clamp(1.25rem, 2vw, 1.875rem)))
                clamp(1rem, 2.5vw, 2rem)
                clamp(2rem, 4vw, 3rem);
        }

        body.client-nav-page .explore-stay-show.client-guest-main {
            padding-left: clamp(1rem, 2.5vw, 2rem);
            padding-right: clamp(1rem, 2.5vw, 2rem);
            padding-bottom: clamp(2rem, 4vw, 3rem);
        }

        .main-container.explore-stay-show:not(.portal-public-main):not(.client-guest-main) {
            max-width: none;
            padding-top: var(--client-nav-offset, var(--app-main-top-offset, 100px));
            padding-left: clamp(1rem, 2.5vw, 2rem);
            padding-right: clamp(1rem, 2.5vw, 2rem);
            padding-bottom: clamp(2rem, 4vw, 3rem);
        }

        .explore-stay-show__crumb {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.35rem 0.5rem;
            font-size: 0.75rem;
            color: var(--gray-500, #6b7280);
        }

        .explore-stay-show__crumb a {
            color: var(--green-primary, #457359);
            font-weight: 600;
            text-decoration: none;
        }

        .explore-stay-show__crumb a:hover { text-decoration: underline; }

        .explore-stay-show__crumb [aria-current="page"] {
            color: var(--gray-800, #1f2937);
            font-weight: 600;
        }

        .explore-stay-panel {
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 0.75rem;
            padding: clamp(1rem, 2vw, 1.25rem);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .explore-stay-show__gallery {
            width: 100%;
        }

        .explore-stay-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(20rem, 22rem);
            gap: clamp(1rem, 2.5vw, 1.75rem);
            align-items: start;
            width: 100%;
        }

        .explore-stay-hero__main {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: clamp(0.85rem, 2vw, 1.25rem);
        }

        .explore-stay-show__intro {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .explore-stay-show__book-wrap {
            width: 100%;
            max-width: 22rem;
            justify-self: end;
            position: sticky;
            top: calc(var(--app-topbar-height, 84px) + 1rem);
        }

        @media (max-width: 900px) {
            .explore-stay-hero {
                grid-template-columns: 1fr;
            }

            .explore-stay-show__book-wrap {
                position: static;
                max-width: none;
            }
        }

        .explore-stay-show__content {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: clamp(0.75rem, 1.5vw, 1rem);
        }

        .explore-stay-show__panels {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: clamp(0.75rem, 1.5vw, 1rem);
            width: 100%;
        }

        @media (min-width: 900px) {
            .explore-stay-show__panels--split {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .explore-stay-show__panels--split > :first-child {
                grid-column: 1 / -1;
            }
        }

        .explore-stay-book-card:has(.booking-form) {
            max-height: calc(100dvh - var(--app-topbar-height, 84px) - 2rem);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .gallery-container { margin-bottom: 0; }
        .gallery-carousel {
            position: relative;
        }
        .carousel-main-wrap {
            position: relative;
            width: 100%;
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: var(--gray-100, #f3f4f6);
        }
        .main-image {
            width: 100%;
            height: clamp(280px, min(52vh, 42vw), 560px);
            border-radius: 0;
            object-fit: cover;
            cursor: zoom-in;
            display: block;
        }
        .main-image:focus {
            outline: 3px solid var(--green-primary);
            outline-offset: 2px;
        }
        .carousel-hint {
            position: absolute;
            bottom: 0.65rem;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.72);
            color: #fff;
            font-size: 0.6875rem;
            font-weight: 600;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            pointer-events: none;
        }
        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: rgba(255, 255, 255, 0.95);
            color: var(--green-dark, #14532d);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.1);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: background 0.15s ease, opacity 0.15s ease;
        }
        .carousel-btn.prev { left: 12px; }
        .carousel-btn.next { right: 12px; }
        .carousel-btn:hover:not(:disabled) {
            background: var(--green-soft);
            transform: translateY(-50%) scale(1.05);
        }
        .carousel-btn:disabled {
            opacity: 0.35;
            cursor: not-allowed;
            transform: translateY(-50%);
        }
        .carousel-counter {
            position: absolute;
            top: 14px;
            right: 14px;
            background: rgba(255, 255, 255, 0.92);
            color: var(--green-dark);
            font-size: 0.82rem;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 999px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            pointer-events: none;
        }
        .thumbnail-row {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
            overflow-x: auto;
            padding-bottom: 0.25rem;
            scroll-snap-type: x mandatory;
        }
        .thumbnail {
            width: 5.5rem;
            height: 3.75rem;
            border-radius: 0.5rem;
            object-fit: cover;
            cursor: pointer;
            opacity: 0.55;
            transition: opacity 0.15s ease, border-color 0.15s ease;
            border: 2px solid transparent;
            flex-shrink: 0;
            scroll-snap-align: start;
        }
        .thumbnail:hover, .thumbnail.active {
            opacity: 1;
            border-color: var(--green-primary, #457359);
        }

        /* Lightbox */
        .lightbox {
            position: fixed;
            inset: 0;
            z-index: 10000;
            background: rgba(15, 23, 42, 0.92);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 56px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .lightbox.is-open {
            opacity: 1;
            visibility: visible;
        }
        .lightbox-img {
            max-width: 100%;
            max-height: calc(100vh - 96px);
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.45);
        }
        .lightbox-close {
            position: absolute;
            top: 16px;
            right: 20px;
            width: 44px;
            height: 44px;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            transition: background 0.2s;
        }
        .lightbox-close:hover { background: rgba(255, 255, 255, 0.28); }
        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 48px;
            height: 48px;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .lightbox-nav:hover { background: rgba(255, 255, 255, 0.3); }
        .lightbox-nav.prev { left: 16px; }
        .lightbox-nav.next { right: 16px; }
        .lightbox-caption {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.9rem;
            text-align: center;
            max-width: 90%;
        }
        
        .content-grid { display: contents; }

        .info-card {
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 0.75rem;
            padding: clamp(1rem, 2vw, 1.25rem);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            margin-bottom: 0;
        }

        .explore-stay-panel.info-card { margin-bottom: 0; }

        .property-header { margin-bottom: 0.75rem; }
        .property-header h1 {
            margin: 0.35rem 0 0.4rem;
            font-family: var(--app-font-display, inherit);
            font-size: clamp(1.35rem, 2.5vw, 1.75rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--gray-900, #0f172a);
            line-height: 1.2;
        }
        .property-location {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            color: var(--gray-500, #6b7280);
            font-size: 0.8125rem;
            margin-bottom: 0.35rem;
        }
        .property-location i { color: var(--green-primary, #457359); font-size: 0.75rem; }
        .rating { display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap; font-size: 0.75rem; }
        .rating-stars { color: #f59e0b; font-size: 0.7rem; }
        .rating-value { font-weight: 600; color: var(--gray-800, #1f2937); }
        .rating-count { color: var(--gray-500, #6b7280); }

        .type-badge {
            display: inline-block;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-size: 0.625rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            background: rgba(69, 115, 89, 0.12);
            color: var(--green-primary, #457359);
            border: 1px solid rgba(69, 115, 89, 0.2);
        }

        .section-title {
            margin: 0 0 0.65rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--gray-800, #1f2937);
        }

        .section-title i { margin-right: 0.35rem; color: var(--green-primary, #457359); }

        .explore-stay-panel__lede {
            margin: 0 0 0.85rem;
            font-size: 0.875rem;
            line-height: 1.6;
            color: var(--gray-600, #4b5563);
        }

        .description {
            color: var(--gray-600, #4b5563);
            line-height: 1.6;
            font-size: 0.875rem;
            margin-bottom: 0.85rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.5rem;
            margin-bottom: 0;
        }
        .feature-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            padding: 0.65rem 0.75rem;
            background: var(--gray-50, #f9fafb);
            border: 1px solid var(--gray-100, #f3f4f6);
            border-radius: 0.5rem;
            text-align: center;
        }
        .feature-icon i { font-size: 1rem; color: var(--green-primary, #457359); }
        .feature-text h4 {
            margin: 0;
            font-size: 0.625rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--gray-500, #6b7280);
        }
        .feature-text p { margin: 0; font-size: 0.9375rem; font-weight: 700; color: var(--gray-900, #0f172a); }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 9.5rem), 1fr));
            gap: 0.4rem;
            margin-bottom: 0;
        }
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.55rem;
            background: var(--gray-50, #f9fafb);
            border: 1px solid var(--gray-100, #f3f4f6);
            border-radius: 0.5rem;
            font-size: 0.75rem;
            color: var(--gray-700, #374151);
        }
        .amenity-item span.check-icon { color: var(--green-primary, #457359); font-size: 0.65rem; }

        .booking-card,
        .explore-stay-book-card {
            width: 100%;
            max-width: 20rem;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 0.75rem;
            padding: 0.9rem 1rem;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
            position: sticky;
            top: calc(var(--app-topbar-height, 84px) + 0.75rem);
        }

        .price-display {
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--gray-100, #f3f4f6);
        }
        .price-display .amount {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--gray-900, #0f172a);
            letter-spacing: -0.02em;
        }
        .price-display .period { color: var(--gray-500, #6b7280); font-size: 0.75rem; }

        .booking-form .form-group { margin-bottom: 0.65rem; }
        .booking-form label {
            display: block;
            margin-bottom: 0.3rem;
            font-weight: 600;
            color: var(--gray-600, #4b5563);
            font-size: 0.6875rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .booking-form input,
        .booking-form select,
        .booking-form textarea {
            width: 100%;
            padding: 0.5rem 0.65rem;
            border: 1px solid var(--gray-200, #e5e7eb);
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .booking-form input:focus,
        .booking-form select:focus,
        .booking-form textarea:focus {
            outline: none;
            border-color: var(--green-primary, #457359);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--green-primary, #457359) 18%, transparent);
        }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; }

        .btn-book { width: 100%; padding: 0.65rem; font-size: 0.875rem; margin-bottom: 0.5rem; }
        .btn-wishlist {
            width: 100%;
            background: transparent;
            border: 1px solid var(--gray-200, #e5e7eb);
            color: var(--gray-600, #4b5563);
        }
        .btn-wishlist:hover { border-color: #fca5a5; color: #dc2626; }

        .explore-stay-book__notice {
            text-align: center;
            padding: 0.75rem 0;
            color: var(--gray-600, #4b5563);
            font-size: 0.8125rem;
            font-weight: 500;
            line-height: 1.45;
        }

        .explore-stay-book__guest-cta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            text-align: center;
        }

        .explore-stay-book__guest-cta p {
            margin: 0 0 0.25rem;
            font-size: 0.8125rem;
            color: var(--gray-500, #6b7280);
        }

        .host-info {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding-top: 0.85rem;
            margin-top: 0.85rem;
            border-top: 1px solid var(--gray-100, #f3f4f6);
        }
        .host-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: var(--green-primary, #457359);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .host-details h4 { margin: 0 0 0.1rem; font-size: 0.8125rem; color: var(--gray-900, #0f172a); }
        .host-details p { margin: 0; color: var(--gray-500, #6b7280); font-size: 0.6875rem; }

        .rules-list { list-style: none; margin: 0; padding: 0; }
        .rules-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 0.45rem 0;
            border-bottom: 1px solid var(--gray-100, #f3f4f6);
            font-size: 0.8125rem;
            color: var(--gray-600, #4b5563);
            line-height: 1.45;
        }
        .rules-list li:last-child { border-bottom: none; }
        .rules-list span { color: var(--green-primary, #457359); }

        .explore-stay-show__calendar {
            margin-top: 0;
        }

        .explore-stay-show__calendar .section-title { margin-bottom: 0.35rem; }

        .explore-stay-show__calendar-note {
            margin: 0 0 0.75rem;
            font-size: 0.8125rem;
            color: var(--gray-500, #6b7280);
            line-height: 1.45;
        }

        @media (max-width: 900px) {
            .explore-stay-show__summary {
                grid-template-columns: 1fr;
            }
            .explore-stay-show__book-wrap {
                max-width: none;
                justify-self: stretch;
            }
            .booking-card,
            .explore-stay-book-card {
                max-width: none;
                position: static;
            }
        }

        @media (max-width: 768px) {
            @if($showLegacyNav)
            .navbar { padding: 0 20px; height: 60px; }
            .nav-links { display: none; }
            @endif
            .explore-stay-show__crumb { font-size: var(--text-fluid-xs); }
            .explore-stay-show h1, .explore-stay-panel h2 { font-size: var(--text-fluid-lg) !important; }
            .explore-stay-panel, .explore-stay-book-card { font-size: var(--text-fluid-sm); padding: var(--app-card-pad); }
            .main-image { height: min(42vw, 200px); }
            .features-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.5rem; font-size: var(--text-fluid-xs); }
            .form-row { grid-template-columns: 1fr; }
            .form-row input, .form-row select, .form-row textarea { font-size: var(--text-fluid-sm); }
            .explore-stay-book-card .price-display { font-size: var(--text-fluid-base) !important; }
        }

        @media (max-width: 480px) {
            .features-grid { grid-template-columns: 1fr; }
            .main-image { height: min(50vw, 180px); }
            .explore-stay-show h1 { font-size: var(--text-fluid-base) !important; }
        }
    </style>
</head>
<body class="{{ $isTenantManager ? 'owner-nav-page' : ($showClientNav ? 'client-nav-page font-sans text-gray-800' : ($showPortalPublicNav ? 'explore-portal-page font-sans text-gray-800' : '')) }}">
    <!-- Navigation -->
    @if($isTenantManager)
    @include('owner.partials.top-navbar', ['active' => 'accommodations'])
    @elseif($showClientNav)
        @include('client.partials.top-navbar', ['active' => 'dashboard', 'portalDirectory' => $portalDirectory ?? false])
    @elseif($showPortalPublicNav)
        @include('partials.portal-public-nav', [
            'active' => 'browse',
            'municipalityName' => config('portals.municipality_name', 'Impasug-ong'),
            'navLayout' => 'minimal',
        ])
    @else
    <nav class="navbar">
        <a href="{{ ($portalDirectory ?? false) ? route('portal.landing') : route('dashboard') }}" class="nav-logo">
            <img src="/SYSTEMLOGO.png" alt="IMPASUGONG TOURISM">
            <span class="nav-brand-text">
                <span class="nav-brand-title">IMPASUGONG TOURISM</span>
                <span class="nav-brand-subtitle">| Impasug-ong stays</span>
            </span>
        </a>
        
        <ul class="nav-links">
            <li><a href="{{ route('dashboard') }}">Browse</a></li>
            <li><a href="{{ ($portalDirectory ?? false) ? route('portal.accommodations.index') : route('dashboard') }}" class="active">Dashboard</a></li>
            @auth
                @if(Auth::user()->role === 'owner')
                    <li><a href="{{ route('owner.dashboard') }}">Dashboard</a></li>
                @elseif(Auth::user()->role === 'admin')
                    <li><a href="{{ \App\Models\Tenant::checkCurrent() ? '/owner/dashboard' : '/admin/dashboard' }}">Dashboard</a></li>
                @elseif(Auth::user()->role !== 'client')
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @endif
                @if(Auth::user()->tenantClientMayManageOwnStays())
                <li><a href="{{ ($portalDirectory ?? false) ? route('portal.bookings.index') : route('bookings.index') }}">My Bookings</a></li>
                @endif
                @if(Auth::user()->tenantClientMayUseMessaging())
                    <li><a href="{{ route('messages.index', [], false) }}">Messages</a></li>
                @endif
                @if(Auth::user()->tenantClientMayEditOwnProfile())
                <li><a href="{{ route('profile.edit') }}">Settings</a></li>
                @endif
            @endauth
        </ul>
        
        @auth
        <div class="nav-actions">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary">Logout</button>
            </form>
        </div>
        @else
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        </div>
        @endauth
    </nav>
    @endif
    
    @php
        $showMainClass = $isTenantManager
            ? 'main-container with-owner-nav explore-stay-show'
            : ($showClientNav
                ? 'client-guest-main client-guest-main--full explore-stay-show'
                : ($showPortalPublicNav
                    ? 'portal-public-main explore-stay-show'
                    : 'main-container explore-stay-show'));
        $listingsUrl = ($portalDirectory ?? false) ? route('portal.accommodations.index') : route('accommodations.index');
        $homeUrl = ($portalDirectory ?? false) ? route('portal.landing') : route('landing');
    @endphp
    <main class="{{ $showMainClass }}">
        <nav class="explore-stay-show__crumb" aria-label="Breadcrumb">
            <a href="{{ $homeUrl }}">{{ ($portalDirectory ?? false) ? 'Explore' : 'Home' }}</a>
            <span aria-hidden="true">/</span>
            <a href="{{ $listingsUrl }}">Accommodations</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">{{ $accommodation->name }}</span>
        </nav>

        <header class="explore-stay-hero">
            <div class="explore-stay-hero__main">
                <div class="explore-stay-show__intro property-header">
                    <span class="type-badge {{ $accommodation->type }}">{{ str_replace('-', ' ', $accommodation->type) }}</span>
                    <h1>{{ $accommodation->name }}</h1>
                    <div class="property-location">
                        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                        {{ $accommodation->address }}@if($accommodation->barangay), Brgy. {{ $accommodation->barangay }}@endif
                    </div>
                    <div class="rating">
                        <span class="rating-stars" aria-hidden="true">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($accommodation->rating))
                                    <i class="fa-solid fa-star"></i>
                                @elseif($i - 0.5 <= $accommodation->rating)
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="rating-value">{{ number_format($accommodation->rating, 1) }}</span>
                        <span class="rating-count">({{ $accommodation->total_reviews }} reviews)</span>
                    </div>
                </div>

                <section class="explore-stay-show__gallery gallery-container" aria-label="Property photos">
                    @php
                        $galleryImages = $accommodation->galleryImageUrls();
                        if (count($galleryImages) === 0) {
                            $galleryImages = [asset('COMMUNAL.jpg')];
                        }
                        $galleryCount = count($galleryImages);
                        $primaryImageUrl = $galleryImages[0];
                    @endphp
                    <script type="application/json" id="accommodation-gallery-data">@json($galleryImages)</script>
                    <div class="gallery-carousel" id="accommodationGallery">
                        @if($galleryCount > 1)
                            <button type="button" class="carousel-btn prev" id="carouselPrev" aria-label="Previous photo">‹</button>
                            <button type="button" class="carousel-btn next" id="carouselNext" aria-label="Next photo">›</button>
                        @endif
                        <div class="carousel-main-wrap">
                            @if($galleryCount > 1)
                                <span class="carousel-counter" id="carouselCounter" aria-live="polite">1 / {{ $galleryCount }}</span>
                            @endif
                            <img src="{{ $galleryImages[0] }}"
                                 alt="{{ $accommodation->name }}"
                                 class="main-image"
                                 id="carouselMain"
                                 data-accommodation-name="{{ e($accommodation->name) }}"
                                 tabindex="0"
                                 role="button"
                                 aria-label="View full size photo. Use arrow keys to change slide when focused.">
                            <span class="carousel-hint">Click for full size</span>
                        </div>
                    </div>
                    @if($galleryCount > 1)
                        <div class="thumbnail-row" id="carouselThumbnails" role="tablist" aria-label="Photo thumbnails">
                            @foreach($galleryImages as $index => $imageUrl)
                                <img src="{{ $imageUrl }}"
                                     alt=""
                                     class="thumbnail {{ $index === 0 ? 'active' : '' }}"
                                     data-index="{{ $index }}"
                                     role="tab"
                                     tabindex="0"
                                     aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                     aria-label="Show photo {{ $index + 1 }} of {{ $galleryCount }}">
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            <aside class="explore-stay-show__book-wrap" aria-label="Book this stay">
                <div class="booking-card explore-stay-book-card">
                    @auth
                        @if(($portalDirectory ?? false) && auth()->user()->isClient() && auth()->user()->tenantClientMayManageOwnStays() && (int) $accommodation->owner_id !== (int) auth()->id())
                            <form method="POST" action="{{ route('portal.wishlist.toggle', $accommodation) }}" class="mb-4">
                                @csrf
                                <button type="submit" class="w-full rounded-xl border-2 border-rose-200 bg-rose-50 py-2.5 text-sm font-semibold text-rose-800 transition hover:bg-rose-100">
                                    <i class="fas fa-heart mr-2"></i> Toggle wishlist
                                </button>
                            </form>
                        @endif
                    @endauth
                    <div class="price-display">
                        <span class="amount">₱{{ number_format($accommodation->price_per_night, 0, '.', ',') }}</span>
                        <span class="period">/ night</span>
                        @if($accommodation->price_per_day)
                            <span class="period" style="margin-left: 10px;">or ₱{{ number_format($accommodation->price_per_day, 0, '.', ',') }}/day</span>
                        @endif
                    </div>
                    
                    @php
                        $authUser = auth()->user();
                        $canBookAccommodation = $authUser && $authUser->isClient() && (int) $accommodation->owner_id !== (int) $authUser->id && $authUser->tenantClientMayManageOwnStays();
                    @endphp

                    @auth
                        @if($canBookAccommodation)
                                <form class="booking-form" method="POST" action="{{ ($portalDirectory ?? false) ? route('portal.bookings.store', $accommodation) : route('accommodations.book', $accommodation) }}" data-loading-form>
                            @csrf
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Check-in</label>
                                    <input type="date" name="check_in_date" min="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Check-out</label>
                                    <input type="date" name="check_out_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Guests</label>
                                <select name="number_of_guests" required>
                                    @for($i = 1; $i <= $accommodation->max_guests; $i++)
                                        <option value="{{ $i }}">{{ $i }} Guest{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Guest Gender (Optional)</label>
                                    <select name="guest_gender">
                                        <option value="">Prefer not to say</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="unspecified">Unspecified</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Guest Age (Optional)</label>
                                    <input type="number" name="guest_age" min="0" max="120" placeholder="e.g. 28">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Guest Location Type (Optional)</label>
                                <select name="guest_is_local" id="guestIsLocalSelect">
                                    <option value="">Select location type</option>
                                    <option value="1">Local</option>
                                    <option value="0">Foreign</option>
                                </select>
                            </div>

                            <div class="form-group" id="guestLocalPlaceGroup" style="display:none;">
                                <label>Local Place (Optional)</label>
                                <input type="text" name="guest_local_place" id="guestLocalPlaceInput" placeholder="e.g. Bukidnon">
                            </div>

                            <div class="form-group" id="guestCountryGroup" style="display:none;">
                                <label>Country (Optional)</label>
                                <input type="text" name="guest_country" id="guestCountryInput" placeholder="e.g. Japan">
                            </div>
                            
                            <div class="form-group">
                                <label>Special Requests (Optional)</label>
                                <textarea name="special_requests" rows="3" placeholder="Any special requests..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-book" data-loading-button>
                                Continue to Payment
                            </button>
                        </form>
                        @elseif($authUser && $authUser->isClient() && (int) $accommodation->owner_id !== (int) $authUser->id)
                            <p class="explore-stay-book__notice">Booking is disabled for your account. Contact the business if you need access.</p>
                        @else
                            <p class="explore-stay-book__notice">Booking is available for client accounts only.</p>
                        @endif

                        <button type="button" class="btn btn-wishlist">
                            <i class="fa-regular fa-heart" aria-hidden="true"></i> Add to Wishlist
                        </button>

                        <div class="host-info">
                            <div class="host-avatar">{{ substr($accommodation->owner->name ?? 'HO', 0, 2) }}</div>
                            <div class="host-details">
                                <h4>{{ $accommodation->owner->name ?? 'Host' }}</h4>
                                <p>Property Owner</p>
                            </div>
                        </div>
                    @else
                        <div class="explore-stay-book__guest-cta">
                            <p>Sign in to book, save to wishlist, or message the host.</p>
                            <a
                                href="{{ $tenantGuestLoginUrl ?? route('login').'?'.http_build_query(['intended' => url()->full()]) }}"
                                class="btn btn-primary btn-book"
                            >Log in</a>
                            <a
                                href="{{ $tenantGuestRegisterUrl ?? (($portalDirectory ?? false) ? route('register.guest') : route('register')) }}"
                                class="btn btn-wishlist"
                            >Create account</a>
                        </div>
                    @endauth
                </div>
            </aside>
        </header>

        <div class="lightbox" id="photoLightbox" role="dialog" aria-modal="true" aria-label="Full size photos">
            <button type="button" class="lightbox-close" id="lightboxClose" aria-label="Close full screen photo">×</button>
            @if($galleryCount > 1)
                <button type="button" class="lightbox-nav prev" id="lightboxPrev" aria-label="Previous photo">‹</button>
                <button type="button" class="lightbox-nav next" id="lightboxNext" aria-label="Next photo">›</button>
            @endif
            <img src="" alt="" class="lightbox-img" id="lightboxImg">
            @if($galleryCount > 1)
                <p class="lightbox-caption" id="lightboxCaption"></p>
            @endif
        </div>

        <div class="explore-stay-show__content content-grid">
            <div class="explore-stay-show__panels explore-stay-show__panels--split">
                <div class="info-card explore-stay-panel">
                    <h3 class="section-title">About this stay</h3>
                    <p class="description">{{ $accommodation->description }}</p>
                    <h3 class="section-title">Property details</h3>
                    <div class="features-grid">
                        <div class="feature-item">
                            <span class="feature-icon"><i class="fa-solid fa-bed" aria-hidden="true"></i></span>
                            <div class="feature-text">
                                <h4>Bedrooms</h4>
                                <p>{{ $accommodation->bedrooms }}</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon"><i class="fa-solid fa-bath" aria-hidden="true"></i></span>
                            <div class="feature-text">
                                <h4>Bathrooms</h4>
                                <p>{{ $accommodation->bathrooms }}</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                            <div class="feature-text">
                                <h4>Max guests</h4>
                                <p>{{ $accommodation->max_guests }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-card explore-stay-panel">
                    <h3 class="section-title">Amenities</h3>
                    <div class="amenities-grid">
                        @if(is_array($accommodation->amenities))
                            @foreach($accommodation->amenities as $amenity)
                                <div class="amenity-item">
                                    <span class="check-icon"><i class="fa-solid fa-check" aria-hidden="true"></i></span>
                                    <span>{{ $amenity }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="info-card explore-stay-panel explore-stay-show__rules-panel">
                    <h3 class="section-title">House rules</h3>
                    <ul class="rules-list">
                        @if($accommodation->house_rules)
                            @foreach(explode('.', $accommodation->house_rules) as $rule)
                                @if(trim($rule))
                                    <li><span>•</span> {{ trim($rule) }}</li>
                                @endif
                            @endforeach
                        @else
                            <li><span>•</span> Standard house rules apply</li>
                            <li><span>•</span> No smoking inside the property</li>
                            <li><span>•</span> Pets allowed with prior notice</li>
                            <li><span>•</span> Check-in: 2PM | Check-out: 11AM</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        @if(isset($availabilityAccommodations) && $availabilityAccommodations->isNotEmpty())
            <section class="info-card explore-stay-panel explore-stay-show__calendar" aria-labelledby="availability-heading">
                <h3 class="section-title" id="availability-heading">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i> Availability
                </h3>
                <p class="explore-stay-show__calendar-note">
                    Green dates are open. Shaded dates have a pending or confirmed booking. Choose check-in and check-out on open nights before you book.
                </p>
                @include('partials.availability-calendar', [
                    'calendarId' => 'guestListingCal',
                    'availabilityAccommodations' => $availabilityAccommodations,
                    'availabilityEventsByAccommodation' => $availabilityEventsByAccommodation ?? [],
                    'compact' => true,
                ])
            </section>
        @endif
    </main>
    
    <script>
        (function () {
            var dataEl = document.getElementById('accommodation-gallery-data');
            if (!dataEl) return;

            var urls = [];
            try {
                urls = JSON.parse(dataEl.textContent || '[]');
            } catch (e) {
                return;
            }
            if (!urls.length) return;

            var main = document.getElementById('carouselMain');
            var prevBtn = document.getElementById('carouselPrev');
            var nextBtn = document.getElementById('carouselNext');
            var counter = document.getElementById('carouselCounter');
            var thumbs = document.querySelectorAll('#carouselThumbnails .thumbnail');
            var lightbox = document.getElementById('photoLightbox');
            var lightboxImg = document.getElementById('lightboxImg');
            var lightboxClose = document.getElementById('lightboxClose');
            var lightboxPrev = document.getElementById('lightboxPrev');
            var lightboxNext = document.getElementById('lightboxNext');
            var lightboxCaption = document.getElementById('lightboxCaption');

            var n = urls.length;
            var i = 0;
            var accName = main ? (main.getAttribute('data-accommodation-name') || 'Photo') : 'Photo';

            var AUTOPLAY_MS = 5000;
            var autoTimer = null;
            var galleryHoverPause = false;

            function clampIndex(x) {
                if (n <= 0) return 0;
                var r = x % n;
                return r < 0 ? r + n : r;
            }

            function showSlide(index) {
                i = clampIndex(index);
                if (main) {
                    main.src = urls[i];
                    main.alt = accName + ' — photo ' + (i + 1) + ' of ' + n;
                }
                if (counter) counter.textContent = (i + 1) + ' / ' + n;
                thumbs.forEach(function (t, idx) {
                    var on = idx === i;
                    t.classList.toggle('active', on);
                    t.setAttribute('aria-selected', on ? 'true' : 'false');
                });
                if (prevBtn) prevBtn.disabled = n <= 1;
                if (nextBtn) nextBtn.disabled = n <= 1;
            }

            function stopAutoplay() {
                if (autoTimer) {
                    clearInterval(autoTimer);
                    autoTimer = null;
                }
            }

            function autoplayShouldAdvance() {
                if (n <= 1) return false;
                if (document.hidden) return false;
                if (lightbox && lightbox.classList.contains('is-open')) return false;
                if (galleryHoverPause) return false;
                return true;
            }

            function startAutoplay() {
                stopAutoplay();
                if (n <= 1) return;
                autoTimer = setInterval(function () {
                    if (autoplayShouldAdvance()) step(1, true);
                }, AUTOPLAY_MS);
            }

            /** @param {boolean} [fromTimer] when true, skip restarting the interval */
            function step(delta, fromTimer) {
                showSlide(i + delta);
                if (lightbox && lightbox.classList.contains('is-open') && lightboxImg) {
                    lightboxImg.src = urls[i];
                    lightboxImg.alt = accName + ' — full size, photo ' + (i + 1) + ' of ' + n;
                    if (lightboxCaption) lightboxCaption.textContent = n > 1 ? 'Photo ' + (i + 1) + ' of ' + n : '';
                }
                if (!fromTimer && n > 1 && (!lightbox || !lightbox.classList.contains('is-open'))) {
                    startAutoplay();
                }
            }

            function openLightbox() {
                if (!lightbox || !lightboxImg) return;
                stopAutoplay();
                lightboxImg.src = urls[i];
                lightboxImg.alt = accName + ' — full size, photo ' + (i + 1) + ' of ' + n;
                if (lightboxCaption) lightboxCaption.textContent = n > 1 ? 'Photo ' + (i + 1) + ' of ' + n : '';
                lightbox.classList.add('is-open');
                document.body.style.overflow = 'hidden';
                if (lightboxClose) lightboxClose.focus();
            }

            function closeLightbox() {
                if (!lightbox) return;
                lightbox.classList.remove('is-open');
                document.body.style.overflow = '';
                if (lightboxImg) lightboxImg.src = '';
                startAutoplay();
            }

            if (prevBtn) prevBtn.addEventListener('click', function () { step(-1); });
            if (nextBtn) nextBtn.addEventListener('click', function () { step(1); });

            thumbs.forEach(function (thumb) {
                thumb.addEventListener('click', function () {
                    var idx = parseInt(thumb.getAttribute('data-index'), 10);
                    if (!isNaN(idx)) {
                        showSlide(idx);
                        if (!lightbox || !lightbox.classList.contains('is-open')) startAutoplay();
                    }
                });
                thumb.addEventListener('keydown', function (ev) {
                    if (ev.key === 'Enter' || ev.key === ' ') {
                        ev.preventDefault();
                        var idx = parseInt(thumb.getAttribute('data-index'), 10);
                        if (!isNaN(idx)) {
                            showSlide(idx);
                            if (!lightbox || !lightbox.classList.contains('is-open')) startAutoplay();
                        }
                    }
                });
            });

            if (main) {
                main.addEventListener('click', openLightbox);
                main.addEventListener('keydown', function (ev) {
                    if (ev.key === 'Enter' || ev.key === ' ') {
                        ev.preventDefault();
                        openLightbox();
                    }
                    if (n > 1 && ev.key === 'ArrowLeft') {
                        ev.preventDefault();
                        step(-1);
                    }
                    if (n > 1 && ev.key === 'ArrowRight') {
                        ev.preventDefault();
                        step(1);
                    }
                });
            }

            if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
            if (lightboxPrev) lightboxPrev.addEventListener('click', function () { step(-1); });
            if (lightboxNext) lightboxNext.addEventListener('click', function () { step(1); });

            if (lightbox) {
                lightbox.addEventListener('click', function (ev) {
                    if (ev.target === lightbox) closeLightbox();
                });
            }

            document.addEventListener('keydown', function (ev) {
                if (!lightbox || !lightbox.classList.contains('is-open')) return;
                if (ev.key === 'Escape') {
                    ev.preventDefault();
                    closeLightbox();
                }
                if (n > 1 && ev.key === 'ArrowLeft') {
                    ev.preventDefault();
                    step(-1, false);
                }
                if (n > 1 && ev.key === 'ArrowRight') {
                    ev.preventDefault();
                    step(1, false);
                }
            });

            document.addEventListener('visibilitychange', function () {
                if (document.hidden) stopAutoplay();
                else if (n > 1 && (!lightbox || !lightbox.classList.contains('is-open'))) startAutoplay();
            });

            var galleryWrap = document.querySelector('.gallery-container');
            if (galleryWrap && n > 1) {
                galleryWrap.addEventListener('mouseenter', function () {
                    galleryHoverPause = true;
                });
                galleryWrap.addEventListener('mouseleave', function () {
                    galleryHoverPause = false;
                });
            }

            /* Touch swipe on main image */
            if (main && n > 1) {
                var touchStartX = null;
                main.addEventListener('touchstart', function (ev) {
                    touchStartX = ev.changedTouches[0].screenX;
                }, { passive: true });
                main.addEventListener('touchend', function (ev) {
                    if (touchStartX === null) return;
                    var dx = ev.changedTouches[0].screenX - touchStartX;
                    touchStartX = null;
                    if (Math.abs(dx) < 50) return;
                    if (dx < 0) step(1);
                    else step(-1);
                }, { passive: true });
            }

            showSlide(0);
            startAutoplay();
        })();

        (function () {
            var locationType = document.getElementById('guestIsLocalSelect');
            var localGroup = document.getElementById('guestLocalPlaceGroup');
            var countryGroup = document.getElementById('guestCountryGroup');
            var localInput = document.getElementById('guestLocalPlaceInput');
            var countryInput = document.getElementById('guestCountryInput');
            if (!locationType || !localGroup || !countryGroup || !localInput || !countryInput) return;

            function syncLocationFields() {
                var value = locationType.value;
                if (value === '1') {
                    localGroup.style.display = '';
                    countryGroup.style.display = 'none';
                    localInput.required = true;
                    countryInput.required = false;
                    countryInput.value = '';
                    return;
                }
                if (value === '0') {
                    localGroup.style.display = 'none';
                    countryGroup.style.display = '';
                    localInput.required = false;
                    countryInput.required = true;
                    localInput.value = '';
                    return;
                }

                localGroup.style.display = 'none';
                countryGroup.style.display = 'none';
                localInput.required = false;
                countryInput.required = false;
                localInput.value = '';
                countryInput.value = '';
            }

            locationType.addEventListener('change', syncLocationFields);
            syncLocationFields();
        })();

        document.querySelectorAll('form[data-loading-form]').forEach(function (form) {
            form.addEventListener('submit', function () {
                var button = form.querySelector('[data-loading-button]');
                if (!button) return;
                button.disabled = true;
                button.textContent = 'Processing...';
            });
        });
    </script>
</body>
</html>
