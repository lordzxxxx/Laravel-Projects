{{-- Guest My Bookings — minimal layout (tenant subdomain + central portal client) --}}
body.client-nav-page .guest-bookings-main {
    padding-top: var(
        --client-nav-safe-offset,
        calc(var(--app-topbar-height, 4.5rem) + clamp(1.75rem, 2.5vw, 2.5rem))
    ) !important;
}

.guest-bookings-main {
    display: flex;
    flex-direction: column;
    gap: clamp(0.75rem, 1.25vw, 1.25rem);
    width: 100%;
    max-width: none;
    flex: 1 1 auto;
    min-height: calc(100dvh - var(--client-nav-safe-offset, 6.5rem));
    box-sizing: border-box;
}

.guest-bookings-hero {
    display: flex;
    flex: 0 0 auto;
    width: 100%;
    align-items: flex-end;
    justify-content: space-between;
    gap: clamp(1rem, 2vw, 1.5rem);
    margin-bottom: 0;
    padding-bottom: clamp(0.75rem, 1.25vw, 1.25rem);
    border-bottom: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.08));
}

.guest-bookings-hero__eyebrow {
    margin: 0 0 0.25rem;
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
}

.guest-bookings-hero__title {
    margin: 0 0 0.2rem;
    font-family: var(--app-font-display, inherit);
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -0.03em;
    color: var(--gray-900, #0f172a);
}

.guest-bookings-hero__lede {
    margin: 0;
    max-width: 36rem;
    font-size: 0.9375rem;
    line-height: 1.45;
    color: var(--text-secondary, var(--ink-600, #4b5563));
}

.guest-bookings-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.375rem;
    padding: 0.35rem;
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 0.75rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}

.guest-bookings-filters__tab {
    display: inline-flex;
    align-items: center;
    padding: 0.4375rem 0.875rem;
    font-size: 0.8125rem;
    font-weight: 500;
    line-height: 1.25;
    color: rgba(15, 23, 42, 0.72);
    text-decoration: none;
    border-radius: 0.5rem;
    transition: color 0.15s ease, background-color 0.15s ease;
}

.guest-bookings-filters__tab:hover {
    color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
    background: var(--ui-accent-surface, var(--accent-pink-soft, #F9DEE5));
}

.guest-bookings-filters__tab.is-active {
    color: #fff;
    font-weight: 600;
    background: var(--action-primary-bg, var(--brand-700, #457359));
}

.guest-bookings-results {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    min-height: 0;
    width: 100%;
}

.guest-bookings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 20rem), 1fr));
    gap: clamp(0.85rem, 1.5vw, 1.15rem);
    width: 100%;
    align-content: start;
}

.guest-booking-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
}

.guest-booking-card:hover {
    border-color: color-mix(in srgb, var(--ui-accent-color, #B0436E) 28%, rgba(15, 23, 42, 0.08));
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
    transform: translateY(-2px);
}

.guest-booking-card__meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 0.625rem 0.875rem;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    font-size: 0.6875rem;
    color: var(--gray-500, #6b7280);
}

.guest-booking-card__meta-row strong {
    font-weight: 600;
    color: var(--gray-700, #374151);
}

.guest-booking-card__body {
    display: flex;
    flex: 1;
    flex-direction: column;
    gap: 0.75rem;
    padding: 0.875rem;
}

@media (min-width: 640px) {
    .guest-booking-card__body {
        flex-direction: row;
        gap: 0.875rem;
    }
}

.guest-booking-card__media {
    display: block;
    flex-shrink: 0;
    width: 100%;
    aspect-ratio: 4 / 3;
    border-radius: 0.5rem;
    overflow: hidden;
    background: var(--gray-100, #f3f4f6);
}

@media (min-width: 640px) {
    .guest-booking-card__media {
        width: 7.5rem;
        aspect-ratio: auto;
        height: 5.5rem;
    }
}

.guest-booking-card__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.guest-booking-card__details {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.guest-booking-card__title {
    margin: 0;
    font-size: 0.9375rem;
    font-weight: 600;
    line-height: 1.3;
    color: var(--gray-900, #0f172a);
}

.guest-booking-card__location {
    margin: 0;
    display: flex;
    align-items: flex-start;
    gap: 0.35rem;
    font-size: 0.75rem;
    line-height: 1.4;
    color: var(--gray-500, #6b7280);
}

.guest-booking-card__location i {
    margin-top: 0.15rem;
    color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
    font-size: 0.65rem;
    flex-shrink: 0;
}

.guest-booking-card__facts {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.375rem;
}

.guest-booking-card__fact {
    padding: 0.4375rem 0.5rem;
    border-radius: 0.4375rem;
    background: rgba(46, 125, 50, 0.06);
    border: 1px solid rgba(46, 125, 50, 0.08);
}

.guest-booking-card__fact-label {
    display: block;
    margin-bottom: 0.125rem;
    font-size: 0.5625rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--gray-500, #6b7280);
}

.guest-booking-card__fact-value {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-800, #1f2937);
}

.guest-booking-card__footer {
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
    padding: 0.75rem 0.875rem;
    border-top: 1px solid rgba(15, 23, 42, 0.06);
    background: rgba(248, 250, 252, 0.85);
}

@media (min-width: 480px) {
    .guest-booking-card__footer {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}

.guest-booking-card__badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.375rem;
}

.guest-booking-card__badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.625rem;
    border-radius: 999px;
    font-size: 0.6875rem;
    font-weight: 600;
    line-height: 1.2;
}

.guest-booking-card__badge--pending { background: #fff7ed; color: #9a3412; }
.guest-booking-card__badge--confirmed { background: #ecfdf5; color: #166534; }
.guest-booking-card__badge--cancelled { background: #fef2f2; color: #b91c1c; }
.guest-booking-card__badge--completed { background: #eff6ff; color: #1d4ed8; }
.guest-booking-card__badge--payment-neutral { background: #f3f4f6; color: #374151; }
.guest-booking-card__badge--payment-review { background: #fff7ed; color: #9a3412; }
.guest-booking-card__badge--payment-paid { background: #ecfdf5; color: #166534; }

.guest-booking-card__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.375rem;
    justify-content: flex-end;
}

.guest-booking-card__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    padding: 0.4375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1.2;
    text-decoration: none;
    border-radius: 0.4375rem;
    border: 1px solid transparent;
    cursor: pointer;
    font-family: inherit;
    transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
}

.guest-booking-card__btn--primary {
    color: #fff;
    background: var(--action-primary-bg, var(--brand-700, #457359));
}

.guest-booking-card__btn--primary:hover {
    background: var(--action-primary-hover, var(--brand-800, #34543f));
}

.guest-booking-card__btn--ghost {
    color: var(--gray-700, #374151);
    background: #fff;
    border-color: rgba(15, 23, 42, 0.12);
}

.guest-booking-card__btn--ghost:hover {
    background: rgba(15, 23, 42, 0.04);
}

.guest-bookings-empty {
    flex: 1 1 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: min(48vh, calc(100dvh - var(--client-nav-safe-offset, 6.5rem) - 14rem));
}

.guest-bookings-empty__card {
    max-width: 22rem;
    width: 100%;
    padding: clamp(2rem, 5vw, 3rem) 1.5rem;
    text-align: center;
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 0.75rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}

.guest-bookings-empty__card i {
    font-size: 2rem;
    color: var(--gray-300, #d1d5db);
    margin-bottom: 0.75rem;
}

.guest-bookings-empty__card h3 {
    margin: 0 0 0.35rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900, #0f172a);
}

.guest-bookings-empty__card p {
    margin: 0 0 1rem;
    font-size: 0.8125rem;
    color: var(--gray-500, #6b7280);
}

.guest-bookings-empty__cta {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #fff;
    text-decoration: none;
    border-radius: 0.5rem;
    background: var(--action-primary-bg, var(--brand-700, #457359));
    transition: background-color 0.15s ease;
}

.guest-bookings-empty__cta:hover {
    background: var(--action-primary-hover, var(--brand-800, #34543f));
}

.guest-bookings-pagination {
    margin-top: auto;
    padding-top: clamp(0.75rem, 2vw, 1.25rem);
    display: flex;
    justify-content: center;
}

@media (max-width: 640px) {
    .guest-bookings-main {
        gap: 0.65rem;
    }

    body.client-nav-page .guest-bookings-main {
        padding-top: var(
            --client-nav-safe-offset,
            calc(var(--app-topbar-height, 4.5rem) + clamp(1rem, 2vw, 1.5rem))
        ) !important;
    }

    .guest-bookings-hero {
        padding-bottom: 0.5rem;
    }

    .guest-bookings-hero__title {
        font-size: 1.25rem;
    }

    .guest-bookings-hero__lede {
        font-size: 0.8125rem;
    }

    .guest-bookings-filters {
        padding: 0.25rem;
        gap: 0.25rem;
    }

    .guest-bookings-filters__tab {
        padding: 0.35rem 0.65rem;
        font-size: 0.6875rem;
    }

    .guest-bookings-grid {
        grid-template-columns: 1fr;
        gap: 0.65rem;
    }

    .guest-booking-card:hover {
        transform: none;
    }

    .guest-booking-card__meta-row {
        padding: 0.45rem 0.6rem;
        font-size: 0.625rem;
    }

    .guest-booking-card__body {
        flex-direction: row;
        align-items: stretch;
        gap: 0.55rem;
        padding: 0.55rem 0.6rem;
    }

    .guest-booking-card__media {
        width: clamp(6.75rem, 32vw, 7.75rem);
        flex: 0 0 clamp(6.75rem, 32vw, 7.75rem);
        aspect-ratio: 1 / 1;
        height: auto;
        border-radius: 0.4375rem;
    }

    .guest-booking-card__details {
        gap: 0.25rem;
    }

    .guest-booking-card__title {
        font-size: 0.8125rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .guest-booking-card__location {
        font-size: 0.6875rem;
    }

    .guest-booking-card__location span {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .guest-booking-card__facts {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.25rem;
    }

    .guest-booking-card__fact {
        padding: 0.3rem 0.35rem;
    }

    .guest-booking-card__fact-label {
        font-size: 0.5rem;
        margin-bottom: 0.05rem;
    }

    .guest-booking-card__fact-value {
        font-size: 0.6875rem;
    }

    .guest-booking-card__footer {
        flex-direction: column;
        align-items: stretch;
        gap: 0.45rem;
        padding: 0.5rem 0.6rem;
    }

    .guest-booking-card__badge {
        padding: 0.2rem 0.5rem;
        font-size: 0.625rem;
    }

    .guest-booking-card__actions {
        justify-content: flex-start;
        gap: 0.3rem;
    }

    .guest-booking-card__btn {
        padding: 0.35rem 0.55rem;
        font-size: 0.6875rem;
    }
}

@media (max-width: 400px) {
    .guest-bookings-hero__lede {
        display: none;
    }

    .guest-booking-card__media {
        width: 6.25rem;
        flex-basis: 6.25rem;
    }
}

.guest-bookings-main .flash-alerts,
.guest-bookings-main > .alert {
    margin: 0;
}
