@props([
    'calendarId' => 'acCal',
    'availabilityAccommodations',
    'availabilityEventsByAccommodation' => [],
])

@once
<style>
    .availability-calendar-wrap {
        margin-top: 0;
        width: 100%;
        max-width: 21.5rem;
        margin-inline: auto;
    }
    .availability-calendar-wrap .availability-controls {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        margin-bottom: 14px;
        max-width: 100%;
    }
    .availability-calendar-wrap .availability-controls__unit {
        width: 100%;
    }
    .availability-calendar-wrap .availability-select {
        display: block;
        width: 100%;
        min-width: 0;
        max-width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid var(--gray-200, #e5e7eb);
        font-size: 0.88rem;
        line-height: 1.35;
        background: #fff;
        color: var(--gray-800, #1f2937);
    }
    .availability-calendar-wrap .availability-controls__month {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
    }
    .availability-calendar-wrap .month-nav-btn {
        flex-shrink: 0;
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 10px;
        border: 1px solid rgba(16, 124, 89, 0.35);
        background: #ecfdf5;
        color: #065f46;
        font-weight: 700;
        font-size: 1rem;
        line-height: 1;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.15s ease;
    }
    .availability-calendar-wrap .month-nav-btn:hover {
        background: #d1fae5;
        transform: scale(1.04);
    }
    .availability-month-label {
        flex: 1 1 auto;
        min-width: 0;
        text-align: center;
        font-weight: 700;
        color: var(--green-dark, #14532d);
        font-size: 0.95rem;
        white-space: nowrap;
    }
    .availability-grid {
        display: grid;
        grid-template-columns: repeat(7, 2.5rem);
        grid-auto-rows: 2.5rem;
        gap: 0.35rem;
        width: fit-content;
        max-width: 100%;
        align-items: center;
        justify-items: center;
    }
    .availability-dow {
        width: 2.5rem;
        height: auto;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        text-align: center;
        color: var(--gray-500, #6b7280);
        padding: 2px 0 4px;
        line-height: 1.2;
    }
    .availability-day {
        box-sizing: border-box;
        width: 2.5rem;
        height: 2.5rem;
        min-width: 2.5rem;
        min-height: 2.5rem;
        max-width: 2.5rem;
        max-height: 2.5rem;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        line-height: 1;
        color: var(--gray-800, #1f2937);
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.25);
    }
    .availability-day.empty {
        visibility: hidden;
        background: transparent;
        border: none;
        width: 2.5rem;
        height: 2.5rem;
    }
    .availability-day.blocked {
        background: rgba(248, 113, 113, 0.15);
        border-color: rgba(248, 113, 113, 0.4);
        color: #991b1b;
    }
    .availability-day.today {
        outline: 2px solid var(--green-primary, #22c55e);
        outline-offset: 0;
    }

    @media (min-width: 480px) {
        .availability-calendar-wrap {
            max-width: 23.5rem;
        }
        .availability-grid {
            grid-template-columns: repeat(7, 2.75rem);
            grid-auto-rows: 2.75rem;
            gap: 0.4rem;
        }
        .availability-dow {
            width: 2.75rem;
        }
        .availability-day {
            width: 2.75rem;
            height: 2.75rem;
            min-width: 2.75rem;
            min-height: 2.75rem;
            max-width: 2.75rem;
            max-height: 2.75rem;
            font-size: 0.84rem;
            border-radius: 9px;
        }
        .availability-day.empty {
            width: 2.75rem;
            height: 2.75rem;
        }
    }
    .availability-legend {
        display: flex;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 12px;
        color: var(--gray-600, #4b5563);
        font-size: 0.8rem;
    }
    .availability-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        display: inline-block;
        margin-right: 5px;
        vertical-align: middle;
    }
    .availability-dot.available { background: #bbf7d0; }
    .availability-dot.blocked { background: #fecaca; }
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
</style>
@endonce

<div class="availability-calendar-wrap">
    <div class="availability-controls">
        <div class="availability-controls__unit">
            <label for="{{ $calendarId }}Accommodation" class="sr-only">Select unit</label>
            <select id="{{ $calendarId }}Accommodation"
                    class="availability-select"
                    aria-label="Select unit for availability calendar">
                @foreach($availabilityAccommodations as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }} ({{ str_replace('-', ' ', $unit->type) }})</option>
                @endforeach
            </select>
        </div>
        <div class="availability-controls__month" role="group" aria-label="Change month">
            <button type="button" class="month-nav-btn" id="{{ $calendarId }}Prev" aria-label="Previous month">&lt;</button>
            <div class="availability-month-label" id="{{ $calendarId }}MonthLabel" aria-live="polite">Month</div>
            <button type="button" class="month-nav-btn" id="{{ $calendarId }}Next" aria-label="Next month">&gt;</button>
        </div>
    </div>
    <div class="availability-grid" id="{{ $calendarId }}Grid"></div>
    <div class="availability-legend">
        <span><span class="availability-dot available"></span>Available</span>
        <span><span class="availability-dot blocked"></span>Booked / pending hold</span>
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
        dayNames.forEach((name) => cells.push(`<div class="availability-dow">${name}</div>`));
        for (let i = 0; i < monthStart.getDay(); i++) cells.push('<div class="availability-day empty"></div>');
        for (let day = 1; day <= monthEnd.getDate(); day++) {
            const date = new Date(year, month, day);
            const key = toDateKey(date);
            const isBlocked = blockedSet.has(key);
            const isToday = key === today;
            const classes = ['availability-day'];
            if (isBlocked) classes.push('blocked');
            if (isToday) classes.push('today');
            cells.push(`<div class="${classes.join(' ')}" title="${isBlocked ? 'Not available' : 'Available'}">${day}</div>`);
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
