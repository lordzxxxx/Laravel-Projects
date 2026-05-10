<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Guest Dashboard - ImpaStay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            @include('partials.tenant-theme-css-vars')
            --gray-200: #E5E7EB; --gray-300: #D1D5DB; --gray-500: #6B7280;
            --gray-600: #4B5563; --gray-700: #374151; --gray-800: #1F2937;
        }

        @include('client.partials.top-navbar-styles')
        .snapshot-meta { color: var(--gray-500); font-size: 0.82rem; }
    </style>
</head>
<body class="flex min-h-screen flex-col bg-gradient-to-br from-green-50 via-lime-50 to-white text-gray-800">
    <!-- Navigation -->
    @include('client.partials.top-navbar', ['active' => 'dashboard', 'portalDirectory' => $portalDirectory ?? false])
    
    <!-- Main Content -->
    <div class="flex min-h-0 flex-1 flex-col">
        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gradient-to-br from-green-900 to-green-700 px-4 pb-12 pt-40 text-white sm:px-8 lg:px-12">
            <div class="absolute inset-0 bg-[url('/COMMUNAL.jpg')] bg-cover bg-center opacity-10"></div>
            <div class="relative mx-auto w-full max-w-[1800px] text-center">
                <div class="mb-3 flex min-h-[130px] flex-wrap items-center justify-center gap-4 sm:gap-6">
                    <img src="{{ asset('Love Impasugong.png') }}" alt="Love Impasugong" width="140" height="140" class="h-24 w-24 object-contain drop-shadow-[0_4px_12px_rgba(0,0,0,0.4)] sm:h-32 sm:w-32">
                    <img src="{{ asset('SYSTEMLOGO.png') }}" alt="ImpaStay Logo" width="140" height="140" class="h-24 w-24 object-contain drop-shadow-[0_4px_12px_rgba(0,0,0,0.4)] sm:h-32 sm:w-32">
                    <img src="{{ asset('Lgu Socmed Template-02.png') }}" alt="LGU Impasugong" width="140" height="140" class="h-24 w-24 object-contain drop-shadow-[0_4px_12px_rgba(0,0,0,0.4)] sm:h-32 sm:w-32">
                </div>
                <h1 class="mb-2 text-3xl font-bold sm:text-4xl"><i class="fas fa-home mr-2" aria-hidden="true"></i>Find Your Perfect Stay</h1>
                <p class="text-sm text-green-50 sm:text-lg">Discover traveller-inns, Airbnb stays, and daily rentals in Impasugong</p>
            </div>
        </section>
        
        <!-- Property Categories -->
        <section class="mx-auto w-full max-w-[1800px] px-4 py-8 sm:px-6 lg:px-10">
            @if($canManageOwnStays)
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-2xl font-bold text-green-900"><i class="fas fa-chart-line mr-2"></i>My Booking Snapshot</h2>
                <a href="{{ $portalDirectory ? route('portal.bookings.index') : route('bookings.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-green-700 transition hover:text-green-900"><i class="fas fa-arrow-right"></i> Manage Bookings</a>
            </div>

            <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-green-100 bg-white p-4 shadow-sm">
                    <h4 class="mb-2 text-sm font-semibold text-green-900"><i class="fas fa-suitcase-rolling mr-2"></i> Upcoming Trips</h4>
                    <div class="mb-1 text-3xl font-bold text-green-700">{{ $upcomingBookingsCount ?? 0 }}</div>
                    <div class="snapshot-meta">Active stays and confirmed arrivals</div>
                </div>

                <div class="rounded-xl border border-green-100 bg-white p-4 shadow-sm">
                    <h4 class="mb-2 text-sm font-semibold text-green-900"><i class="fas fa-hourglass-half mr-2"></i> Pending Requests</h4>
                    <div class="mb-1 text-3xl font-bold text-green-700">{{ $pendingBookingsCount ?? 0 }}</div>
                    <div class="snapshot-meta">Waiting for owner confirmation</div>
                </div>

                <div class="rounded-xl border border-green-100 bg-white p-4 shadow-sm">
                    <h4 class="mb-2 text-sm font-semibold text-green-900"><i class="fas fa-wallet mr-2"></i> Year-to-Date Spend</h4>
                    <div class="mb-1 text-3xl font-bold text-green-700">₱{{ number_format($ytdSpend ?? 0, 0) }}</div>
                    <div class="snapshot-meta">Paid and completed bookings this year</div>
                </div>

                <div class="rounded-xl border border-green-100 border-l-4 bg-white p-4 shadow-sm">
                    <h4 class="mb-2 text-sm font-semibold text-green-900"><i class="fas fa-plane-departure mr-2"></i> Next Trip</h4>
                    @if($nextUpcomingBooking)
                        <div class="mb-1 text-lg font-bold text-green-800">{{ $nextUpcomingBooking->accommodation->name ?? 'Accommodation' }}</div>
                        <div class="snapshot-meta">
                            {{ optional($nextUpcomingBooking->check_in_date)->format('M d, Y') }} - {{ optional($nextUpcomingBooking->check_out_date)->format('M d, Y') }}
                        </div>
                    @else
                        <div class="text-sm text-gray-600">No upcoming booking yet. Explore available stays.</div>
                    @endif
                </div>
            </div>
            @else
            <div class="mb-4 flex items-center gap-2">
                <h2 class="text-2xl font-bold text-green-900"><i class="fas fa-chart-line mr-2"></i>Bookings</h2>
            </div>
            <p class="mb-6 max-w-2xl text-sm text-gray-600 sm:text-base">Your account does not have permission to create or manage bookings on this site. Contact the business if you need this enabled.</p>
            @endif

            <div class="mb-4 flex items-center gap-2">
                <h2 class="text-2xl font-bold text-green-900"><i class="fas fa-calendar-days mr-2"></i>Room Availability Calendar</h2>
            </div>
            <div class="mb-6 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
                @if(($availabilityAccommodations ?? collect())->isNotEmpty())
                    @include('partials.availability-calendar', [
                        'calendarId' => 'clientDashCal',
                        'availabilityAccommodations' => $availabilityAccommodations,
                        'availabilityEventsByAccommodation' => $availabilityEventsByAccommodation ?? [],
                    ])
                @else
                    <p class="snapshot-meta">No rooms are currently listed for this tenant.</p>
                @endif
            </div>

        </section>
        
        @include('partials.central-public-footer')
    </div>
    
    <script>
        // Simple favorite toggle
        document.querySelectorAll('.property-favorite').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.style.color = '#dc3545';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.style.color = '';
                }
            });
        });
    </script>
</body>
</html>
