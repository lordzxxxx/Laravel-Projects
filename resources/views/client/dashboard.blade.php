<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @php
        $portalDirectory = $portalDirectory ?? false;
        $showClientNav = auth()->user()?->isClient() === true;
        $showPortalPublicNav = $portalDirectory && ! auth()->check();
        $showLegacyNav = ! $showClientNav && ! $showPortalPublicNav;
        $listingUrl = $portalDirectory ? route('portal.accommodations.index') : route('dashboard');
        $showUrl = fn ($accommodation) => $portalDirectory
            ? route('portal.accommodations.show', $accommodation)
            : route('accommodations.show', $accommodation);
    @endphp
    @include('partials.tenant-favicon')
    <title>Dashboard - Impasugong Accommodations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @include('partials.typography-system')
        :root {
            @include('partials.tenant-theme-css-vars')
            --gray-200: #E5E7EB; --gray-500: #6B7280; --gray-600: #4B5563; --gray-700: #374151; --gray-800: #1F2937;
        }

        @if($showLegacyNav)
        .navbar {
            background: var(--white);
            padding: 0 40px;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo img { width: 45px; height: 45px; border-radius: 0; border: none; object-fit: contain; }
        .nav-logo span { font-size: 1.3rem; font-weight: 700; color: var(--green-dark); }
        .nav-links { display: flex; gap: 8px; list-style: none; }
        .nav-links a {
            text-decoration: none;
            color: var(--gray-600);
            font-weight: 500;
            padding: 10px 18px;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }
        .nav-links a:hover { background: var(--green-soft); color: var(--green-dark); }
        .nav-links a.active { background: var(--green-primary); color: var(--white); }
        .nav-actions { display: flex; gap: 12px; align-items: center; }
        .nav-btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }
        .nav-btn.primary { background: var(--green-primary); color: var(--white); }
        .nav-btn.primary:hover { background: var(--green-dark); transform: translateY(-1px); }
        .nav-btn.secondary { background: var(--green-soft); color: var(--green-dark); }
        .nav-btn.secondary:hover { background: var(--green-white); }
        @endif

        @if($showClientNav)
            @include('client.partials.top-navbar-styles')
        @elseif($showPortalPublicNav)
            @include('client.partials.guest-shell-styles')
        @endif

        @include('client.partials.guest-stays-browse-styles')

        @media (max-width: 768px) {
            @if($showLegacyNav)
            .navbar { padding: 0 20px; height: 60px; }
            .nav-logo img { width: 38px; height: 38px; }
            .nav-logo span { font-size: 1.1rem; }
            .nav-links { display: none; }
            @endif
        }
    </style>
</head>
<body class="{{ $showClientNav ? 'client-nav-page font-sans text-gray-800' : 'min-h-screen font-sans text-gray-800 bg-cover bg-center bg-fixed' }}"@if(! $showClientNav) style="background-image: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 50%, rgba(27, 94, 32, 0.1) 100%), url('/COMMUNAL.jpg');"@endif>
    @if($showClientNav)
        @include('client.partials.top-navbar', ['active' => 'dashboard', 'portalDirectory' => $portalDirectory])
    @elseif($showPortalPublicNav)
        @include('partials.portal-public-nav', ['active' => 'browse', 'municipalityName' => config('portals.municipality_name', 'Impasug-ong')])
    @else
        <nav class="navbar">
            <a href="{{ $portalDirectory ? route('portal.landing') : route('dashboard') }}" class="nav-logo">
                <img src="/SYSTEMLOGO.png" alt="ImpaStay Logo">
                <span>Impasugong</span>
            </a>

            <ul class="nav-links">
                @auth
                    <li><a href="{{ $listingUrl }}" class="active">Browse</a></li>
                    <li><a href="{{ $portalDirectory ? route('portal.bookings.index') : route('bookings.index') }}">My Bookings</a></li>
                    <li><a href="{{ route('messages.index', [], false) }}">Messages</a></li>
                    <li><a href="{{ route('profile.edit') }}">Settings</a></li>
                @else
                    <li><a href="{{ $listingUrl }}" class="active">Browse</a></li>
                @endauth
            </ul>

            <div class="nav-actions">
                @auth
                    <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 10px; background: var(--green-soft); color: var(--green-dark); text-decoration: none; transition: all 0.3s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </a>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="nav-btn primary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login').'?'.http_build_query(['intended' => url()->full()]) }}" class="nav-btn secondary">Login</a>
                    <a href="{{ $portalDirectory ? route('register.guest') : route('register') }}" class="nav-btn primary">Register</a>
                @endauth
            </div>
        </nav>
    @endif

    @php
        $portalMainClass = $showPortalPublicNav
            ? 'portal-public-main explore-stays-main'
            : 'mx-auto w-full max-w-[1280px] px-5 pb-20 sm:px-8 lg:px-10';
        $mainShellClass = $showClientNav
            ? 'client-guest-main client-guest-main--wide explore-stays-main'
            : $portalMainClass;
    @endphp
    <main class="{{ $mainShellClass }}"@if($showLegacyNav) style="padding-top: 30px;"@endif>
        <header class="explore-stays-hero">
            <div class="explore-stays-hero__copy">
                <p class="explore-stays-hero__eyebrow">Stay in Impasug-ong</p>
                <h1 class="explore-stays-hero__title">Find your perfect stay</h1>
                <p class="explore-stays-hero__lede">
                    Traveller-inns, Airbnb stays, and daily rentals — curated from local hosts across the municipality.
                </p>
                @if(isset($accommodations) && method_exists($accommodations, 'total'))
                    <p class="explore-stays-hero__count">
                        {{ $accommodations->total() }} {{ Str::plural('property', $accommodations->total()) }} available
                    </p>
                @endif
            </div>
            <div class="explore-stays-hero__logos" aria-hidden="true">
                @include('tenant.partials.auth-brand-logos', ['tenant' => \App\Models\Tenant::current()])
            </div>
        </header>

        <form action="{{ $listingUrl }}" method="GET" class="explore-stays-filters">
            <div class="explore-stays-filters__grid">
                <div class="explore-stays-field explore-stays-field--type">
                    <label for="dashboard-filter-type">Type</label>
                    <select id="dashboard-filter-type" name="type">
                        <option value="">All</option>
                        <option value="traveller-inn" {{ request('type') == 'traveller-inn' ? 'selected' : '' }}>Traveller-Inn</option>
                        <option value="airbnb" {{ request('type') == 'airbnb' ? 'selected' : '' }}>Airbnb</option>
                        <option value="daily-rental" {{ request('type') == 'daily-rental' ? 'selected' : '' }}>Daily Rental</option>
                    </select>
                </div>
                <div class="explore-stays-field explore-stays-field--min">
                    <label for="dashboard-filter-min-price">Min price</label>
                    <input type="number" id="dashboard-filter-min-price" name="min_price" placeholder="₱ 0" value="{{ request('min_price') }}">
                </div>
                <div class="explore-stays-field explore-stays-field--max">
                    <label for="dashboard-filter-max-price">Max price</label>
                    <input type="number" id="dashboard-filter-max-price" name="max_price" placeholder="₱ 10,000" value="{{ request('max_price') }}">
                </div>
                <div class="explore-stays-field explore-stays-field--guests">
                    <label for="dashboard-filter-guests">Guests</label>
                    <select id="dashboard-filter-guests" name="guests">
                        <option value="">Any</option>
                        <option value="1" {{ request('guests') == '1' ? 'selected' : '' }}>1 guest</option>
                        <option value="2" {{ request('guests') == '2' ? 'selected' : '' }}>2 guests</option>
                        <option value="3" {{ request('guests') == '3' ? 'selected' : '' }}>3 guests</option>
                        <option value="4" {{ request('guests') == '4' ? 'selected' : '' }}>4 guests</option>
                        <option value="5" {{ request('guests') == '5' ? 'selected' : '' }}>5+ guests</option>
                    </select>
                </div>
                <div class="explore-stays-field explore-stays-field--search">
                    <label for="dashboard-filter-search">Search</label>
                    <input type="text" id="dashboard-filter-search" name="search" placeholder="Property name, location…" value="{{ request('search') }}" aria-label="Search properties">
                </div>
                <div class="explore-stays-field explore-stays-field--submit">
                    <label class="sr-only" for="dashboard-filter-submit">Search</label>
                    <button type="submit" id="dashboard-filter-submit" class="explore-stays-search-btn">Search</button>
                </div>
            </div>
        </form>

        @if(isset($accommodations) && count($accommodations) > 0)
            <section class="explore-stays-results" aria-label="Property listings">
                <div class="explore-stays-grid">
                    @foreach($accommodations as $accommodation)
                        <article class="explore-stay-card">
                            <a href="{{ $showUrl($accommodation) }}" class="explore-stay-card__media">
                                @if($accommodation->primary_image)
                                    <img src="{{ $accommodation->primary_image_url }}" alt="{{ $accommodation->name }}" loading="lazy" decoding="async">
                                @else
                                    <img src="/COMMUNAL.jpg" alt="{{ $accommodation->name }}" loading="lazy" decoding="async">
                                @endif
                                <span class="explore-stay-card__type">{{ str_replace('-', ' ', $accommodation->type) }}</span>
                            </a>
                            <div class="explore-stay-card__body">
                                <div class="explore-stay-card__head">
                                    <h2 class="explore-stay-card__title">
                                        <a href="{{ $showUrl($accommodation) }}" class="text-inherit no-underline hover:underline">
                                            {{ $accommodation->name }}
                                        </a>
                                    </h2>
                                    <button type="button" class="explore-stay-card__fav property-favorite" title="Add to favorites" aria-label="Add to favorites">
                                        <i class="fa-regular fa-heart" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <p class="explore-stay-card__location">
                                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                    <span>{{ $accommodation->address }}, Brgy. {{ $accommodation->barangay }}</span>
                                </p>
                                <p class="explore-stay-card__meta">
                                    {{ $accommodation->bedrooms ?? 1 }} bed · {{ $accommodation->bathrooms ?? 1 }} bath · up to {{ $accommodation->max_guests ?? 2 }} guests
                                </p>
                                <p class="explore-stay-card__rating">
                                    <i class="fa-solid fa-star" aria-hidden="true"></i>
                                    <strong>5.0</strong> ({{ $accommodation->total_reviews ?? 0 }} reviews)
                                </p>
                                <p class="explore-stay-card__price">
                                    ₱{{ number_format($accommodation->price_per_night, 0, '.', ',') }}
                                    <span>/ night</span>
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            @if(method_exists($accommodations, 'links'))
                <nav class="explore-stays-pagination" aria-label="Property pages">
                    {{ $accommodations->withQueryString()->links() }}
                </nav>
            @endif
        @else
            <section class="explore-stays-empty" aria-label="No results">
                <div class="explore-stays-empty__card">
                    <i class="fa-solid fa-building" aria-hidden="true"></i>
                    <h3>No properties found</h3>
                    <p>Try adjusting your filters or search criteria.</p>
                </div>
            </section>
        @endif
    </main>

    <script>
        document.querySelectorAll('button.property-favorite').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (!icon) return;
                if (icon.classList.contains('fa-regular')) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    this.classList.remove('text-gray-600');
                    this.classList.add('text-red-500');
                } else {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    this.classList.add('text-gray-600');
                    this.classList.remove('text-red-500');
                }
            });
        });
    </script>
</body>
</html>
