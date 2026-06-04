<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>My Properties - Impasugong Accommodations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @include('owner.partials.owner-page-fonts')
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            @include('partials.tenant-theme-css-vars')
        }

        .owner-units-top {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 0.75rem 1.25rem;
            flex-shrink: 0;
        }

        .owner-units-top .page-header {
            margin-bottom: 0;
            flex: 1 1 14rem;
            min-width: min(100%, 16rem);
        }

        .owner-units-top .page-header h1 {
            font-size: clamp(1.35rem, 2.2vw, 1.65rem);
            letter-spacing: -0.02em;
        }

        .owner-units-top .page-header > p {
            margin-top: 0.35rem;
            font-size: 0.875rem;
            color: var(--gray-500);
            max-width: 32rem;
        }

        .owner-units-top .business-status-pill {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px 8px;
            margin-top: 0.5rem;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 0.82rem;
            font-weight: 600;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: rgba(255, 255, 255, 0.9);
            color: var(--gray-800);
            max-width: 100%;
        }

        .owner-units-top .business-status-pill i { color: var(--green-primary); }
        .owner-units-top .business-status-pill .biz-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--gray-500);
            font-weight: 700;
        }
        .owner-units-top .business-status-pill .biz-detail { color: var(--gray-500); font-weight: 500; }
        .owner-units-top .business-status-pill.tone-success { border-color: rgba(16, 185, 129, 0.35); background: #ecfdf5; }
        .owner-units-top .business-status-pill.tone-warning { border-color: rgba(245, 158, 11, 0.45); background: #fffbeb; color: #92400e; }
        .owner-units-top .business-status-pill.tone-danger { border-color: rgba(248, 113, 113, 0.5); background: #fef2f2; color: #b91c1c; }
        .owner-units-top .business-status-pill.is-blocked { outline: 1px dashed rgba(245, 158, 11, 0.6); }

        .owner-units-add-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.55rem 1.1rem;
            background: var(--green-primary);
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            flex-shrink: 0;
            transition: filter 0.15s ease, background 0.15s ease;
        }

        .owner-units-add-btn:hover { filter: brightness(1.06); }

        .owner-units-add-btn.is-disabled {
            opacity: 0.55;
            cursor: not-allowed;
            pointer-events: none;
            background: var(--gray-400);
        }

        .owner-units-kpis {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: clamp(0.65rem, 1.2vw, 1rem);
            flex-shrink: 0;
        }

        .owner-units-kpi {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: clamp(0.85rem, 1.5vw, 1.1rem) clamp(0.9rem, 1.5vw, 1.15rem);
            background: var(--app-surface-bg, #fff);
            border: 1px solid var(--app-surface-border, var(--gray-200));
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .owner-units-kpi:hover {
            border-color: color-mix(in srgb, var(--green-primary) 28%, var(--gray-200));
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
        }

        .owner-units-kpi__icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .owner-units-kpi__icon--green { background: #ecfdf5; color: #047857; }
        .owner-units-kpi__icon--blue { background: #eff6ff; color: #1d4ed8; }
        .owner-units-kpi__icon--orange { background: #fffbeb; color: #b45309; }
        .owner-units-kpi__icon--slate { background: #f8fafc; color: #475569; }

        .owner-units-kpi__value {
            font-size: clamp(1.25rem, 2vw, 1.5rem);
            font-weight: 700;
            line-height: 1.15;
            color: var(--ink-900, var(--gray-900));
            letter-spacing: -0.03em;
        }

        .owner-units-kpi__label {
            margin-top: 0.15rem;
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--gray-500);
        }

        .owner-units-body {
            flex: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(17rem, 22rem);
            gap: clamp(1rem, 2vw, 1.5rem);
            align-items: stretch;
            min-height: 0;
        }

        .owner-units-primary {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0;
            height: 100%;
            min-height: 100%;
        }

        .owner-units-block {
            background: var(--app-surface-bg, #fff);
            border: 1px solid var(--app-surface-border, var(--gray-200));
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }

        .owner-units-block__head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem 1rem;
            padding: 0.85rem 1.15rem;
            border-bottom: 1px solid var(--gray-100);
        }

        .owner-units-block__head h2 {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .owner-units-block__head h2 i {
            color: var(--green-primary);
            font-size: 0.8rem;
        }

        .owner-units-block__count {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--gray-500);
        }

        .owner-units-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 16.5rem), 1fr));
            gap: clamp(0.75rem, 1.5vw, 1rem);
            padding: clamp(0.85rem, 1.5vw, 1.15rem);
            flex: 1;
        }

        .owner-unit-card {
            display: flex;
            flex-direction: column;
            background: var(--app-surface-bg, #fff);
            border: 1px solid var(--app-surface-border, var(--gray-200));
            border-radius: 0.65rem;
            overflow: hidden;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .owner-unit-card:hover {
            border-color: color-mix(in srgb, var(--green-primary) 30%, var(--gray-200));
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        }

        .owner-unit-card__media {
            position: relative;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: var(--gray-100);
        }

        .owner-unit-card__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .owner-unit-card__badge {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            padding: 0.2rem 0.5rem;
            border-radius: 999px;
            font-size: 0.625rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.95);
            color: var(--gray-800);
            border: 1px solid var(--gray-200);
        }

        .owner-unit-card__badge--verified {
            background: #ecfdf5;
            color: #047857;
            border-color: #bbf7d0;
        }

        .owner-unit-card__badge--inactive {
            background: var(--gray-700);
            color: #fff;
            border-color: transparent;
        }

        .owner-unit-card__type {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            padding: 0.2rem 0.45rem;
            border-radius: 0.35rem;
            font-size: 0.625rem;
            font-weight: 600;
            background: rgba(15, 23, 42, 0.72);
            color: #fff;
            text-transform: capitalize;
        }

        .owner-unit-card__body {
            padding: 0.75rem 0.85rem 0.85rem;
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            flex: 1;
        }

        .owner-unit-card__title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--ink-900, var(--gray-900));
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .owner-unit-card__location {
            font-size: 0.75rem;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .owner-unit-card__location i {
            color: var(--green-primary);
            font-size: 0.7rem;
        }

        .owner-unit-card__meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.35rem;
            padding: 0.5rem 0;
            border-top: 1px solid var(--gray-100);
            border-bottom: 1px solid var(--gray-100);
        }

        .owner-unit-card__meta dt {
            font-size: 0.5625rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            text-align: center;
        }

        .owner-unit-card__meta dd {
            margin: 0.15rem 0 0;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-800);
            text-align: center;
        }

        .owner-unit-card__meta dd.price { color: var(--green-dark); }

        .owner-unit-card__actions {
            display: flex;
            gap: 0.35rem;
            margin-top: auto;
        }

        .owner-unit-card__btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            padding: 0.4rem 0.35rem;
            border-radius: 0.4rem;
            border: 1px solid var(--gray-200);
            background: var(--app-surface-bg, #fff);
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--ink-700, var(--gray-700));
            text-decoration: none;
            cursor: pointer;
            transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }

        .owner-unit-card__btn:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        .owner-unit-card__btn--primary {
            color: var(--green-dark);
            border-color: color-mix(in srgb, var(--green-primary) 35%, var(--gray-200));
            background: var(--green-white, #edf4ea);
        }

        .owner-unit-card__btn--primary:hover {
            border-color: var(--green-primary);
        }

        .owner-unit-card__btn--danger {
            color: #b91c1c;
            border-color: #fecaca;
            background: #fef2f2;
        }

        .owner-unit-card__btn--danger:hover {
            border-color: #f87171;
        }

        .owner-unit-card__form {
            flex: 1;
            display: flex;
            min-width: 0;
        }

        .owner-unit-card__form .owner-unit-card__btn {
            width: 100%;
        }

        .owner-units-aside {
            display: flex;
            flex-direction: column;
            min-height: 100%;
            height: 100%;
            position: sticky;
            top: calc(var(--owner-content-offset, 108px) + 0.75rem);
            align-self: stretch;
        }

        .owner-units-aside .owner-avail-card {
            flex: 1;
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: none;
            min-height: 100%;
            height: 100%;
        }

        .owner-units-aside .owner-avail-card .availability-calendar-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 0;
        }

        .owner-units-aside .owner-avail-card .availability-legend {
            margin-top: auto;
            flex-shrink: 0;
            padding-top: 0.35rem;
        }

        .owner-units-empty {
            text-align: center;
            padding: clamp(2.5rem, 6vw, 4rem) 1.5rem;
            color: var(--gray-500);
        }

        .owner-units-empty i {
            font-size: 2rem;
            color: var(--gray-300);
            margin-bottom: 0.75rem;
        }

        .owner-units-empty h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.35rem;
        }

        .owner-units-empty p {
            font-size: 0.8125rem;
            max-width: 22rem;
            margin: 0 auto 1rem;
        }

        .owner-units-pagination {
            padding: 0.75rem 1.15rem 1rem;
            border-top: 1px solid var(--gray-100);
            display: flex;
            justify-content: center;
        }

        .owner-units-pagination .pagination {
            display: flex;
            list-style: none;
            gap: 0.35rem;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .owner-units-pagination .page-item { list-style: none; }

        .owner-units-pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2rem;
            height: 2rem;
            padding: 0 0.5rem;
            border-radius: 0.4rem;
            border: 1px solid var(--app-surface-border, var(--gray-200));
            background: var(--app-surface-bg, #fff);
            color: var(--ink-700, var(--gray-700));
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .owner-units-pagination .page-item.active .page-link {
            background: var(--green-primary);
            border-color: var(--green-primary);
            color: #fff;
        }

        .owner-units-pagination .page-item.disabled .page-link {
            opacity: 0.45;
            cursor: not-allowed;
        }

        .owner-units-pagination p.small.text-muted {
            margin-top: 0.5rem;
            width: 100%;
            text-align: center;
            color: var(--gray-500);
            font-size: 0.75rem;
        }

        @media (max-width: 1280px) {
            .owner-units-body {
                grid-template-columns: 1fr;
            }

            .owner-units-aside {
                position: static;
                height: auto;
                min-height: 0;
            }

            .owner-units-aside .owner-avail-card {
                flex: none;
                height: auto;
                min-height: 0;
            }
        }

        @media (max-width: 1100px) {
            .owner-units-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 640px) {
            .owner-units-kpis { grid-template-columns: 1fr; }
            .owner-units-top { align-items: stretch; }
            .owner-units-add-btn { width: 100%; }
            .owner-units-grid { grid-template-columns: 1fr; }
        }

        @include('owner.partials.top-navbar-styles')
    </style>
</head>
<body class="owner-nav-page">
    @include('owner.partials.top-navbar')

    <main class="main-content with-owner-nav owner-app-main owner-units-main">
        <header class="owner-units-top owner-page-top">
            <div class="owner-page-hero owner-page-hero--flush">
                <p class="owner-page-hero__eyebrow">Listings</p>
                <h1 class="owner-page-hero__title">My Properties</h1>
                <p class="owner-page-hero__lede">Manage accommodations, pricing, and availability.</p>
                @if(!empty($businessStatus))
                    <span class="business-status-pill tone-{{ $businessStatus['tone'] }} @if(!($canCreateListing ?? false)) is-blocked @endif">
                        <i class="fa-solid fa-clipboard-check" aria-hidden="true"></i>
                        <span class="biz-label">Business status</span>
                        <span class="biz-main">Registration: {{ $businessStatus['registration'] }}</span>
                        <span class="biz-detail">&middot; Billing: {{ $businessStatus['billing'] }}</span>
                    </span>
                @endif
            </div>
            @if($canCreateListing ?? false)
                <a href="/owner/accommodations/create" class="owner-units-add-btn">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Add property</span>
                </a>
            @else
                <span class="owner-units-add-btn is-disabled" title="Adding units isn’t available right now. Check your business status (registration &amp; billing) or contact support.">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Add property</span>
                </span>
            @endif
        </header>

        <section class="owner-units-kpis" aria-label="Property overview">
            <div class="owner-units-kpi">
                <span class="owner-units-kpi__icon owner-units-kpi__icon--green" aria-hidden="true"><i class="fa-solid fa-house-chimney"></i></span>
                <div>
                    <div class="owner-units-kpi__value">{{ number_format($accommodations->total() ?? 0) }}</div>
                    <div class="owner-units-kpi__label">Total units</div>
                </div>
            </div>
            <div class="owner-units-kpi">
                <span class="owner-units-kpi__icon owner-units-kpi__icon--blue" aria-hidden="true"><i class="fa-solid fa-circle-check"></i></span>
                <div>
                    <div class="owner-units-kpi__value">{{ number_format($accommodations->where('is_verified', true)->count() ?? 0) }}</div>
                    <div class="owner-units-kpi__label">Verified</div>
                </div>
            </div>
            <div class="owner-units-kpi">
                <span class="owner-units-kpi__icon owner-units-kpi__icon--orange" aria-hidden="true"><i class="fa-solid fa-calendar-check"></i></span>
                <div>
                    <div class="owner-units-kpi__value">{{ number_format($accommodations->sum('bookings_count') ?? 0) }}</div>
                    <div class="owner-units-kpi__label">Total bookings</div>
                </div>
            </div>
            <div class="owner-units-kpi">
                <span class="owner-units-kpi__icon owner-units-kpi__icon--slate" aria-hidden="true"><i class="fa-solid fa-star"></i></span>
                <div>
                    <div class="owner-units-kpi__value">{{ number_format($accommodations->avg('rating') ?? 0, 1) }}</div>
                    <div class="owner-units-kpi__label">Avg. rating</div>
                </div>
            </div>
        </section>

        <div class="owner-units-body">
            <div class="owner-units-primary">
                <section class="owner-units-block" aria-labelledby="owner-units-list-heading">
                    <div class="owner-units-block__head">
                        <h2 id="owner-units-list-heading">
                            <i class="fa-solid fa-list-ul" aria-hidden="true"></i>
                            All properties
                        </h2>
                        @if(isset($accommodations) && method_exists($accommodations, 'total'))
                            <span class="owner-units-block__count">
                                {{ $accommodations->firstItem() ?? 0 }}–{{ $accommodations->lastItem() ?? 0 }}
                                of {{ number_format($accommodations->total()) }}
                            </span>
                        @endif
                    </div>

                    @if(isset($accommodations) && count($accommodations) > 0)
                        <div class="owner-units-grid">
                            @foreach($accommodations as $accommodation)
                                <article class="owner-unit-card">
                                    <div class="owner-unit-card__media">
                                        <img src="{{ $accommodation->primary_image_url }}" alt="{{ $accommodation->name }}" loading="lazy">
                                        @if($accommodation->is_verified)
                                            <span class="owner-unit-card__badge owner-unit-card__badge--verified">Verified</span>
                                        @elseif($accommodation->is_available)
                                            <span class="owner-unit-card__badge">Active</span>
                                        @else
                                            <span class="owner-unit-card__badge owner-unit-card__badge--inactive">Inactive</span>
                                        @endif
                                        <span class="owner-unit-card__type">{{ str_replace('-', ' ', $accommodation->type) }}</span>
                                    </div>
                                    <div class="owner-unit-card__body">
                                        <h3 class="owner-unit-card__title">{{ $accommodation->name }}</h3>
                                        <p class="owner-unit-card__location">
                                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                            Brgy. {{ $accommodation->barangay }}
                                        </p>
                                        <dl class="owner-unit-card__meta">
                                            <div>
                                                <dt>Price</dt>
                                                <dd class="price">₱{{ number_format($accommodation->price_per_night, 0, '.', ',') }}</dd>
                                            </div>
                                            <div>
                                                <dt>Bookings</dt>
                                                <dd>{{ $accommodation->bookings_count ?? 0 }}</dd>
                                            </div>
                                            <div>
                                                <dt>Rating</dt>
                                                <dd>{{ number_format($accommodation->rating ?? 0, 1) }}</dd>
                                            </div>
                                        </dl>
                                        <div class="owner-unit-card__actions">
                                            <a href="/owner/accommodations/{{ $accommodation->id }}" class="owner-unit-card__btn" title="View property">
                                                <i class="fa-solid fa-eye" aria-hidden="true"></i> View
                                            </a>
                                            <a href="/owner/accommodations/{{ $accommodation->id }}/edit" class="owner-unit-card__btn owner-unit-card__btn--primary" title="Edit property">
                                                <i class="fa-solid fa-pen" aria-hidden="true"></i> Edit
                                            </a>
                                            <form action="/owner/accommodations/{{ $accommodation->id }}" method="POST" class="owner-unit-card__form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="owner-unit-card__btn owner-unit-card__btn--danger" title="Delete property" onclick="return confirm('Are you sure you want to delete this property? This action cannot be undone.')">
                                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        @if(method_exists($accommodations, 'hasPages') && $accommodations->hasPages())
                            <div class="owner-units-pagination">
                                {{ $accommodations->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    @else
                        <div class="owner-units-empty">
                            <i class="fa-solid fa-building-circle-exclamation" aria-hidden="true"></i>
                            <h3>No properties yet</h3>
                            <p>Add your first unit to start accepting bookings.</p>
                            @if($canCreateListing ?? false)
                                <a href="/owner/accommodations/create" class="owner-units-add-btn">
                                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                    <span>Add your first property</span>
                                </a>
                            @else
                                <span class="owner-units-add-btn is-disabled">
                                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                    <span>Add your first property</span>
                                </span>
                            @endif
                        </div>
                    @endif
                </section>
            </div>

            @if(isset($availabilityAccommodations))
                <aside class="owner-units-aside" aria-label="Room availability">
                    @include('owner.partials.owner-availability-card', [
                        'calendarId' => 'ownerUnitsCal',
                        'availabilityAccommodations' => $availabilityAccommodations,
                        'availabilityEventsByAccommodation' => $availabilityEventsByAccommodation ?? [],
                        'headingId' => 'owner-units-avail-heading',
                    ])
                </aside>
            @endif
        </div>
    </main>
</body>
</html>
