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
        width: min(100%, 22rem);
        display: flex;
        flex-direction: column;
        background: var(--app-surface-bg, #fff);
        border: 1px solid var(--app-surface-border, var(--gray-200, #e5e7eb));
        border-radius: 0.75rem;
        padding: 0.85rem 1rem 0.75rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        text-align: center;
    }

    .owner-avail-card__head {
        margin-bottom: 0.6rem;
        flex-shrink: 0;
    }

    .owner-avail-card__head h3 {
        margin: 0 0 0.15rem;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--ink-900, var(--gray-900, #0f172a));
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }

    .owner-avail-card__head h3 i {
        color: var(--green-primary, #457359);
        font-size: 0.75rem;
    }

    .owner-avail-card__head p {
        margin: 0;
        font-size: 0.625rem;
        line-height: 1.4;
        color: var(--gray-500, #6b7280);
    }

    .owner-avail-card .availability-calendar-wrap {
        align-items: center;
        width: 100%;
        flex: 1;
        justify-content: center;
    }

    .owner-avail-card__empty {
        font-size: 0.8125rem;
        color: var(--gray-500, #6b7280);
        text-align: center;
        padding: 0.5rem 0;
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
