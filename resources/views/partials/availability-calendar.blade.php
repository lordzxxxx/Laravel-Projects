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
        gap: clamp(0.65rem, 1.25vw, 0.85rem);
        width: 100%;
        max-width: 100%;
        min-height: 0;
    }

    .avail-cal-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 0.65rem 0.85rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.08));
        flex-shrink: 0;
    }

    .avail-cal-toolbar__unit {
        flex: 1 1 12rem;
        min-width: min(100%, 10rem);
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
        padding: 0.5rem 0.65rem;
        border-radius: 0.5rem;
        border: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.12));
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
        gap: 0.35rem;
        flex-shrink: 0;
    }

    .availability-calendar-wrap .month-nav-btn {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        border: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.1));
        background: var(--app-surface-bg, #fff);
        color: var(--gray-600, #4b5563);
        font-size: 0.75rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: border-color 0.15s ease, background 0.15s ease, color 0.15s ease;
    }

    .availability-calendar-wrap .month-nav-btn:hover {
        border-color: var(--green-primary, #457359);
        background: color-mix(in srgb, var(--green-primary, #457359) 8%, #fff);
        color: var(--green-dark, #14532d);
    }

    .availability-month-label {
        min-width: 7.5rem;
        padding: 0 0.35rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
        letter-spacing: -0.02em;
        color: var(--ink-900, var(--gray-900, #0f172a));
    }

    .avail-cal-grid-shell {
        width: 100%;
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
    }

    .availability-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 0.3rem;
        width: 100%;
    }

    .availability-dow {
        font-size: 0.625rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        text-align: center;
        color: var(--gray-400, #9ca3af);
        padding: 0 0 0.15rem;
    }

    .availability-day {
        width: 100%;
        aspect-ratio: 1;
        min-height: 1.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--gray-700, #374151);
        background: transparent;
        border: none;
        transition: background-color 0.12s ease, color 0.12s ease, box-shadow 0.12s ease;
    }

    .availability-day.empty {
        visibility: hidden;
        pointer-events: none;
        min-height: 0;
    }

    .availability-day.available {
        background: color-mix(in srgb, var(--gray-100, #f1f5f9) 65%, transparent);
        color: var(--gray-700, #374151);
    }

    .availability-day.blocked {
        background: color-mix(in srgb, #f87171 22%, #fff);
        color: #b91c1c;
        font-weight: 600;
    }

    .availability-day.today {
        box-shadow: inset 0 0 0 2px var(--green-primary, #457359);
        color: var(--green-dark, #14532d);
        font-weight: 700;
    }

    .availability-day.today.blocked {
        box-shadow: inset 0 0 0 2px #dc2626;
    }

    .availability-legend {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.65rem 1rem;
        padding-top: 0.65rem;
        margin-top: auto;
        flex-shrink: 0;
        border-top: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.06));
        color: var(--gray-500, #6b7280);
        font-size: 0.6875rem;
        font-weight: 500;
    }

    .availability-legend__item {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .availability-dot {
        width: 0.625rem;
        height: 0.625rem;
        border-radius: 0.2rem;
        flex-shrink: 0;
    }

    .availability-dot.available {
        background: color-mix(in srgb, var(--gray-100, #f1f5f9) 65%, #cbd5e1);
    }

    .availability-dot.blocked {
        background: color-mix(in srgb, #f87171 55%, #fecaca);
    }

    /* Owner sidebar / dashboard compact card */
    .availability-calendar-wrap.avail-cal--compact {
        flex: 1 1 auto;
        min-height: 0;
        gap: clamp(0.55rem, 1vw, 0.75rem);
    }

    .avail-cal--compact .avail-cal-toolbar {
        flex-direction: column;
        align-items: stretch;
        gap: 0.55rem;
    }

    .avail-cal--compact .avail-cal-toolbar__unit {
        max-width: none;
        width: 100%;
    }

    .avail-cal--compact .avail-cal-toolbar__nav {
        width: 100%;
        justify-content: space-between;
        padding: 0.15rem 0;
    }

    .avail-cal--compact .availability-month-label {
        flex: 1;
        text-align: center;
        font-size: 0.9375rem;
    }

    .avail-cal--compact .avail-cal-grid-shell {
        flex: 1 1 auto;
        justify-content: stretch;
        min-height: 10rem;
    }

    .avail-cal--compact .availability-grid {
        flex: 1;
        align-content: start;
        gap: clamp(0.25rem, 0.8vw, 0.4rem);
    }

    .avail-cal--compact .availability-day {
        min-height: clamp(1.65rem, 4vw, 2.35rem);
        font-size: clamp(0.6875rem, 1.4vw, 0.8125rem);
        border-radius: 0.45rem;
    }

    .avail-cal--compact .availability-dow {
        font-size: 0.5625rem;
        padding-bottom: 0.25rem;
    }

    .avail-cal--compact .availability-legend {
        justify-content: flex-start;
        width: 100%;
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
        .avail-cal-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .avail-cal-toolbar__nav {
            width: 100%;
            justify-content: space-between;
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

    html.dark .availability-day.available {
        background: color-mix(in srgb, var(--app-surface-muted-bg) 80%, transparent) !important;
        color: var(--ink-700) !important;
    }

    html.dark .availability-day.today {
        color: var(--green-dark) !important;
        box-shadow: inset 0 0 0 2px var(--green-primary) !important;
    }

    html.dark .availability-day.blocked {
        background: color-mix(in srgb, var(--status-danger, #ef4444) 22%, var(--app-surface-muted-bg)) !important;
        color: #fca5a5 !important;
    }

    html.dark .availability-legend {
        color: var(--ink-500) !important;
        border-color: var(--app-surface-border) !important;
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
            if (isBlocked) {
                classes.push('blocked');
            } else {
                classes.push('available');
            }
            if (isToday) classes.push('today');
            const label = isBlocked ? 'Booked or pending' : 'Available';
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
