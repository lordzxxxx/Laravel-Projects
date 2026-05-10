@props([
    'calendarId' => 'acCal',
    'availabilityAccommodations',
    'availabilityEventsByAccommodation' => [],
])

@once
<style>
    .availability-calendar-wrap { margin-top: 0; }
    .availability-calendar-wrap .availability-controls {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }
    .availability-calendar-wrap .availability-select {
        min-width: 220px;
        flex: 1 1 220px;
        padding: 8px 10px;
        border-radius: 10px;
        border: 1px solid var(--gray-200, #e5e7eb);
        font-size: 0.88rem;
        background: #fff;
    }
    .availability-calendar-wrap .month-nav-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid rgba(16, 124, 89, 0.35);
        background: #ecfdf5;
        color: #065f46;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .availability-calendar-wrap .month-nav-btn:hover { background: #d1fae5; }
    .availability-month-label {
        min-width: 160px;
        text-align: center;
        font-weight: 700;
        color: var(--green-dark, #14532d);
        font-size: 0.95rem;
    }
    .availability-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 5px;
        width: 100%;
    }
    .availability-dow {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        text-align: center;
        color: var(--gray-500, #6b7280);
        padding: 4px 0;
    }
    .availability-day {
        aspect-ratio: 1;
        min-height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--gray-800, #1f2937);
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.25);
    }
    .availability-day.empty { visibility: hidden; background: transparent; border: none; }
    .availability-day.blocked {
        background: rgba(248, 113, 113, 0.15);
        border-color: rgba(248, 113, 113, 0.4);
        color: #991b1b;
    }
    .availability-day.today {
        outline: 2px solid var(--green-primary, #22c55e);
        outline-offset: 1px;
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
        <label for="{{ $calendarId }}Accommodation" class="sr-only">Select unit</label>
        <select id="{{ $calendarId }}Accommodation"
                class="availability-select"
                aria-label="Select unit for availability calendar">
            @foreach($availabilityAccommodations as $unit)
                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ str_replace('-', ' ', $unit->type) }})</option>
            @endforeach
        </select>
        <button type="button" class="month-nav-btn" id="{{ $calendarId }}Prev" aria-label="Previous month">&lt;</button>
        <div class="availability-month-label" id="{{ $calendarId }}MonthLabel">Month</div>
        <button type="button" class="month-nav-btn" id="{{ $calendarId }}Next" aria-label="Next month">&gt;</button>
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
