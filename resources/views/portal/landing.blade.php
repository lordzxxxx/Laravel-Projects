<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'IMPASUGONG TOURISM | Impasugong Accommodations'])
    <style>
        @include('partials.ui-foundation-styles')
        @include('client.partials.guest-shell-styles')

        body.explore-portal-page.portal-landing-page {
            min-height: 100dvh;
            background-color: var(--app-page-bg, #f8fafc);
            background-image: var(--communal-bg-overlay-light);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .portal-landing-main {
            width: 100%;
            max-width: none;
            margin: 0;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: clamp(1rem, 2vw, 1.5rem);
        }

        body.explore-portal-page .portal-landing-main.portal-public-main {
            padding: var(--portal-content-below-nav, calc(var(--app-topbar-height, 4rem) + clamp(1.25rem, 2vw, 1.875rem)))
                clamp(1rem, 2.5vw, 2rem)
                clamp(2rem, 4vw, 3rem);
        }

        .portal-landing-hero {
            display: flex;
            flex: 1;
            min-height: calc(100dvh - var(--app-topbar-height, 4rem) - clamp(2rem, 4vw, 3rem));
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: clamp(2rem, 5vw, 3.5rem) clamp(1.25rem, 4vw, 2.5rem);
            text-align: center;
        }

        .portal-landing-logos {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: clamp(0.85rem, 2.5vw, 1.75rem);
            margin-bottom: clamp(1.25rem, 3vw, 2rem);
        }

        .portal-landing-logos img {
            height: clamp(6.5rem, 18vw, 11rem);
            width: auto;
            max-width: min(11rem, 32vw);
            object-fit: contain;
        }

        .portal-landing-hero h1 {
            margin: 0 0 1rem;
            font-family: var(--app-font-display, inherit);
            font-size: clamp(1.85rem, 5vw, 3.25rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.1;
            color: var(--brand-dark, #1b5e20);
        }

        .portal-landing-hero h1 span {
            color: var(--brand-primary, #2e7d32);
        }

        .portal-landing-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            padding: 0.65rem 1.35rem;
            border-radius: 999px;
            border: 2px solid var(--brand-soft, #e8f5e9);
            background: #fff;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--brand-dark, #1b5e20);
            box-shadow: 0 4px 15px rgba(27, 94, 32, 0.1);
        }

        .portal-landing-badge i {
            color: var(--brand-primary, #2e7d32);
        }

        .portal-landing-lede {
            margin: 0 auto 2rem;
            max-width: 42rem;
            font-size: clamp(1rem, 2vw, 1.2rem);
            line-height: 1.65;
            color: var(--brand-medium, #4b5563);
        }

        .portal-landing-ctas {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 0.85rem;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="explore-portal-page portal-landing-page flex min-h-screen flex-col font-sans text-brand-dark antialiased">
    @include('partials.portal-public-nav', [
        'active' => 'home',
        'municipalityName' => $municipalityName,
        'navLayout' => 'minimal',
    ])

    <main class="portal-public-main portal-landing-main flex flex-1 flex-col outline-none">
        <section class="portal-landing-hero" aria-labelledby="portal-landing-heading">
            <div class="portal-landing-logos" aria-hidden="true">
                <img src="{{ asset('images/love-impasugong-transparent.png') }}" alt="Love Impasugong" decoding="async">
                <img src="{{ asset('SYSTEMLOGO.png') }}" alt="" decoding="async">
                <img src="{{ asset('Lgu Socmed Template-02.png') }}" alt="" decoding="async">
            </div>

            <h1 id="portal-landing-heading">
                Find Your Perfect <span>Stay</span>
            </h1>

            <div class="portal-landing-badge">
                <i class="fas fa-home" aria-hidden="true"></i>
                <span>Your Gateway to Impasugong Accommodations</span>
            </div>

            <p class="portal-landing-lede">
                Discover traveller-inns, Airbnb stays, and daily rentals.
                Book unique accommodations and experience local hospitality.
            </p>

            <div class="portal-landing-ctas">
                <a
                    href="{{ route('portal.accommodations.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-br from-brand-dark to-brand-primary px-8 py-3.5 text-base font-semibold text-white shadow-[0_4px_15px_rgba(46,125,50,0.3)] transition-all hover:-translate-y-0.5 hover:shadow-[0_6px_20px_rgba(46,125,50,0.4)]"
                >
                    <i class="fas fa-compass" aria-hidden="true"></i> Explore stays
                </a>
                <a
                    href="{{ route('register.guest') }}"
                    class="inline-flex items-center gap-2 rounded-lg border-2 border-brand-primary bg-transparent px-8 py-3.5 text-base font-semibold text-brand-dark transition-all hover:bg-brand-primary hover:text-white"
                >
                    <i class="fas fa-user-plus" aria-hidden="true"></i> Create guest account
                </a>
            </div>
        </section>
    </main>

    @include('partials.portal-public-footer')
</body>
</html>
