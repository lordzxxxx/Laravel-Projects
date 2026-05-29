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
        @endif

        @include('client.partials.accommodations-listing-styles')

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

    <main class="{{ $showClientNav ? 'client-guest-main' : 'mx-auto w-full max-w-[1280px] px-5 pb-20 sm:px-8 lg:px-10' }}"@if(! $showClientNav) style="padding-top: 30px;"@endif>
        <header class="mb-8 flex flex-col gap-6 sm:mb-10 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0 flex-1 pr-0 lg:pr-4">
                <span class="mb-3 inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-green-700">
                    <span class="h-px w-6 bg-green-700/60"></span>
                    Stay in Impasug-ong
                </span>
                <h1 class="font-display text-3xl font-semibold leading-tight tracking-tight text-gray-900 sm:text-4xl lg:text-[2.6rem]">
                    Find your perfect stay.
                </h1>
                <p class="mt-3 text-[15px] leading-relaxed text-gray-700">
                    Traveller-inns, Airbnb stays, and daily rentals — curated from local hosts across the municipality.
                </p>
                @if(isset($accommodations) && method_exists($accommodations, 'total'))
                    <div class="mt-4 text-xs font-semibold uppercase tracking-[0.15em] text-gray-700">
                        {{ $accommodations->total() }} {{ Str::plural('property', $accommodations->total()) }} available
                    </div>
                @endif
            </div>
            @include('client.partials.accommodation-header-logos')
        </header>

        <form action="{{ $listingUrl }}" method="GET" class="mb-10 rounded-2xl border border-gray-200 bg-white/95 px-4 py-4 shadow-[0_2px_8px_rgba(15,23,42,0.06)] backdrop-blur-sm sm:px-5">
            <div class="grid gap-x-4 gap-y-4 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-2">
                    <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.14em] text-gray-700">Type</label>
                    <select name="type" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-800 transition focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">
                        <option value="">All</option>
                        <option value="traveller-inn" {{ request('type') == 'traveller-inn' ? 'selected' : '' }}>Traveller-Inn</option>
                        <option value="airbnb" {{ request('type') == 'airbnb' ? 'selected' : '' }}>Airbnb</option>
                        <option value="daily-rental" {{ request('type') == 'daily-rental' ? 'selected' : '' }}>Daily Rental</option>
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.14em] text-gray-700">Min Price</label>
                    <input type="number" name="min_price" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="₱ 0" value="{{ request('min_price') }}">
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.14em] text-gray-700">Max Price</label>
                    <input type="number" name="max_price" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="₱ 10,000" value="{{ request('max_price') }}">
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.14em] text-gray-700">Guests</label>
                    <select name="guests" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-800 transition focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">
                        <option value="">Any</option>
                        <option value="1" {{ request('guests') == '1' ? 'selected' : '' }}>1 Guest</option>
                        <option value="2" {{ request('guests') == '2' ? 'selected' : '' }}>2 Guests</option>
                        <option value="3" {{ request('guests') == '3' ? 'selected' : '' }}>3 Guests</option>
                        <option value="4" {{ request('guests') == '4' ? 'selected' : '' }}>4 Guests</option>
                        <option value="5" {{ request('guests') == '5' ? 'selected' : '' }}>5+ Guests</option>
                    </select>
                </div>

                <div class="lg:col-span-3">
                    <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.14em] text-gray-700">Search</label>
                    <input type="text" name="search" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Property name, location..." value="{{ request('search') }}" aria-label="Search properties">
                </div>

                <div class="lg:col-span-1">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-black focus:outline-none focus:ring-2 focus:ring-green-100">Search</button>
                </div>
            </div>
        </form>

        @if(isset($accommodations) && count($accommodations) > 0)
            <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($accommodations as $accommodation)
                    <article class="group flex flex-col">
                        <a href="{{ $showUrl($accommodation) }}" class="relative block aspect-[4/3] w-full overflow-hidden rounded-xl bg-gray-100">
                            @if($accommodation->primary_image)
                                <img src="{{ $accommodation->primary_image_url }}" alt="{{ $accommodation->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                            @else
                                <img src="/COMMUNAL.jpg" alt="{{ $accommodation->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                            @endif
                            <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-gray-800 shadow-sm backdrop-blur">{{ str_replace('-', ' ', $accommodation->type) }}</span>
                        </a>

                        <div class="mt-4 flex flex-col gap-2">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-[15px] font-semibold leading-snug text-gray-900 line-clamp-1">{{ $accommodation->name }}</h3>
                                @guest
                                <a href="{{ route('login').'?'.http_build_query(['intended' => url()->full()]) }}" class="property-favorite -mt-0.5 inline-flex h-7 w-7 flex-none items-center justify-center rounded-full text-gray-600 transition hover:text-red-500" title="Sign in to save to wishlist" aria-label="Sign in to save to wishlist"><i class="fa-regular fa-heart text-sm" aria-hidden="true"></i></a>
                                @else
                                <button type="button" class="property-favorite -mt-0.5 inline-flex h-7 w-7 flex-none items-center justify-center rounded-full text-gray-600 transition hover:text-red-500" title="Add to favorites" aria-label="Add to favorites"><i class="fa-regular fa-heart text-sm" aria-hidden="true"></i></button>
                                @endguest
                            </div>

                            <div class="flex items-center gap-1.5 text-[13px] text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="line-clamp-1">{{ $accommodation->address }}, Brgy. {{ $accommodation->barangay }}</span>
                            </div>

                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[12px] text-gray-700">
                                <span>{{ $accommodation->bedrooms ?? 1 }} bed</span>
                                <span class="text-gray-400">·</span>
                                <span>{{ $accommodation->bathrooms ?? 1 }} bath</span>
                                <span class="text-gray-400">·</span>
                                <span>up to {{ $accommodation->max_guests ?? 2 }} guests</span>
                            </div>

                            <div class="mt-1 flex items-center gap-1.5 text-[12px] text-gray-700">
                                <i class="fa-solid fa-star text-[11px] text-amber-500" aria-hidden="true"></i>
                                <span class="font-semibold text-gray-900">5.0</span>
                                <span class="text-gray-600">({{ $accommodation->total_reviews ?? 0 }} reviews)</span>
                            </div>

                            <div class="mt-2 flex items-baseline gap-1.5">
                                <span class="text-[17px] font-semibold text-gray-900">₱{{ number_format($accommodation->price_per_night, 0, '.', ',') }}</span>
                                <span class="text-[13px] text-gray-700">/ night</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if(method_exists($accommodations, 'links'))
                <div class="mt-14 flex justify-center">
                    {{ $accommodations->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="mx-auto max-w-md rounded-2xl border border-gray-200 bg-white/95 px-6 py-16 text-center shadow-[0_2px_8px_rgba(15,23,42,0.06)] backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-5 h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="mb-1.5 text-lg font-semibold text-gray-900">No properties found</h3>
                <p class="text-sm text-gray-700">Try adjusting your filters or search criteria.</p>
            </div>
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
