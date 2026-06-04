@props([
    'calendarId' => 'ownerCal',
    'availabilityAccommodations' => null,
    'availabilityEventsByAccommodation' => [],
    'headingId' => 'owner-avail-heading',
    'emptyMessage' => 'Add a unit to track availability.',
])

@once
<style>
    .owner-avail-card {
        width: 100%;
        max-width: none;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
        background: var(--app-surface-bg, #fff);
        border: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.08));
        border-radius: 0.75rem;
        padding: clamp(0.85rem, 1.5vw, 1.1rem) clamp(0.9rem, 1.75vw, 1.15rem);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        text-align: left;
    }

    .owner-avail-card__head {
        margin-bottom: clamp(0.55rem, 1vw, 0.75rem);
        flex-shrink: 0;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.06));
    }

    .owner-avail-card__head h3 {
        margin: 0 0 0.2rem;
        font-family: var(--app-font-display, inherit);
        font-size: 0.875rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--ink-900, var(--gray-900, #0f172a));
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .owner-avail-card__head h3 i {
        color: var(--green-primary, #457359);
        font-size: 0.8125rem;
    }

    .owner-avail-card__head p {
        margin: 0;
        font-size: 0.6875rem;
        line-height: 1.45;
        color: var(--gray-500, #6b7280);
    }

    .owner-avail-card .availability-calendar-wrap {
        align-items: stretch;
        width: 100%;
        flex: 1 1 auto;
        min-height: 0;
        justify-content: flex-start;
    }

    .owner-avail-card__empty {
        font-size: 0.8125rem;
        color: var(--gray-500, #6b7280);
        text-align: left;
        padding: 0.75rem 0;
        margin: 0;
    }
</style>
@endonce

<section {{ $attributes->merge(['class' => 'owner-avail-card']) }} aria-labelledby="{{ $headingId }}">
    <div class="owner-avail-card__head">
        <h3 id="{{ $headingId }}">
            <i class="fas fa-calendar-days" aria-hidden="true"></i>
            Room availability
        </h3>
        <p>Pending &amp; confirmed holds shown as booked</p>
    </div>
    @if(($availabilityAccommodations ?? collect())->isNotEmpty())
        @include('partials.availability-calendar', [
            'calendarId' => $calendarId,
            'availabilityAccommodations' => $availabilityAccommodations,
            'availabilityEventsByAccommodation' => $availabilityEventsByAccommodation,
            'compact' => true,
        ])
    @else
        <p class="owner-avail-card__empty">{{ $emptyMessage }}</p>
    @endif
</section>
