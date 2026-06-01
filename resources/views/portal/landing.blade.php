<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'IMPASUGONG TOURISM | Impasugong Accommodations'])
    <style>
        @include('partials.central-portal-shell-styles')

        .portal-landing-main {
            width: 100%;
            max-width: none;
            margin: 0 auto;
            box-sizing: border-box;
            display: flex;
            flex: 1;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0;
        }

        body.explore-portal-page .portal-landing-main.portal-public-main {
            padding: var(--portal-content-below-nav, calc(var(--app-topbar-height, 4rem) + clamp(1.25rem, 2vw, 1.875rem)))
                clamp(1rem, 2.5vw, 2rem)
                clamp(2rem, 4vw, 3rem);
        }

        .portal-landing-hero {
            display: flex;
            width: 100%;
            max-width: 44rem;
            margin-inline: auto;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: clamp(1.5rem, 4vw, 2.5rem) clamp(1.25rem, 4vw, 2.5rem);
            text-align: center;
        }

        .portal-landing-hero > * {
            width: 100%;
            max-width: 42rem;
            margin-inline: auto;
            text-align: center;
        }

        .portal-landing-hero .partner-logos-strip {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: min(100%, 42rem);
            margin-bottom: 0.35rem;
            gap: clamp(0.65rem, 2vw, 1.15rem);
        }

        .portal-landing-hero .partner-logos-strip img {
            height: clamp(7rem, 15vw, 13.5rem);
            max-width: min(10.5rem, 32vw);
        }

        @media (max-width: 1100px) {
            .portal-landing-hero .partner-logos-strip img {
                height: clamp(6rem, 12vw, 10.5rem);
                max-width: min(9rem, 28vw);
            }
        }

        @media (max-width: 900px) {
            .portal-landing-hero .partner-logos-strip {
                justify-content: center;
                flex-wrap: wrap;
            }

            .portal-landing-hero .partner-logos-strip img {
                height: clamp(5.25rem, 18vw, 8.5rem);
                max-width: min(7.5rem, 32vw);
            }
        }

        @media (max-width: 480px) {
            .portal-landing-hero .partner-logos-strip img {
                height: clamp(4.5rem, 24vw, 7rem);
                max-width: min(6.5rem, 30vw);
            }
        }

        .portal-landing-hero__eyebrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            width: auto;
            max-width: 100%;
            margin: 0 auto 0.65rem;
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
        }

        .portal-landing-hero__eyebrow i {
            color: var(--ui-accent-strong, var(--accent-pink-strong, #C25C82));
        }

        .portal-landing-hero__title {
            margin: 0 auto 0.75rem;
            font-family: var(--app-font-display, inherit);
            font-size: clamp(1.85rem, 5vw, 3rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.12;
            color: var(--ink-900, #0f172a);
        }

        .portal-landing-hero__title span {
            color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
        }

        .portal-landing-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: auto;
            max-width: 100%;
            margin: 0 auto 1rem;
            padding: 0.55rem 1.15rem;
            border-radius: 999px;
            border: 1px solid var(--ui-accent-border, var(--accent-pink-border, #F0C3D2));
            background: color-mix(in srgb, var(--ui-accent-surface, var(--accent-pink-soft, #F9DEE5)) 65%, #fff);
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--ink-700, #334155);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .portal-landing-badge i {
            color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
        }

        .portal-landing-lede {
            margin: 0 auto 1.75rem;
            max-width: 42rem;
            font-size: clamp(0.9375rem, 2vw, 1.125rem);
            line-height: 1.6;
            color: var(--ink-600, #4b5563);
        }

        .portal-landing-ctas {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            max-width: 42rem;
            margin-inline: auto;
        }

        .portal-landing-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.625rem;
            font-size: 0.9375rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease, filter 0.15s ease, transform 0.05s ease;
        }

        .portal-landing-btn--primary {
            border: 1px solid var(--action-primary-border, transparent);
            background: var(--action-primary-bg, var(--brand-700, #457359));
            color: var(--action-primary-text, #fff);
            box-shadow: 0 4px 14px color-mix(in srgb, var(--action-primary-bg, #457359) 28%, transparent);
        }

        .portal-landing-btn--primary:hover {
            background: var(--action-primary-hover, var(--brand-800, #34543f));
            filter: brightness(1.03);
        }

        .portal-landing-btn--secondary {
            border: 1px solid var(--ui-accent-border, var(--accent-pink-border, #F0C3D2));
            background: #fff;
            color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
        }

        .portal-landing-btn--secondary:hover {
            background: var(--ui-accent-surface, var(--accent-pink-soft, #F9DEE5));
            border-color: var(--ui-accent-strong, var(--accent-pink-strong, #C25C82));
        }

        html.dark .portal-landing-badge {
            background: var(--app-surface-bg, rgba(30, 41, 59, 0.92));
            color: var(--text-secondary, var(--ink-700));
        }

        html.dark .portal-landing-btn--secondary {
            background: var(--app-surface-bg, rgba(30, 41, 59, 0.92));
            color: var(--ui-accent-color, var(--accent-pink-deep, #F9DEE5));
        }

        @media (max-width: 767px) {
            body.explore-portal-page .portal-landing-main.portal-public-main {
                padding-top: var(--portal-content-below-nav, calc(var(--app-topbar-height-mobile, 5.75rem) + clamp(1rem, 2vw, 1.5rem)));
            }
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
<body class="explore-portal-page portal-landing-page flex min-h-screen flex-col font-sans antialiased">
    @include('partials.portal-public-nav', [
        'active' => 'home',
        'municipalityName' => $municipalityName,
        'navLayout' => 'minimal',
    ])

    <main class="portal-public-main portal-landing-main flex flex-1 flex-col outline-none">
        <section class="portal-landing-hero" aria-labelledby="portal-landing-heading">
            @include('partials.partner-logos-strip')

            <p class="portal-landing-hero__eyebrow">
                <i class="fas fa-compass" aria-hidden="true"></i>
                Official tourism portal
            </p>

            <h1 id="portal-landing-heading" class="portal-landing-hero__title">
                Find your perfect <span>stay</span>
            </h1>

            <div class="portal-landing-badge">
                <i class="fas fa-home" aria-hidden="true"></i>
                <span>Your gateway to Impasugong accommodations</span>
            </div>

            <p class="portal-landing-lede">
                Discover traveller-inns, Airbnb stays, and daily rentals.
                Book unique accommodations and experience local hospitality.
            </p>

            <div class="portal-landing-ctas">
                <a href="{{ route('portal.accommodations.index') }}" class="portal-landing-btn portal-landing-btn--primary">
                    <i class="fas fa-compass" aria-hidden="true"></i>
                    Explore stays
                </a>
                <a href="{{ route('register.guest') }}" class="portal-landing-btn portal-landing-btn--secondary">
                    <i class="fas fa-user-plus" aria-hidden="true"></i>
                    Create guest account
                </a>
            </div>
        </section>
    </main>

    @include('partials.portal-public-footer')
</body>
</html>
