        .explore-stays-main {
            width: 100%;
            max-width: none;
            margin: 0;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: clamp(1rem, 2vw, 1.35rem);
        }

        body.explore-portal-page .explore-stays-main.portal-public-main {
            padding: var(--portal-content-below-nav, calc(var(--app-topbar-height, 4rem) + clamp(1.25rem, 2vw, 1.875rem)))
                clamp(1rem, 2.5vw, 2rem)
                clamp(2rem, 4vw, 3rem);
        }

        .explore-stays-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(min-content, 20rem);
            align-items: center;
            gap: clamp(1rem, 2vw, 1.75rem);
            padding-bottom: clamp(0.85rem, 1.5vw, 1.15rem);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        .explore-stays-hero__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin: 0 0 0.35rem;
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
        }

        .explore-stays-hero__title {
            margin: 0 0 0.4rem;
            font-family: var(--app-font-display, inherit);
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 700;
            line-height: 1.15;
            letter-spacing: -0.03em;
            color: var(--gray-900, #0f172a);
        }

        .explore-stays-hero__lede {
            margin: 0;
            max-width: 38rem;
            font-size: 0.9375rem;
            line-height: 1.55;
            color: var(--gray-600, #4b5563);
        }

        .explore-stays-hero__count {
            margin-top: 0.5rem;
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--gray-500, #6b7280);
        }

        @include('partials.partner-logos-strip-rules')

        .explore-stays-hero__logos {
            display: flex;
            justify-content: flex-end;
            max-width: min(100%, 20rem);
        }

        .explore-stays-filters {
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 0.75rem;
            padding: clamp(0.85rem, 1.5vw, 1.1rem);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .explore-stays-filters__grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 0.65rem 0.75rem;
            align-items: end;
        }

        .explore-stays-field label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.625rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--gray-500, #6b7280);
        }

        .explore-stays-field input,
        .explore-stays-field select {
            width: 100%;
            padding: 0.5rem 0.65rem;
            border: 1px solid var(--gray-200, #e5e7eb);
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            color: var(--gray-800, #1f2937);
            background: #fff;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .explore-stays-field input:focus,
        .explore-stays-field select:focus {
            outline: none;
            border-color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--ui-accent-color, #B0436E) 18%, transparent);
        }

        .explore-stays-field--type { grid-column: span 2; }
        .explore-stays-field--min { grid-column: span 2; }
        .explore-stays-field--max { grid-column: span 2; }
        .explore-stays-field--guests { grid-column: span 2; }
        .explore-stays-field--search { grid-column: span 3; }
        .explore-stays-field--submit { grid-column: span 1; }

        .explore-stays-search-btn {
            display: inline-flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.75rem;
            border: none;
            border-radius: 0.5rem;
            background: var(--action-primary-bg, var(--brand-700, #457359));
            color: #fff;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: filter 0.15s ease, background 0.15s ease;
        }

        .explore-stays-search-btn:hover {
            filter: brightness(1.06);
        }

        .explore-stays-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 15.5rem), 1fr));
            gap: clamp(0.85rem, 1.5vw, 1.15rem);
        }

        .explore-stay-card {
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
        }

        .explore-stay-card:hover {
            border-color: color-mix(in srgb, var(--ui-accent-color, #B0436E) 28%, var(--gray-200, #e5e7eb));
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
            transform: translateY(-2px);
        }

        .explore-stay-card__media {
            position: relative;
            display: block;
            aspect-ratio: 4 / 3;
            overflow: hidden;
            background: var(--gray-100, #f3f4f6);
        }

        .explore-stay-card__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }

        .explore-stay-card:hover .explore-stay-card__media img {
            transform: scale(1.03);
        }

        .explore-stay-card__type {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            padding: 0.2rem 0.5rem;
            border-radius: 999px;
            font-size: 0.5625rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.95);
            color: var(--gray-800, #1f2937);
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .explore-stay-card__body {
            padding: 0.75rem 0.85rem 0.85rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1;
        }

        .explore-stay-card__head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .explore-stay-card__title {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            line-height: 1.3;
            color: var(--gray-900, #0f172a);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .explore-stay-card__fav {
            flex-shrink: 0;
            width: 1.75rem;
            height: 1.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 999px;
            background: transparent;
            color: var(--gray-500, #6b7280);
            cursor: pointer;
            text-decoration: none;
            transition: color 0.15s ease, background 0.15s ease;
        }

        .explore-stay-card__fav:hover {
            color: #dc2626;
            background: #fef2f2;
        }

        .explore-stay-card__location {
            margin: 0;
            font-size: 0.75rem;
            color: var(--gray-500, #6b7280);
            display: flex;
            align-items: center;
            gap: 0.3rem;
            line-height: 1.35;
        }

        .explore-stay-card__location i {
            color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
            font-size: 0.65rem;
            flex-shrink: 0;
        }

        .explore-stay-card__meta {
            font-size: 0.6875rem;
            color: var(--gray-500, #6b7280);
        }

        .explore-stay-card__rating {
            font-size: 0.6875rem;
            color: var(--gray-600, #4b5563);
        }

        .explore-stay-card__rating i {
            color: #f59e0b;
            font-size: 0.625rem;
        }

        .explore-stay-card__price {
            margin-top: auto;
            padding-top: 0.35rem;
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--gray-900, #0f172a);
        }

        .explore-stay-card__price span {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--gray-500, #6b7280);
        }

        .explore-stays-empty,
        .explore-stays-pagination {
            display: flex;
            justify-content: center;
        }

        .explore-stays-empty__card {
            max-width: 22rem;
            text-align: center;
            padding: clamp(2rem, 5vw, 3rem) 1.5rem;
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 0.75rem;
        }

        .explore-stays-empty__card i {
            font-size: 2rem;
            color: var(--gray-300, #d1d5db);
            margin-bottom: 0.75rem;
        }

        .explore-stays-empty__card h3 {
            margin: 0 0 0.35rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-900, #0f172a);
        }

        .explore-stays-empty__card p {
            margin: 0;
            font-size: 0.8125rem;
            color: var(--gray-500, #6b7280);
        }

        @media (max-width: 1100px) {
            .explore-stays-hero { grid-template-columns: minmax(0, 1fr) minmax(min-content, 17rem); }
        }

        @media (max-width: 900px) {
            .explore-stays-hero {
                grid-template-columns: 1fr;
                align-items: start;
            }
            .explore-stays-hero__logos {
                justify-content: flex-start;
                max-width: 100%;
            }

            .explore-stays-hero__logos .partner-logos-strip {
                justify-content: flex-start;
                flex-wrap: wrap;
                max-width: 100%;
            }
            .explore-stays-filters__grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .explore-stays-field--type,
            .explore-stays-field--min,
            .explore-stays-field--max,
            .explore-stays-field--guests,
            .explore-stays-field--search,
            .explore-stays-field--submit { grid-column: span 1; }
            .explore-stays-field--search { grid-column: span 2; }
            .explore-stays-field--submit { grid-column: span 2; }
        }

        @media (max-width: 640px) {
            .explore-stays-main {
                gap: 0.75rem;
            }

            .explore-stays-hero {
                gap: 0.5rem;
                padding-bottom: 0.5rem;
            }

            .explore-stays-hero__eyebrow {
                margin-bottom: 0.2rem;
                font-size: 0.625rem;
            }

            .explore-stays-hero__title {
                margin-bottom: 0.25rem;
                font-size: 1.25rem;
            }

            .explore-stays-hero__lede {
                font-size: 0.8125rem;
                line-height: 1.4;
            }

            .explore-stays-hero__count {
                margin-top: 0.35rem;
            }

            .explore-stays-hero__logos {
                display: none;
            }

            .explore-stays-filters {
                padding: 0.65rem;
            }

            .explore-stays-filters__grid { grid-template-columns: 1fr; }
            .explore-stays-field--search,
            .explore-stays-field--submit { grid-column: span 1; }

            .explore-stays-field label {
                margin-bottom: 0.25rem;
            }

            .explore-stays-field input,
            .explore-stays-field select {
                padding: 0.4375rem 0.55rem;
                font-size: 0.75rem;
            }

            .explore-stays-search-btn {
                padding: 0.4375rem 0.65rem;
                font-size: 0.75rem;
            }

            .explore-stays-grid {
                grid-template-columns: 1fr;
                gap: 0.65rem;
            }

            .explore-stay-card {
                flex-direction: row;
                align-items: stretch;
                border-radius: 0.625rem;
            }

            .explore-stay-card:hover {
                transform: none;
            }

            .explore-stay-card__media {
                flex: 0 0 clamp(6.75rem, 32vw, 7.75rem);
                width: clamp(6.75rem, 32vw, 7.75rem);
                aspect-ratio: 1 / 1;
                min-height: 0;
            }

            .explore-stay-card__type {
                top: 0.35rem;
                left: 0.35rem;
                padding: 0.15rem 0.35rem;
                font-size: 0.5rem;
            }

            .explore-stay-card__body {
                min-width: 0;
                padding: 0.5rem 0.6rem 0.55rem;
                gap: 0.2rem;
            }

            .explore-stay-card__title {
                font-size: 0.8125rem;
                -webkit-line-clamp: 2;
            }

            .explore-stay-card__fav {
                width: 1.5rem;
                height: 1.5rem;
                font-size: 0.75rem;
            }

            .explore-stay-card__location {
                font-size: 0.6875rem;
                align-items: flex-start;
            }

            .explore-stay-card__location span {
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .explore-stay-card__meta,
            .explore-stay-card__rating {
                font-size: 0.625rem;
            }

            .explore-stay-card__price {
                padding-top: 0.15rem;
                font-size: 0.8125rem;
            }

            .explore-stay-card__price span {
                font-size: 0.6875rem;
            }
        }

        @media (max-width: 400px) {
            .explore-stays-hero__lede {
                display: none;
            }

            .explore-stay-card__media {
                flex-basis: 6.25rem;
                width: 6.25rem;
            }
        }

.explore-stays-results {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    width: 100%;
    min-height: 0;
}

body.client-nav-page .client-guest-main,
body.client-nav-page .client-guest-main.explore-stays-main {
    max-width: none;
    width: 100%;
    flex: 1 1 auto;
    min-height: calc(100dvh - var(--client-nav-safe-offset, 6.5rem));
    padding-top: var(
        --client-nav-safe-offset,
        calc(var(--app-topbar-height, 4.5rem) + clamp(1.5rem, 2.5vw, 2.5rem))
    ) !important;
    padding-left: clamp(1rem, 2.5vw, 2rem);
    padding-right: clamp(1rem, 2.5vw, 2rem);
    padding-bottom: clamp(1.25rem, 2.5vw, 2rem);
    box-sizing: border-box;
    scroll-margin-top: var(--client-nav-safe-offset, 6.5rem);
}

.explore-stays-empty {
    flex: 1 1 auto;
    align-items: center;
    min-height: min(48vh, calc(100dvh - var(--client-nav-safe-offset, 5.5rem) - 14rem));
}

.explore-stays-pagination {
    margin-top: auto;
    padding-top: clamp(0.75rem, 2vw, 1.25rem);
}
