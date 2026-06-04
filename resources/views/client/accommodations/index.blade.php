@php
    $portalDirectory = $portalDirectory ?? false;
    $showClientNav = auth()->user()?->isClient() === true;
    $showPortalPublicNav = $portalDirectory && ! auth()->check();
    $showLegacyNav = ! $showClientNav && ! $showPortalPublicNav;
@endphp
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
    @endphp
    @include('partials.tenant-favicon')
    <title>Properties - Impasugong Accommodations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @include('partials.app-vite-head')
    <style>
        @if($showPortalPublicNav)
            @include('partials.central-portal-shell-styles')
        @else
            @include('partials.typography-system')
            :root {
                @include('partials.tenant-theme-css-vars')
                --gray-200: #E5E7EB; --gray-500: #6B7280; --gray-600: #4B5563; --gray-700: #374151; --gray-800: #1F2937;
            }
        @endif
        
        @if($showLegacyNav)
        /* Legacy fixed nav (non–client users on this page) */
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
        @endif

        @include('client.partials.guest-stays-browse-styles')

        body.explore-portal-page .explore-stays-main.portal-public-main {
            padding: var(--portal-content-below-nav, calc(var(--app-topbar-height, 4rem) + clamp(1.25rem, 2vw, 1.875rem)))
                clamp(1rem, 2.5vw, 2rem)
                clamp(2rem, 4vw, 3rem);
        }

        body.client-nav-page .explore-stays-main.client-guest-main {
            padding-left: clamp(1rem, 2.5vw, 2rem);
            padding-right: clamp(1rem, 2.5vw, 2rem);
            padding-bottom: clamp(2rem, 4vw, 3rem);
        }


        @if($showLegacyNav)
            @include('partials.legacy-navbar-responsive')
        @endif

    </style>
</head>
<body class="{{ $showClientNav ? 'client-nav-page font-sans text-gray-800' : ($showPortalPublicNav ? 'explore-portal-page font-sans text-gray-800' : 'app-bg-fixed-safe min-h-[100dvh] font-sans text-gray-800 bg-cover bg-center bg-fixed') }}"@if(! $showClientNav && ! $showPortalPublicNav) style="background-image: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 50%, rgba(27, 94, 32, 0.1) 100%), url('/COMMUNAL.jpg');"@endif>
    <!-- Navigation -->
    @if($showClientNav)
    @include('client.partials.top-navbar', ['active' => 'accommodations', 'portalDirectory' => $portalDirectory])
    @elseif($showPortalPublicNav)
    @include('partials.portal-public-nav', [
        'active' => 'browse',
        'municipalityName' => config('portals.municipality_name', 'Impasug-ong'),
        'navLayout' => 'minimal',
    ])
    @else
    <nav class="navbar legacy-navbar-responsive">
        <a href="{{ $portalDirectory ? route('portal.landing') : route('dashboard') }}" class="nav-logo">
            <img src="/SYSTEMLOGO.png" alt="ImpaStay Logo">
            <span>Impasugong</span>
        </a>
        
        <ul class="nav-links">
            @auth
                <li><a href="{{ route('dashboard') }}">Browse</a></li>
                <li><a href="{{ $portalDirectory ? route('portal.accommodations.index') : route('accommodations.index') }}" class="active">Accommodations</a></li>
                <li><a href="{{ $portalDirectory ? route('portal.bookings.index') : route('bookings.index') }}">My Bookings</a></li>
                <li><a href="{{ route('messages.index', [], false) }}">Messages</a></li>
                <li><a href="{{ route('profile.edit') }}">Settings</a></li>
            @else
                <li><a href="{{ $portalDirectory ? route('portal.accommodations.index') : route('accommodations.index') }}" class="active">Accommodations</a></li>
            @endauth
        </ul>

        <button type="button" class="legacy-nav-toggle" aria-label="Open menu" aria-expanded="false">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>
        
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
            <a href="{{ route('register') }}" class="nav-btn primary">Register</a>
            @endauth
        </div>
    </nav>
    @endif
    
    <!-- Main Content -->
    @php
        $mainShellClass = $showClientNav
            ? 'client-guest-main client-guest-main--full explore-stays-main'
            : ($portalDirectory
                ? 'portal-public-main explore-stays-main'
                : 'explore-stays-main mx-auto w-full px-5 pb-20 sm:px-8 lg:px-10');
    @endphp
    <main class="{{ $mainShellClass }}"@if(! $showClientNav && ! $showPortalPublicNav) style="padding-top: 30px;"@endif>
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
                @include('partials.partner-logos-strip', ['tenant' => ($portalDirectory ?? false) ? null : \App\Models\Tenant::current()])
            </div>
        </header>

        <form
            action="{{ ($portalDirectory ?? false) ? route('portal.accommodations.index') : route('accommodations.index') }}"
            method="GET"
            class="explore-stays-filters"
        >
            <div class="explore-stays-filters__grid">
                <div class="explore-stays-field explore-stays-field--type">
                    <label for="filter-type">Type</label>
                    <select id="filter-type" name="type">
                        <option value="">All</option>
                        <option value="traveller-inn" {{ request('type') == 'traveller-inn' ? 'selected' : '' }}>Traveller-Inn</option>
                        <option value="airbnb" {{ request('type') == 'airbnb' ? 'selected' : '' }}>Airbnb</option>
                        <option value="daily-rental" {{ request('type') == 'daily-rental' ? 'selected' : '' }}>Daily Rental</option>
                    </select>
                </div>
                <div class="explore-stays-field explore-stays-field--min">
                    <label for="filter-min-price">Min price</label>
                    <input type="number" id="filter-min-price" name="min_price" placeholder="₱ 0" value="{{ request('min_price') }}">
                </div>
                <div class="explore-stays-field explore-stays-field--max">
                    <label for="filter-max-price">Max price</label>
                    <input type="number" id="filter-max-price" name="max_price" placeholder="₱ 10,000" value="{{ request('max_price') }}">
                </div>
                <div class="explore-stays-field explore-stays-field--guests">
                    <label for="filter-guests">Guests</label>
                    <select id="filter-guests" name="guests">
                        <option value="">Any</option>
                        <option value="1" {{ request('guests') == '1' ? 'selected' : '' }}>1 guest</option>
                        <option value="2" {{ request('guests') == '2' ? 'selected' : '' }}>2 guests</option>
                        <option value="3" {{ request('guests') == '3' ? 'selected' : '' }}>3 guests</option>
                        <option value="4" {{ request('guests') == '4' ? 'selected' : '' }}>4 guests</option>
                        <option value="5" {{ request('guests') == '5' ? 'selected' : '' }}>5+ guests</option>
                    </select>
                </div>
                <div class="explore-stays-field explore-stays-field--search">
                    <label for="filter-search">Search</label>
                    <input type="text" id="filter-search" name="search" placeholder="Property name, location…" value="{{ request('search') }}" aria-label="Search properties">
                </div>
                <div class="explore-stays-field explore-stays-field--submit">
                    <label class="sr-only" for="filter-submit">Search</label>
                    <button type="submit" id="filter-submit" class="explore-stays-search-btn">Search</button>
                </div>
            </div>
        </form>

        @if(isset($accommodations) && count($accommodations) > 0)
            <div class="guest-property-grid">
                @foreach($accommodations as $accommodation)
                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="relative h-44 overflow-hidden">
                            <x-accommodation-image :accommodation="$accommodation" :alt="$accommodation->name" class="h-full w-full object-cover transition duration-300 hover:scale-105" />
                            <span class="absolute left-3 top-3 rounded-full bg-green-700 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">{{ str_replace('-', ' ', $accommodation->type) }}</span>
                            @guest
                            <a href="{{ route('login').'?'.http_build_query(['intended' => url()->full()]) }}" class="property-favorite absolute right-3 top-3 flex h-9 w-9 items-center justify-center rounded-full bg-white text-base shadow-sm transition hover:scale-110 hover:bg-green-50 text-red-500" title="Sign in to save to wishlist" aria-label="Sign in to save to wishlist"><i class="fa-regular fa-heart" aria-hidden="true"></i></a>
                            @else
                            <button type="button" class="property-favorite absolute right-3 top-3 flex h-9 w-9 items-center justify-center rounded-full bg-white text-base shadow-sm transition hover:scale-110 hover:bg-green-50 text-gray-600" title="Add to favorites" aria-label="Add to favorites"><i class="fa-regular fa-heart" aria-hidden="true"></i></button>
                            @endguest
                        </div>
                        
                        <div class="p-4">
                            <div class="mb-2 text-xl font-bold text-green-700">₱{{ number_format($accommodation->price_per_night, 0, '.', ',') }} <span class="text-sm font-normal text-gray-500">/ night</span></div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-800">{{ $accommodation->name }}</h3>
                            <div class="mb-3 flex items-center gap-2 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $accommodation->address }}, Brgy. {{ $accommodation->barangay }}
                            </div>
                            
                            <p class="mb-3 line-clamp-2 text-sm text-gray-600">{{ Str::limit($accommodation->description, 100) }}</p>
                            
                            <div class="mb-3 flex flex-wrap gap-3 border-t border-gray-200 pt-3">
                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                    {{ $accommodation->bedrooms ?? 1 }} Bed
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                    </svg>
                                    {{ $accommodation->bathrooms ?? 1 }} Bath
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    {{ $accommodation->max_guests ?? 2 }} Guests
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

            @if(isset($accommodations) && method_exists($accommodations, 'links'))
                <nav class="explore-stays-pagination" aria-label="Property pages">
                    {{ $accommodations->links() }}
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
        // Favorite toggle (authenticated listing only)
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
