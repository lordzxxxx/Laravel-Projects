{{-- Booking show + payment checkout — compact mobile layout --}}

@media (max-width: 640px) {
    body.client-nav-page main.booking-show-page.client-guest-main,
    body.client-nav-page main.booking-payment-page {
        padding-top: var(
            --client-nav-safe-offset,
            calc(var(--app-topbar-height, 4.5rem) + clamp(0.75rem, 2vw, 1rem))
        ) !important;
    }

    main.booking-show-page.with-owner-nav,
    main.booking-show-page.client-guest-main,
    main.booking-payment-page {
        padding-left: clamp(0.75rem, 3vw, 1rem);
        padding-right: clamp(0.75rem, 3vw, 1rem);
        padding-bottom: 1rem;
    }

    main.booking-show-page:not(.with-owner-nav):not(.client-guest-main) {
        padding-left: clamp(0.75rem, 3vw, 1rem) !important;
        padding-right: clamp(0.75rem, 3vw, 1rem) !important;
        padding-bottom: 1rem !important;
    }

    .booking-show-inner,
    .booking-payment-inner {
        gap: 0.65rem;
    }

    .booking-show-card,
    .booking-payment-card {
        border-radius: 0.625rem;
    }

    .booking-show-card__header,
    .booking-payment-card__header {
        padding: 0.85rem 1rem !important;
    }

    .booking-show-card__header > div,
    .booking-payment-card__header > div {
        gap: 0.65rem !important;
    }

    .booking-show-card__header h1,
    .booking-payment-card__header h1 {
        font-size: 1.125rem !important;
        margin-top: 0.25rem !important;
    }

    .booking-show-card__header .text-sm.text-emerald-100,
    .booking-payment-card__header .text-sm.text-emerald-100 {
        font-size: 0.75rem !important;
        margin-top: 0.25rem !important;
    }

    .booking-show-card__total,
    .booking-payment-card__total {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.5rem 0.75rem !important;
        min-width: 0 !important;
        border-radius: 0.5rem !important;
    }

    .booking-show-card__total p:last-of-type,
    .booking-payment-card__total p:last-of-type {
        font-size: 0.625rem !important;
    }

    .booking-show-card__total .text-2xl,
    .booking-payment-card__total .text-3xl {
        font-size: 1.25rem !important;
        margin-top: 0 !important;
    }

    .booking-show-card__total > p:first-child,
    .booking-payment-card__total > p:first-child {
        font-size: 0.5625rem !important;
    }

    .booking-show-card__aside {
        flex-direction: row !important;
        align-items: stretch;
    }

    .booking-show-card__aside-media {
        position: relative !important;
        width: clamp(6.75rem, 32vw, 7.75rem) !important;
        flex: 0 0 clamp(6.75rem, 32vw, 7.75rem) !important;
        max-height: none !important;
        height: auto !important;
        aspect-ratio: 1 / 1 !important;
    }

    .booking-show-card__aside-body {
        flex: 1 1 0;
        min-width: 0;
        padding: 0.55rem 0.65rem !important;
        gap: 0.25rem !important;
    }

    .booking-show-card__aside-body .text-base {
        font-size: 0.8125rem !important;
    }

    .booking-show-card__aside-body .text-sm {
        font-size: 0.6875rem !important;
    }

    .booking-show-card__aside-body .line-clamp-3 {
        display: none;
    }

    .booking-show-card__content {
        gap: 0.65rem !important;
        padding: 0.65rem !important;
    }

    .booking-show-card__content > section:first-child .grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.45rem;
        margin-top: 0.5rem !important;
    }

    .booking-show-card__content > section:first-child h2 {
        font-size: 0.6875rem;
    }

    .booking-show-panel {
        padding: 0.6rem 0.7rem !important;
        border-radius: 0.5rem;
    }

    .booking-show-panel .text-base {
        font-size: 0.8125rem !important;
    }

    .booking-show-panel dl {
        gap: 0.5rem !important;
        margin-top: 0.65rem !important;
    }

    .booking-show-panel .text-lg {
        font-size: 0.9375rem !important;
    }

    .booking-show-card__actions {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.45rem !important;
        padding-top: 0.65rem !important;
    }

    .booking-show-card__actions .flex.flex-wrap {
        flex-direction: column;
        align-items: stretch;
        gap: 0.45rem;
    }

    .booking-show-card__actions a,
    .booking-show-card__actions button {
        width: 100%;
        justify-content: center;
        padding: 0.55rem 0.85rem !important;
        font-size: 0.8125rem !important;
    }

    .booking-payment-card__summary {
        padding: 0.75rem !important;
    }

    .booking-payment-card__summary h2 {
        font-size: 0.6875rem;
    }

    .booking-payment-card__summary dl {
        margin-top: 0.65rem !important;
        gap: 0.45rem !important;
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .booking-payment-card__summary dl > div {
        padding: 0.55rem 0.65rem !important;
        border-radius: 0.5rem;
    }

    .booking-payment-card__summary dl .text-base {
        font-size: 0.8125rem !important;
    }

    .booking-payment-card__summary .mt-6 {
        margin-top: 0.65rem !important;
        padding: 0.55rem 0.65rem !important;
        font-size: 0.75rem !important;
    }

    .booking-payment-card__summary .pt-8 {
        padding-top: 0.65rem !important;
        font-size: 0.75rem;
    }

    .booking-payment-card__methods {
        gap: 0.65rem !important;
        padding: 0.75rem !important;
    }

    .booking-payment-card__methods > div:first-child h2 {
        font-size: 1rem;
    }

    .booking-payment-card__methods > div:first-child p {
        font-size: 0.75rem;
    }

    .booking-payment-option {
        padding: 0.75rem !important;
        border-radius: 0.625rem !important;
    }

    .booking-payment-option h3 {
        font-size: 0.875rem !important;
    }

    .booking-payment-option .text-sm {
        font-size: 0.6875rem !important;
    }

    .booking-payment-option .h-11.w-11 {
        width: 2.25rem !important;
        height: 2.25rem !important;
        border-radius: 0.5rem !important;
    }

    .booking-payment-option .text-lg {
        font-size: 0.875rem !important;
    }

    .booking-payment-option button,
    .booking-payment-option .inline-flex.w-full {
        width: 100%;
        padding: 0.55rem 0.85rem !important;
        font-size: 0.8125rem !important;
    }

    .booking-payment-qr img {
        height: 7.5rem !important;
        width: 7.5rem !important;
    }

    .booking-payment-qr {
        padding: 0.5rem !important;
        border-radius: 0.625rem !important;
    }

    .booking-payment-proof-form {
        padding: 0.65rem !important;
    }

    .booking-payment-proof-form .flex.flex-col {
        gap: 0.45rem !important;
    }

    .booking-payment-card__footer {
        flex-direction: column;
        align-items: stretch;
        gap: 0.45rem !important;
        padding-top: 0.65rem !important;
    }

    .booking-payment-card__footer a {
        width: 100%;
        justify-content: center;
        padding: 0.55rem 0.85rem !important;
        font-size: 0.8125rem !important;
    }

    main.booking-show-page .inline-flex.w-max.items-center.gap-2,
    main.booking-payment-page .inline-flex.w-max.items-center.gap-2 {
        font-size: 0.75rem;
    }
}

@media (max-width: 400px) {
    .booking-show-card__aside-media {
        width: 6.25rem !important;
        flex-basis: 6.25rem !important;
    }

    .booking-show-card__header .mt-3.flex.flex-wrap {
        gap: 0.35rem !important;
    }

    .booking-show-card__header .mt-3.flex.flex-wrap span {
        font-size: 0.5625rem !important;
        padding: 0.15rem 0.45rem !important;
    }

    .booking-payment-card__summary dl {
        grid-template-columns: 1fr;
    }

    .booking-payment-qr img {
        height: 6.5rem !important;
        width: 6.5rem !important;
    }
}
