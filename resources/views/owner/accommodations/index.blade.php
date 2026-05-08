<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.tenant-favicon')
    <title>My Properties - Impasugong Accommodations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }

        :root {
            @include('partials.tenant-theme-css-vars')
            --gray-50: #F9FAFB; --gray-100: #F3F4F6; --gray-200: #E5E7EB;
            --gray-300: #D1D5DB; --gray-400: #9CA3AF; --gray-500: #6B7280;
            --gray-600: #4B5563; --gray-700: #374151; --gray-800: #1F2937;
            --gray-900: #0F172A;
            --shadow-xs: 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-sm: 0 2px 4px rgba(15, 23, 42, 0.06);
            --shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            --shadow-md: 0 10px 25px rgba(15, 23, 42, 0.08);
            --shadow-lg: 0 20px 40px rgba(15, 23, 42, 0.10);
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top right, rgba(16, 185, 129, 0.06), transparent 55%),
                radial-gradient(circle at bottom left, rgba(245, 158, 11, 0.04), transparent 50%),
                var(--cream);
            color: var(--gray-800);
            line-height: 1.55;
            min-height: 100vh;
        }

        /* Main content shell */
        .main-content {
            width: min(1600px, 100%);
            margin: 0 auto;
            padding: var(--owner-content-offset) clamp(14px, 2.5vw, 36px) 36px;
            min-height: calc(100vh - var(--owner-content-offset));
        }

        /* ── Page header ───────────────────────────────────────────────────── */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 24px;
            padding: 20px 22px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85)),
                linear-gradient(135deg, rgba(16, 185, 129, 0.06), rgba(245, 158, 11, 0.03));
            border: 1px solid rgba(16, 185, 129, 0.18);
            border-radius: 18px;
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        /* h1 typography comes from ui-foundation-styles for system-wide consistency */

        .plan-usage-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            margin-left: 3.65rem;
            padding: 7px 14px;
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid var(--gray-200);
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--gray-700);
        }
        .plan-usage-pill .plan-name {
            color: var(--green-dark);
            font-weight: 700;
        }
        .plan-usage-pill .plan-remaining { color: var(--gray-500); font-weight: 500; }
        .plan-usage-pill.is-full { border-color: #fde68a; background: #fffbeb; color: #92400e; }
        .plan-usage-pill.is-full .plan-name { color: #b45309; }

        .add-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 22px;
            background: linear-gradient(135deg, var(--green-primary), var(--green-dark));
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(16, 124, 89, 0.22);
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
            white-space: nowrap;
        }
        .add-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 18px rgba(16, 124, 89, 0.28); filter: brightness(1.05); }
        .add-btn:active { transform: translateY(0); }
        .add-btn-disabled, .add-btn.add-btn-disabled {
            opacity: 0.55;
            cursor: not-allowed;
            pointer-events: none;
            background: var(--gray-400);
            box-shadow: none;
        }

        /* ── Stats grid ────────────────────────────────────────────────────── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 20px;
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 16px;
            box-shadow: var(--shadow-xs);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
            border-color: var(--gray-300);
        }

        .stat-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            font-size: 1.05rem;
            flex-shrink: 0;
        }
        .stat-icon.green  { background: #ECFDF5; color: #047857; border: 1px solid #D1FAE5; }
        .stat-icon.blue   { background: #EFF6FF; color: #1D4ED8; border: 1px solid #DBEAFE; }
        .stat-icon.orange { background: #FFF7ED; color: #C2410C; border: 1px solid #FED7AA; }
        .stat-icon.purple { background: #FAF5FF; color: #7E22CE; border: 1px solid #E9D5FF; }

        .stat-info h3 {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--gray-900);
            margin: 0 0 2px;
            line-height: 1.1;
        }
        .stat-info p {
            color: var(--gray-500);
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin: 0;
        }

        /* ── Properties section ────────────────────────────────────────────── */
        .properties-section {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 18px;
            box-shadow: var(--shadow-xs);
            overflow: hidden;
        }

        .properties-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--gray-200);
            background: linear-gradient(180deg, #ffffff, #FAFBFC);
        }
        .properties-section-header h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            color: var(--gray-900);
            font-weight: 700;
            margin: 0;
        }
        .properties-section-header h3 i {
            display: inline-flex;
            width: 30px;
            height: 30px;
            align-items: center;
            justify-content: center;
            background: #ECFDF5;
            color: #047857;
            border: 1px solid #D1FAE5;
            border-radius: 9px;
            font-size: 0.82rem;
        }
        .properties-section-header .results-count {
            font-size: 0.78rem;
            color: var(--gray-500);
            font-weight: 600;
        }

        /* ── Property cards grid ───────────────────────────────────────────── */
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
            gap: 16px;
            padding: 20px;
        }

        .property-card {
            display: flex;
            flex-direction: column;
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 14px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .property-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow);
            border-color: rgba(16, 185, 129, 0.4);
        }

        .property-card-media {
            position: relative;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: var(--gray-100);
        }
        .property-card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .property-card:hover .property-card-media img { transform: scale(1.04); }

        .property-card-status {
            position: absolute;
            top: 12px;
            left: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 11px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
        .property-card-status.verified { background: rgba(16, 185, 129, 0.95); color: #ffffff; }
        .property-card-status.active   { background: rgba(255, 255, 255, 0.95); color: var(--green-dark); }
        .property-card-status.inactive { background: rgba(107, 114, 128, 0.92); color: #ffffff; }

        .property-card-type {
            position: absolute;
            top: 12px;
            right: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 11px;
            background: rgba(15, 23, 42, 0.72);
            color: #ffffff;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: capitalize;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        .property-card-body {
            display: flex;
            flex-direction: column;
            flex: 1;
            padding: 16px 18px;
            gap: 12px;
        }
        .property-card-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .property-card-location {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--gray-500);
            font-size: 0.82rem;
            margin: 0;
        }
        .property-card-location i { color: var(--green-primary); font-size: 0.78rem; }

        .property-card-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            padding: 12px 0;
            border-top: 1px solid var(--gray-100);
            border-bottom: 1px solid var(--gray-100);
        }
        .property-card-meta-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            text-align: center;
        }
        .property-card-meta-item .label {
            font-size: 0.66rem;
            color: var(--gray-500);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .property-card-meta-item .value {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--gray-800);
        }
        .property-card-meta-item.price .value { color: var(--green-dark); }
        .property-card-meta-item.rating .value i { color: #F59E0B; font-size: 0.78rem; }

        .property-card-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex: 1;
            padding: 9px 12px;
            border-radius: 10px;
            border: 1px solid transparent;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.18s ease, color 0.18s ease, border-color 0.18s ease, transform 0.12s ease;
            background: transparent;
        }
        .action-btn i { font-size: 0.82rem; }
        .action-btn:active { transform: translateY(1px); }

        .action-btn.view {
            background: #ECFDF5;
            color: #047857;
            border-color: #D1FAE5;
        }
        .action-btn.view:hover { background: #D1FAE5; border-color: #A7F3D0; }

        .action-btn.edit {
            background: #EFF6FF;
            color: #1D4ED8;
            border-color: #DBEAFE;
        }
        .action-btn.edit:hover { background: #DBEAFE; border-color: #BFDBFE; }

        .action-btn.delete {
            background: #FEF2F2;
            color: #B91C1C;
            border-color: #FECACA;
        }
        .action-btn.delete:hover { background: #FEE2E2; border-color: #FCA5A5; }

        .action-btn-form { flex: 1; display: flex; }
        .action-btn-form .action-btn { width: 100%; }

        /* ── Empty state ───────────────────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 70px 28px 80px;
        }
        .empty-state-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 84px;
            height: 84px;
            margin: 0 auto 20px;
            border-radius: 24px;
            background: #ECFDF5;
            color: #047857;
            border: 1px solid #D1FAE5;
            font-size: 2rem;
        }
        .empty-state h3 {
            color: var(--gray-900);
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 6px;
        }
        .empty-state p {
            color: var(--gray-500);
            font-size: 0.92rem;
            margin: 0 0 22px;
            max-width: 380px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ── Pagination ────────────────────────────────────────────────────── */
        .pagination-wrapper {
            padding: 18px 22px;
            border-top: 1px solid var(--gray-200);
            background: #FAFBFC;
            display: flex;
            justify-content: center;
        }
        .pagination-wrapper .pagination {
            display: flex;
            list-style: none;
            gap: 6px;
            margin: 0;
            padding: 0;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }
        .pagination-wrapper .page-item { list-style: none; }
        .pagination-wrapper .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border-radius: 9px;
            border: 1px solid var(--gray-200);
            background: #ffffff;
            color: var(--gray-700);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
        }
        .pagination-wrapper .page-link:hover { background: #ECFDF5; color: var(--green-dark); border-color: #A7F3D0; }
        .pagination-wrapper .page-item.active .page-link {
            background: var(--green-primary);
            border-color: var(--green-primary);
            color: #ffffff;
        }
        .pagination-wrapper .page-item.disabled .page-link {
            opacity: 0.4;
            cursor: not-allowed;
            background: var(--gray-50);
        }
        .pagination-wrapper p.small.text-muted {
            margin-top: 10px;
            width: 100%;
            text-align: center;
            color: var(--gray-500);
            font-size: 0.78rem;
        }

        /* ── Responsive ────────────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .main-content { padding: calc(var(--owner-content-offset) - 8px) 14px 24px; min-height: auto; }
            .page-header { flex-direction: column; gap: 16px; align-items: stretch; padding: 18px; }
            .page-header .add-btn { width: 100%; }
            .plan-usage-pill { margin-left: 0; }
            .stats-row { grid-template-columns: repeat(2, 1fr); }
            .properties-grid { grid-template-columns: 1fr; padding: 16px; }
            .properties-section-header { padding: 14px 16px; flex-direction: column; align-items: flex-start; }
        }

        @include('owner.partials.top-navbar-styles')
    </style>
</head>
<body class="owner-nav-page">
    @include('owner.partials.top-navbar')
    
    <main class="main-content with-owner-nav">
        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1>
                    <span class="page-title-icon"><i class="fa-solid fa-building"></i></span>
                    <span>My Properties</span>
                </h1>
                <p>Manage your accommodations and listings.</p>
                @if(isset($listingUsage))
                    @php
                        $usageLimitReached = isset($listingUsage['remaining']) && $listingUsage['remaining'] === 0;
                    @endphp
                    <span class="plan-usage-pill {{ $usageLimitReached ? 'is-full' : '' }}">
                        <i class="fa-solid fa-crown" aria-hidden="true"></i>
                        <span class="plan-name">{{ $listingUsage['plan_label'] }} plan</span>
                        @if($listingUsage['max'] === null)
                            <span class="plan-remaining">&middot; {{ $listingUsage['used'] }} active listing(s) (unlimited on Premium)</span>
                        @else
                            <span class="plan-remaining">&middot; {{ $listingUsage['used'] }} / {{ $listingUsage['max'] }} listings</span>
                            @if(($listingUsage['remaining'] ?? 0) > 0)
                                <span class="plan-remaining">({{ $listingUsage['remaining'] }} remaining)</span>
                            @elseif($usageLimitReached)
                                <span class="plan-remaining">(limit reached)</span>
                            @endif
                        @endif
                    </span>
                @endif
            </div>
            @if($canCreateListing ?? false)
                <a href="/owner/accommodations/create" class="add-btn">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Add Property</span>
                </a>
            @else
                <span class="add-btn add-btn-disabled" title="You've reached your plan limit or your subscription isn't active. Upgrade or remove a listing to add more.">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Add Property</span>
                </span>
            @endif
        </div>

        {{-- Stats overview --}}
        <div class="stats-row">
            <div class="stat-card">
                <span class="stat-icon green"><i class="fa-solid fa-house-chimney" aria-hidden="true"></i></span>
                <div class="stat-info">
                    <h3>{{ number_format($accommodations->total() ?? 0) }}</h3>
                    <p>Total properties</p>
                </div>
            </div>
            <div class="stat-card">
                <span class="stat-icon blue"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></span>
                <div class="stat-info">
                    <h3>{{ number_format($accommodations->where('is_verified', true)->count() ?? 0) }}</h3>
                    <p>Verified</p>
                </div>
            </div>
            <div class="stat-card">
                <span class="stat-icon orange"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></span>
                <div class="stat-info">
                    <h3>{{ number_format($accommodations->sum('bookings_count') ?? 0) }}</h3>
                    <p>Total bookings</p>
                </div>
            </div>
            <div class="stat-card">
                <span class="stat-icon purple"><i class="fa-solid fa-star" aria-hidden="true"></i></span>
                <div class="stat-info">
                    <h3>{{ number_format($accommodations->avg('rating') ?? 0, 1) }}</h3>
                    <p>Average rating</p>
                </div>
            </div>
        </div>

        {{-- Properties section --}}
        <div class="properties-section">
            <div class="properties-section-header">
                <h3>
                    <i class="fa-solid fa-list-ul" aria-hidden="true"></i>
                    <span>All properties</span>
                </h3>
                @if(isset($accommodations) && method_exists($accommodations, 'total'))
                    <span class="results-count">
                        Showing {{ $accommodations->firstItem() ?? 0 }}–{{ $accommodations->lastItem() ?? 0 }}
                        of {{ number_format($accommodations->total()) }}
                    </span>
                @endif
            </div>

            @if(isset($accommodations) && count($accommodations) > 0)
                <div class="properties-grid">
                    @foreach($accommodations as $accommodation)
                        <article class="property-card">
                            <div class="property-card-media">
                                <img src="{{ $accommodation->primary_image_url }}" alt="{{ $accommodation->name }}" loading="lazy">

                                @if($accommodation->is_verified)
                                    <span class="property-card-status verified">
                                        <i class="fa-solid fa-circle-check" aria-hidden="true"></i> Verified
                                    </span>
                                @elseif($accommodation->is_available)
                                    <span class="property-card-status active">
                                        <i class="fa-solid fa-circle-dot" aria-hidden="true"></i> Active
                                    </span>
                                @else
                                    <span class="property-card-status inactive">
                                        <i class="fa-solid fa-circle-pause" aria-hidden="true"></i> Inactive
                                    </span>
                                @endif

                                <span class="property-card-type">
                                    <i class="fa-solid fa-tag" aria-hidden="true"></i>
                                    {{ str_replace('-', ' ', $accommodation->type) }}
                                </span>
                            </div>

                            <div class="property-card-body">
                                <h4 class="property-card-title">{{ $accommodation->name }}</h4>
                                <p class="property-card-location">
                                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                    Brgy. {{ $accommodation->barangay }}
                                </p>

                                <div class="property-card-meta">
                                    <div class="property-card-meta-item price">
                                        <span class="label">Price</span>
                                        <span class="value">₱{{ number_format($accommodation->price_per_night, 0, '.', ',') }}</span>
                                    </div>
                                    <div class="property-card-meta-item">
                                        <span class="label">Bookings</span>
                                        <span class="value">
                                            <i class="fa-solid fa-calendar" aria-hidden="true"></i>
                                            {{ $accommodation->bookings_count ?? 0 }}
                                        </span>
                                    </div>
                                    <div class="property-card-meta-item rating">
                                        <span class="label">Rating</span>
                                        <span class="value">
                                            <i class="fa-solid fa-star" aria-hidden="true"></i>
                                            {{ number_format($accommodation->rating ?? 0, 1) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="property-card-actions">
                                    <a href="/owner/accommodations/{{ $accommodation->id }}" class="action-btn view" title="View property">
                                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="/owner/accommodations/{{ $accommodation->id }}/edit" class="action-btn edit" title="Edit property">
                                        <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="/owner/accommodations/{{ $accommodation->id }}" method="POST" class="action-btn-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Delete property" onclick="return confirm('Are you sure you want to delete this property? This action cannot be undone.')">
                                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if(method_exists($accommodations, 'hasPages') && $accommodations->hasPages())
                    <div class="pagination-wrapper">
                        {{ $accommodations->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <span class="empty-state-icon">
                        <i class="fa-solid fa-building-circle-exclamation" aria-hidden="true"></i>
                    </span>
                    <h3>No properties yet</h3>
                    <p>Start by adding your first property to begin accepting bookings on the platform.</p>
                    @if($canCreateListing ?? false)
                        <a href="/owner/accommodations/create" class="add-btn">
                            <i class="fa-solid fa-plus" aria-hidden="true"></i>
                            <span>Add your first property</span>
                        </a>
                    @else
                        <span class="add-btn add-btn-disabled" title="You've reached your plan limit or your subscription isn't active.">
                            <i class="fa-solid fa-plus" aria-hidden="true"></i>
                            <span>Add your first property</span>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </main>
</body>
</html>
