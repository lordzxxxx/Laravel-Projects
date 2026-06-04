<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>{{ $tenant->name }} | Accommodations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.appearance-boot')
    <style>
        @include('partials.ui-foundation-styles')
        @include('partials.app-typography-styles')
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            /*
             * Default tenant palette: match Love Impasugong / system brand greens.
             * Tenant-specific overrides (from $settings) still take precedence.
             */
            --green-dark: {{ $settings['primary_color'] ?? 'var(--brand-800, #34543F)' }};
            --green-primary: {{ $settings['accent_color'] ?? 'var(--brand-600, #457359)' }};
            --green-medium: color-mix(in srgb, var(--green-primary) 82%, #ffffff);
            --green-light: color-mix(in srgb, var(--green-primary) 70%, #ffffff);
            --green-pale: color-mix(in srgb, var(--green-primary) 45%, #ffffff);
            --green-soft: color-mix(in srgb, var(--green-primary) 20%, #ffffff);
            --green-white: color-mix(in srgb, var(--green-primary) 10%, #ffffff);
            --white: #FFFFFF;
            --ink-900: var(--ink-900, #0F172A);
            --ink-800: var(--ink-800, #1E293B);
            --ink-700: var(--ink-700, #334155);
            --ink-600: var(--ink-600, #475569);
            --ink-500: var(--ink-500, #64748B);
            --ink-300: var(--ink-300, #CBD5E1);
            --ink-200: var(--ink-200, #E2E8F0);
            --ink-100: var(--ink-100, #F1F5F9);
            --ink-50: var(--ink-50, #F7F9F7);

            --radius-lg: var(--radius-lg, 0.75rem);
            --radius-xl: var(--radius-xl, 1rem);
            --radius-2xl: var(--radius-2xl, 1.5rem);
            --shadow-sm: var(--shadow-sm, 0 1px 2px rgba(15, 23, 42, 0.05));
            --shadow-md: var(--shadow-md, 0 1px 2px rgba(15, 23, 42, 0.04), 0 12px 34px -26px rgba(15, 23, 42, 0.22));
        }

        body.tenant-landing-page {
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            color: var(--ink-800);
            background-color: var(--app-page-bg, #f8fafc);
            background-image: var(--communal-bg-overlay-light, linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.95) 0%,
                rgba(255, 255, 255, 0.88) 50%,
                rgba(27, 94, 32, 0.08) 100%
            ), url('/COMMUNAL.jpg'));
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .tenant-landing-main {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding-left: clamp(18px, 3vw, 40px);
            padding-right: clamp(18px, 3vw, 40px);
        }

        .btn {
            padding: 11px 18px;
            font-size: 0.92rem;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            line-height: 1.1;
        }

        .btn-outline {
            background: transparent;
            color: var(--ink-800);
            border: 1px solid rgba(148, 163, 184, 0.75);
        }
        .btn-outline:hover { background: rgba(15, 23, 42, 0.04); border-color: rgba(100, 116, 139, 0.65); }

        .btn-primary {
            background: linear-gradient(135deg, var(--green-dark), var(--green-primary));
            color: var(--white);
            box-shadow: 0 4px 15px color-mix(in srgb, var(--green-primary) 35%, transparent);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 24px -18px rgba(15, 23, 42, 0.35); }

        .hero {
            width: 100%;
            box-sizing: border-box;
            min-height: min(70dvh, calc(100dvh - var(--app-topbar-height, 4rem)));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: var(--portal-content-below-nav, calc(var(--app-topbar-height, 4rem) + 1.5rem))
                clamp(1.25rem, 4vw, 2.5rem)
                72px;
            text-align: center;
        }

        .hero__inner {
            width: 100%;
            max-width: 44rem;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero__logos {
            margin-bottom: clamp(1rem, 2.5vw, 1.5rem);
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.88);
            padding: 10px 18px;
            border-radius: 50px;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 18px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .hero-badge i { color: var(--chrome-active-bg, var(--green-primary)); }

        .hero h1 {
            width: 100%;
            margin: 0 auto 14px;
            font-family: var(--app-font-display);
            font-size: clamp(2rem, 4.2vw, 3.25rem);
            color: var(--ink-900);
            letter-spacing: -1px;
            font-weight: 800;
            text-align: center;
            text-wrap: balance;
        }
        .hero h1 span { color: var(--chrome-active-bg, var(--green-primary)); }

        .hero p {
            width: 100%;
            max-width: 40rem;
            margin: 0 auto 26px;
            font-size: clamp(1rem, 1.25vw, 1.125rem);
            color: var(--ink-600);
            line-height: 1.7;
            text-align: center;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin: 0 auto;
            flex-wrap: wrap;
        }
        .hero-buttons .btn { padding: 12px 20px; font-size: 0.95rem; }

        .carousel-section {
            padding: 76px 0;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-top: 1px solid rgba(226, 232, 240, 0.9);
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        }
        .carousel-header { text-align: center; margin-bottom: 50px; }
        .carousel-header h2 {
            font-family: var(--app-font-display);
            font-size: clamp(1.5rem, 2.2vw, 2.05rem);
            color: var(--ink-900);
            margin-bottom: 10px;
            font-weight: 800;
        }
        .carousel-header p { font-size: 1rem; color: var(--ink-600); }

        .featured-accommodations {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 17.5rem), 1fr));
            gap: clamp(1rem, 2.5vw, 1.5rem);
            align-items: stretch;
            justify-content: center;
            width: 100%;
        }

        .featured-grid__empty {
            grid-column: 1 / -1;
        }

        .property-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 100%;
            background: var(--white);
            border-radius: var(--radius-2xl);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(226, 232, 240, 0.95);
        }
        .property-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 22px 55px -38px rgba(15, 23, 42, 0.45);
        }

        .property-img {
            width: 100%;
            height: clamp(11rem, 28vw, 12.5rem);
            min-height: 11rem;
            object-fit: cover;
            object-position: center;
            background: var(--ink-100, #f1f5f9);
            flex-shrink: 0;
        }

        .property-content {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            padding: clamp(1rem, 2.5vw, 1.375rem);
            min-height: 0;
        }

        .property-type {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            align-self: flex-start;
            background: rgba(15, 23, 42, 0.04);
            color: var(--ink-700);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .property-content h3 {
            font-size: clamp(1rem, 2vw, 1.15rem);
            color: var(--ink-900);
            margin: 0 0 8px;
            font-weight: 800;
            letter-spacing: -0.01em;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
            min-height: 2.6em;
        }

        .property-location {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            color: var(--ink-600);
            font-size: 0.85rem;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .property-features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem 0.875rem;
            margin-bottom: 18px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        }

        .feature {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--ink-700);
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .property-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 0.75rem;
            margin-top: auto;
            padding-top: 0.25rem;
        }

        .property-price {
            font-size: clamp(1.15rem, 2.5vw, 1.4rem);
            font-weight: 700;
            color: var(--green-primary);
            line-height: 1.2;
        }

        .property-price span { font-size: 0.85rem; font-weight: 500; color: var(--ink-500); }

        .property-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            color: var(--ink-600);
            flex-shrink: 0;
        }

        .stars { color: #F59E0B; }

        .about {
            padding: 76px 0 90px;
            background: transparent;
        }

        .about-card {
            max-width: 980px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.88);
            border-radius: var(--radius-2xl);
            padding: 34px 30px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            box-shadow: var(--shadow-md);
            color: var(--ink-800);
        }

        .about-card h2 {
            font-family: var(--app-font-display);
            font-size: clamp(1.35rem, 2vw, 1.85rem);
            margin-bottom: 10px;
            font-weight: 800;
            letter-spacing: -0.015em;
            color: var(--ink-900);
        }

        .about-card p {
            font-size: 1rem;
            line-height: 1.7;
            color: var(--ink-700);
        }

        .footer {
            margin-top: auto;
            width: 100%;
            box-sizing: border-box;
            background: rgba(15, 23, 42, 0.92);
            padding: 8px 14px 10px;
            text-align: center;
            border-top: 1px solid rgba(148, 163, 184, 0.22);
        }
        .footer p { color: #E8F5E9; font-size: 0.72rem; line-height: 1.45; margin: 0.2em 0; }
        .footer p strong { color: #FFFFFF; }
        .footer .footer-impastay { font-size: 0.68rem; color: #DCEDC8; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate { animation: fadeInUp 0.6s ease forwards; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.3s; }

        @media (max-width: 1024px) {
            .hero h1 { font-size: 2.8rem; }
        }

        @media (max-width: 768px) {
            .hero {
                padding-top: var(--portal-content-below-nav, calc(5.75rem + 1rem));
            }
            .hero h1 { font-size: 2rem; }
            .hero p { font-size: 1rem; }
            .hero-buttons { flex-direction: column; align-items: center; }
            .carousel-section, .about { padding: 50px 20px; }
            .carousel-header h2, .about-card h2 { font-size: 1.6rem; }
            .property-features { gap: 0.5rem; }
        }

        @media (max-width: 768px) {
            body { background-attachment: scroll; }
        }
    </style>
</head>
<body class="tenant-landing-page font-sans antialiased text-gray-800">
    @include('partials.tenant-public-nav', [
        'tenant' => $tenant,
        'settings' => $settings,
        'active' => 'landing',
    ])

    <main class="tenant-landing-main">
    <section class="hero" aria-labelledby="tenant-landing-heading">
        <div class="hero__inner">
            <div class="hero__logos animate">
                @include('tenant.partials.auth-brand-logos', ['tenant' => $tenant])
            </div>

            <div class="hero-badge animate">
                <i class="fas fa-store" aria-hidden="true"></i>
                <span>Welcome to {{ $tenant->name }}</span>
            </div>

            <h1 id="tenant-landing-heading" class="animate delay-1">{{ $tenant->name }} <span>Accommodations</span></h1>

            <p class="animate delay-2">{{ $settings['hero_subtitle'] }}</p>

            <div class="hero-buttons animate delay-2">
                <a href="{{ route('portal.accommodations.index') }}" class="btn btn-primary"><i class="fas fa-rocket" aria-hidden="true"></i> {{ $settings['cta_text'] }}</a>
                <a href="#properties" class="btn btn-outline"><i class="fas fa-search" aria-hidden="true"></i> Browse Properties</a>
            </div>
        </div>
    </section>

    <section class="carousel-section" id="properties">
        <div class="container">
        <div class="carousel-header animate">
            <h2><i class="fas fa-star" style="color: var(--green-primary); margin-right: 10px;"></i>Featured Accommodations</h2>
            <p>Curated stays from {{ $tenant->name }}</p>
        </div>

        <div class="featured-accommodations">
            <div class="featured-grid" id="featuredGrid">
                @forelse(($featuredAccommodations ?? collect()) as $accommodation)
                    @php
                        $typeIcon = match ($accommodation->type) {
                            'airbnb' => 'fas fa-home',
                            'daily-rental' => 'fas fa-calendar',
                            default => 'fas fa-bed',
                        };
                        $priceAmount = $accommodation->price_per_night ?: $accommodation->price_per_day;
                        $priceUnit = $accommodation->price_per_night ? 'night' : 'day';
                    @endphp
                    <article class="property-card">
                        <img src="{{ $accommodation->primary_image_url }}" alt="{{ $accommodation->name }}" class="property-img" loading="lazy" decoding="async">
                        <div class="property-content">
                            <span class="property-type"><i class="{{ $typeIcon }}" aria-hidden="true"></i> {{ $accommodation->type_label }}</span>
                            <h3>{{ $accommodation->name }}</h3>
                            <div class="property-location"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Brgy. {{ $accommodation->barangay ?: 'Impasugong' }}</div>
                            <div class="property-features">
                                <span class="feature"><i class="fas fa-bed" aria-hidden="true"></i> {{ (int) ($accommodation->bedrooms ?? 0) }} Beds</span>
                                <span class="feature"><i class="fas fa-bath" aria-hidden="true"></i> {{ (int) ($accommodation->bathrooms ?? 0) }} Baths</span>
                                <span class="feature"><i class="fas fa-users" aria-hidden="true"></i> {{ (int) ($accommodation->max_guests ?? 1) }} Guests</span>
                            </div>
                            <div class="property-footer">
                                <div class="property-price">₱{{ number_format((float) $priceAmount, 0) }} <span>/ {{ $priceUnit }}</span></div>
                                <div class="property-rating">
                                    <span class="stars"><i class="fas fa-star" aria-hidden="true"></i></span>
                                    <span>{{ number_format((float) ($accommodation->rating ?? 0), 1) }} ({{ (int) ($accommodation->total_reviews ?? 0) }})</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="property-card featured-grid__empty" style="padding: 32px; text-align: center;">
                        <h3 style="margin-bottom: 12px;">No Accommodations Yet</h3>
                        <p style="color: var(--green-medium);">This tenant has no published accommodations yet.</p>
                    </article>
                @endforelse
            </div>
        </div>
        </div>
    </section>

    </main>

    <footer class="footer">
        <p><strong>{{ $tenant->name }}</strong> · Hosted on {{ $tenant->domain }}:{{ env('CENTRAL_PORT', 8000) }}</p>
    </footer>

</body>
</html>
