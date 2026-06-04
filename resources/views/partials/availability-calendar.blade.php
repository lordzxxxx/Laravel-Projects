@props([
    'calendarId' => 'acCal',
    'availabilityAccommodations',
    'availabilityEventsByAccommodation' => [],
    'compact' => false,
])

@once
<style>
    .availability-calendar-wrap {
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .avail-cal-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem 0.75rem;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid var(--gray-200, #e5e7eb);
    }

    .avail-cal-toolbar__unit {
        flex: 1 1 10rem;
        min-width: min(100%, 9rem);
        max-width: 16rem;
    }

    .avail-cal-label {
        display: block;
        margin-bottom: 0.35rem;
        font-size: 0.6875rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--gray-500, #6b7280);
    }

    .availability-calendar-wrap .availability-select {
        width: 100%;
        padding: 0.4rem 0.55rem;
        border-radius: 0.375rem;
        border: 1px solid var(--gray-200, #e5e7eb);
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--gray-800, #1f2937);
        background: var(--app-surface-bg, #fff);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .availability-calendar-wrap .availability-select:focus {
        outline: none;
        border-color: var(--green-primary, #457359);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--green-primary, #457359) 18%, transparent);
    }

    .avail-cal-toolbar__nav {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .availability-calendar-wrap .month-nav-btn {
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 0.375rem;
        border: 1px solid var(--gray-200, #e5e7eb);
        background: #fff;
        color: var(--gray-700, #374151);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: border-color 0.15s ease, background 0.15s ease, color 0.15s ease;
    }

    .availability-calendar-wrap .month-nav-btn:hover {
        border-color: var(--green-primary, #457359);
        background: var(--green-white, #edf4ea);
        color: var(--green-dark, #14532d);
    }

    .availability-month-label {
        min-width: 6.5rem;
        padding: 0 0.15rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.8125rem;
        letter-spacing: -0.01em;
        color: var(--ink-900, var(--gray-900, #0f172a));
    }

    .avail-cal-grid-shell {
        width: 100%;
    }

    .availability-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 0.25rem;
        width: 100%;
        max-width: 18.5rem;
    }

    .availability-dow {
        font-size: 0.5625rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        text-align: center;
        color: var(--gray-400, #9ca3af);
        padding: 0 0 0.2rem;
    }

    .availability-day {
        width: 100%;
        aspect-ratio: 1;
        max-height: 1.65rem;
        min-height: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.3rem;
        font-size: 0.6875rem;
        font-weight: 600;
        color: var(--gray-700, #374151);
        background: #fff;
        border: 1px solid var(--gray-200, #e5e7eb);
        transition: border-color 0.12s ease, background 0.12s ease;
    }

    .availability-day.empty {
        visibility: hidden;
        background: transparent;
        border: none;
        min-height: 0;
    }

    .availability-day:not(.empty):not(.blocked) {
        background: var(--gray-50, #f9fafb);
    }

    .availability-day.blocked {
        background: #fef2f2;
        border-color: #fecaca;
        color: #b91c1c;
    }

    .availability-day.today {
        border-color: var(--green-primary, #457359);
        box-shadow: 0 0 0 1px color-mix(in srgb, var(--green-primary, #457359) 35%, transparent);
        color: var(--green-dark, #14532d);
        font-weight: 700;
    }

    .availability-legend {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem 0.85rem;
        padding-top: 0.1rem;
        color: var(--gray-500, #6b7280);
        font-size: 0.6875rem;
        font-weight: 500;
    }

    /* Compact card variant (owner dashboard) */
    .availability-calendar-wrap.avail-cal--compact {
        gap: 0.55rem;
    }

    .avail-cal--compact .avail-cal-toolbar {
        flex-direction: column;
        align-items: center;
        width: 100%;
        padding-bottom: 0.5rem;
        gap: 0.45rem;
    }

    .avail-cal--compact .avail-cal-toolbar__unit {
        max-width: 100%;
        width: 100%;
        flex: 1 1 auto;
    }

    .avail-cal--compact .avail-cal-label {
        text-align: center;
    }

    .avail-cal--compact .avail-cal-toolbar__nav {
        justify-content: center;
    }

    .avail-cal--compact .avail-cal-grid-shell {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .avail-cal--compact .availability-grid {
        grid-template-columns: repeat(7, 1.5rem);
        justify-content: center;
        gap: 0.2rem;
        max-width: none;
        width: max-content;
        margin-inline: auto;
    }

    .avail-cal--compact .availability-legend {
        justify-content: center;
        width: 100%;
    }

    .avail-cal--compact .availability-day {
        width: 1.5rem;
        height: 1.5rem;
        max-height: 1.5rem;
        aspect-ratio: unset;
        font-size: 0.625rem;
        border-radius: 0.25rem;
    }

    .avail-cal--compact .availability-dow {
        width: 1.5rem;
        font-size: 0.5rem;
    }

    .availability-legend__item {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .availability-dot {
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 999px;
        flex-shrink: 0;
    }

    .availability-dot.available {
        background: var(--gray-300, #d1d5db);
        box-shadow: inset 0 0 0 1px var(--gray-200, #e5e7eb);
    }

    .availability-dot.blocked {
        background: #f87171;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    @media (max-width: 640px) {
        .avail-cal-toolbar__nav {
            margin-left: auto;
        }

        .avail-cal--compact .availability-grid {
            grid-template-columns: repeat(7, minmax(1.35rem, 1fr));
            width: 100%;
            max-width: 16.5rem;
        }

        .avail-cal--compact .availability-day,
        .avail-cal--compact .availability-dow {
            width: auto;
            height: auto;
            max-height: 1.45rem;
            aspect-ratio: 1;
        }
    }

    html.dark .availability-calendar-wrap .availability-select,
    html.dark .availability-calendar-wrap .month-nav-btn {
        background: var(--app-surface-muted-bg) !important;
        border-color: var(--app-surface-border) !important;
        color: var(--ink-800) !important;
    }

    html.dark .availability-month-label,
    html.dark .avail-cal-label {
        color: var(--ink-500) !important;
    }

    html.dark .availability-day:not(.empty):not(.blocked) {
        background: var(--app-surface-muted-bg) !important;
        border-color: var(--app-surface-border) !important;
        color: var(--ink-700) !important;
    }

    html.dark .availability-day.today {
        color: var(--green-dark) !important;
        border-color: var(--green-primary) !important;
    }

    html.dark .availability-day.blocked {
        background: color-mix(in srgb, var(--status-danger) 18%, var(--app-surface-muted-bg)) !important;
        border-color: color-mix(in srgb, var(--status-danger) 35%, transparent) !important;
        color: #fca5a5 !important;
    }

    html.dark .availability-legend {
        color: var(--ink-500) !important;
    }

    html.dark .avail-cal-toolbar {
        border-color: var(--app-surface-border) !important;
    }
</style>
@endonce

<div class="availability-calendar-wrap{{ ($compact ?? false) ? ' avail-cal--compact' : '' }}">
    <div class="avail-cal-toolbar">
        <div class="avail-cal-toolbar__unit">
            <label for="{{ $calendarId }}Accommodation" class="avail-cal-label">Unit</label>
            <select id="{{ $calendarId }}Accommodation"
                    class="availability-select"
                    aria-label="Select unit for availability calendar">
                @foreach($availabilityAccommodations as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }} ({{ str_replace('-', ' ', $unit->type) }})</option>
                @endforeach
            </select>
        </div>
        <div class="avail-cal-toolbar__nav" role="group" aria-label="Change month">
            <button type="button" class="month-nav-btn" id="{{ $calendarId }}Prev" aria-label="Previous month">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            <div class="availability-month-label" id="{{ $calendarId }}MonthLabel" aria-live="polite">Month</div>
            <button type="button" class="month-nav-btn" id="{{ $calendarId }}Next" aria-label="Next month">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <div class="avail-cal-grid-shell">
        <div class="availability-grid" id="{{ $calendarId }}Grid" role="grid" aria-label="Monthly availability"></div>
    </div>

    <div class="availability-legend">
        <span class="availability-legend__item">
            <span class="availability-dot available" aria-hidden="true"></span>
            Available
        </span>
        <span class="availability-legend__item">
            <span class="availability-dot blocked" aria-hidden="true"></span>
            Booked or pending
        </span>
    </div>
</div>

<script>
(function () {
    const selectEl = document.getElementById('{{ $calendarId }}Accommodation');
    const gridEl = document.getElementById('{{ $calendarId }}Grid');
    const monthLabelEl = document.getElementById('{{ $calendarId }}MonthLabel');
    const prevBtn = document.getElementById('{{ $calendarId }}Prev');
    const nextBtn = document.getElementById('{{ $calendarId }}Next');
    if (!selectEl || !gridEl || !monthLabelEl || !prevBtn || !nextBtn) return;

    const eventsByAccommodation = @json($availabilityEventsByAccommodation ?? []);
    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    let currentMonth = new Date();
    currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1);

    const toDateKey = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const blockedSetForAccommodation = (accommodationId) => {
        const rows = eventsByAccommodation[String(accommodationId)] || eventsByAccommodation[accommodationId] || [];
        const blocked = new Set();
        rows.forEach((row) => {
            if (!row.start || !row.end) return;
            const start = new Date(`${row.start}T00:00:00`);
            const end = new Date(`${row.end}T00:00:00`);
            if (Number.isNaN(start.getTime()) || Number.isNaN(end.getTime())) return;
            for (let cursor = new Date(start); cursor <= end; cursor.setDate(cursor.getDate() + 1)) {
                blocked.add(toDateKey(cursor));
            }
        });
        return blocked;
    };

    const renderCalendar = () => {
        const selectedAccommodationId = selectEl.value;
        const blockedSet = blockedSetForAccommodation(selectedAccommodationId);
        const year = currentMonth.getFullYear();
        const month = currentMonth.getMonth();
        const monthStart = new Date(year, month, 1);
        const monthEnd = new Date(year, month + 1, 0);
        const today = toDateKey(new Date());
        monthLabelEl.textContent = monthStart.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
        const cells = [];
        dayNames.forEach((name) => cells.push(`<div class="availability-dow" role="columnheader">${name}</div>`));
        for (let i = 0; i < monthStart.getDay(); i++) {
            cells.push('<div class="availability-day empty" aria-hidden="true"></div>');
        }
        for (let day = 1; day <= monthEnd.getDate(); day++) {
            const date = new Date(year, month, day);
            const key = toDateKey(date);
            const isBlocked = blockedSet.has(key);
            const isToday = key === today;
            const classes = ['availability-day'];
            if (isBlocked) classes.push('blocked');
            if (isToday) classes.push('today');
            const label = isBlocked ? 'Not available' : 'Available';
            cells.push(`<div class="${classes.join(' ')}" role="gridcell" title="${label}" aria-label="${monthStart.toLocaleDateString(undefined, { month: 'long' })} ${day}, ${label}">${day}</div>`);
        }
        gridEl.innerHTML = cells.join('');
    };

    selectEl.addEventListener('change', renderCalendar);
    prevBtn.addEventListener('click', () => {
        currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() - 1, 1);
        renderCalendar();
    });
    nextBtn.addEventListener('click', () => {
        currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 1);
        renderCalendar();
    });
    renderCalendar();
})();
</script>
