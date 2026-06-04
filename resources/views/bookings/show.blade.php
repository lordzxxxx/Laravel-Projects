<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Booking Details - Impasugong Accommodations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.appearance-boot')
    <style>
        @include('partials.typography-system')
        @include('partials.ui-foundation-styles')
        @php
            $authUser = auth()->user();
            $currentTenant = \App\Models\Tenant::current();
            $isTenantManager = $authUser && (
                $authUser->isOwner()
                || ($authUser->isAdmin() && $currentTenant && (int) $authUser->tenant_id === (int) $currentTenant->id)
            );
            $useLegacyBookingsNav = ! $isTenantManager && ! $authUser?->isClient();
            $bookingRouteGroup = $currentTenant ? 'bookings' : 'portal.bookings';
        @endphp
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            @include('partials.tenant-theme-css-vars')
            --gray-50: #F9FAFB; --gray-100: #F3F4F6; --gray-200: #E5E7EB;
            --gray-500: #6B7280; --gray-600: #4B5563; --gray-700: #374151;
            --gray-800: #1F2937;
        }

        @if($useLegacyBookingsNav)
        .navbar {
            background: var(--white);
            padding: 0 40px;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 20px rgba(27, 94, 32, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo img { width: 45px; height: 45px; border-radius: 0; border: none; object-fit: contain; }
        .nav-logo span { font-size: 1.2rem; font-weight: 700; color: var(--green-dark); }
        .nav-links { display: flex; gap: 25px; list-style: none; }
        .nav-links a { text-decoration: none; color: var(--gray-600); font-weight: 500; padding: 8px 12px; border-radius: 8px; transition: all 0.3s; }
        .nav-links a:hover, .nav-links a.active { background: var(--green-soft); color: var(--green-dark); }
        .nav-actions { display: flex; gap: 15px; align-items: center; }
        .nav-btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.3s; cursor: pointer; border: none; }
        .nav-btn.primary { background: var(--green-primary); color: var(--white); }
        .nav-btn.secondary { background: var(--green-soft); color: var(--green-dark); }
        @endif

        @if($isTenantManager)
            @include('owner.partials.top-navbar-styles')
        @elseif($authUser?->isClient())
            @include('client.partials.top-navbar-styles')
            @include('client.partials.guest-shell-styles')
        @endif

        body {
            min-height: 100dvh;
            color: var(--gray-800);
        }

        main.booking-show-page {
            display: flex;
            flex-direction: column;
            flex: 1 1 0;
            min-height: 0;
        }

        body.client-nav-page main.booking-show-page.client-guest-main {
            flex: 1 1 0;
            min-height: 0;
            padding-bottom: clamp(1.25rem, 2.5vw, 2rem);
        }

        body.owner-nav-page main.booking-show-page.with-owner-nav {
            flex: 1 1 0;
            min-height: 0;
        }

        .booking-show-inner {
            --stay-max: 78rem;
            width: 100%;
            max-width: var(--stay-max);
            margin-left: auto;
            margin-right: auto;
            flex: 1 1 0;
            min-height: 0;
            display: flex;
            flex-direction: column;
            gap: clamp(0.75rem, 1.5vw, 1rem);
        }

        .booking-show-toolbar {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: clamp(0.75rem, 1.5vw, 1rem);
        }

        .booking-show-card {
            flex: 1 1 0;
            min-height: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 1rem;
            border: 1px solid rgba(16, 185, 129, 0.15);
            background: #fff;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.06);
        }

        .booking-show-panel {
            border-radius: 0.75rem;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: rgba(248, 250, 252, 0.85);
            padding: 1rem 1.125rem;
        }

        @media (min-width: 640px) {
            .booking-show-panel {
                padding: 1.125rem 1.25rem;
            }
        }

        @include('client.partials.guest-booking-detail-styles')
    </style>
</head>
<body class="{{ $isTenantManager ? 'owner-nav-page' : ($authUser?->isClient() ? 'client-nav-page font-sans text-slate-800 antialiased' : '') }} flex min-h-screen flex-col {{ $authUser?->isClient() ? '' : 'bg-gradient-to-br from-emerald-50 via-lime-50/90 to-emerald-100 text-slate-900 antialiased' }}">
    @if($isTenantManager)
        @include('owner.partials.top-navbar')
    @elseif(auth()->user()?->isClient())
        @include('client.partials.top-navbar', ['active' => 'bookings', 'portalDirectory' => $portalDirectory ?? false])
    @else
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="nav-logo">
            <img src="/SYSTEMLOGO.png" alt="IMPASUGONG TOURISM">
            <span class="nav-brand-text">
                <span class="nav-brand-title">IMPASUGONG TOURISM</span>
                <span class="nav-brand-subtitle">| Bookings</span>
            </span>
        </a>

        <ul class="nav-links">
            @auth
                @if(Auth::user()->isAdmin())
                    @php
                        $adminDashboardHref = \App\Models\Tenant::checkCurrent() ? '/owner/dashboard' : '/admin/dashboard';
                    @endphp
                    <li><a href="{{ $adminDashboardHref }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">Dashboard</a></li>
                @elseif(Auth::user()->isOwner())
                    <li><a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.*') ? 'active' : '' }}">Dashboard</a></li>
                @else
                    <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Browse</a></li>
                @endif
            @endauth
            <li><a href="{{ route(Auth::check() && $isTenantManager && \Illuminate\Support\Facades\Route::has('owner.accommodations.index') ? 'owner.accommodations.index' : (\Illuminate\Support\Facades\Route::has('accommodations.index') ? 'accommodations.index' : 'dashboard')) }}" class="{{ request()->routeIs('accommodations.*') || request()->routeIs('owner.accommodations.*') ? 'active' : '' }}">Browse</a></li>
            <li><a href="{{ Auth::check() && $isTenantManager ? route('owner.bookings.index') : route($bookingRouteGroup.'.index') }}" class="{{ request()->routeIs('bookings.*', 'portal.bookings.*') || request()->routeIs('owner.bookings.*') ? 'active' : '' }}">My Bookings</a></li>
            <li><a href="{{ route('messages.index', [], false) }}" class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">Messages</a></li>
            <li><a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Settings</a></li>
        </ul>

        <div class="nav-actions">
            <form action="{{ route('profile.edit') }}" method="GET">
                @csrf
                <button type="submit" class="nav-btn secondary"><i class="fa-solid fa-gear" aria-hidden="true"></i> Settings</button>
            </form>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="nav-btn primary">Logout</button>
            </form>
        </div>
    </nav>
    @endif

    @php
        $bookingsIndexRouteName = Auth::check() && $isTenantManager
            ? 'owner.bookings.index'
            : "{$bookingRouteGroup}.index";
    @endphp

    <main class="booking-show-page flex min-h-0 w-full flex-1 flex-col {{ $isTenantManager ? 'main-content with-owner-nav min-h-[calc(100dvh-5rem)] px-4 pb-6 pt-6 sm:px-6 lg:px-8' : ($authUser?->isClient() ? 'client-guest-main client-guest-main--full flex min-h-0 flex-1 flex-col' : 'min-h-[calc(100dvh-5rem)] px-4 pb-6 pt-6 sm:px-6 lg:px-8') }}"@if(! $isTenantManager && ! $authUser?->isClient()) style="padding-top: calc(var(--client-nav-offset) + 16px);"@endif>
        <div class="booking-show-inner">
            <div class="booking-show-toolbar">
                @include('partials.flash-alerts')

                <a
                    href="{{ route($bookingsIndexRouteName, [], false) }}"
                    class="inline-flex w-max items-center gap-2 text-sm font-semibold text-emerald-800 transition hover:text-emerald-950"
                >
                    <i class="fas fa-arrow-left text-xs" aria-hidden="true"></i>
                    Back to My Bookings
                </a>
            </div>

            @if(isset($booking))
                @php
                    $paymentUi = $booking->payment_ui_state;
                    $paymentToneClass = $paymentUi['tone'] === 'pending_review'
                        ? 'bg-amber-50 text-amber-900 ring-amber-200'
                        : ($paymentUi['tone'] === 'paid'
                            ? 'bg-emerald-50 text-emerald-900 ring-emerald-200'
                            : 'bg-slate-100 text-slate-800 ring-slate-200');
                    $checkIn = \Carbon\Carbon::parse($booking->check_in_date);
                    $checkOut = \Carbon\Carbon::parse($booking->check_out_date);
                    $nights = max(1, $checkIn->diffInDays($checkOut));
                    $isPaymentRecorded = ($paymentUi['tone'] ?? 'neutral') === 'paid';
                    $propertyImage = ($booking->accommodation && $booking->accommodation->primary_image)
                        ? $booking->accommodation->primary_image_url
                        : '/COMMUNAL.jpg';
                @endphp

                @if(Auth::check() && $isTenantManager)
                    <details class="shrink-0 rounded-2xl border border-emerald-100/90 bg-white/95 shadow-sm">
                        <summary class="cursor-pointer list-none px-5 py-4 text-sm font-bold text-emerald-900 sm:px-6 [&::-webkit-details-marker]:hidden">
                            <span class="inline-flex items-center gap-2">
                                <i class="fas fa-qrcode text-emerald-600" aria-hidden="true"></i>
                                GCash QR for guests
                                <i class="fas fa-chevron-down ml-1 text-xs text-slate-400" aria-hidden="true"></i>
                            </span>
                        </summary>
                        <div class="border-t border-slate-100 px-5 pb-5 pt-2 sm:px-6">
                            @if($currentTenant?->getGcashQrUrl())
                                <div class="mb-4 flex flex-wrap items-center gap-4">
                                    <img
                                        src="{{ $currentTenant->getGcashQrUrl() }}"
                                        alt="GCash QR"
                                        class="h-28 w-28 rounded-xl border border-slate-200 object-cover shadow-sm"
                                    >
                                    <form method="POST" action="{{ route('owner.bookings.payment-settings.gcash-qr.remove', [], false) }}" data-loading-form>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" data-loading-button class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                                            Remove QR
                                        </button>
                                    </form>
                                </div>
                            @endif
                            <form
                                method="POST"
                                action="{{ route('owner.bookings.payment-settings.gcash-qr.upload', [], false) }}"
                                enctype="multipart/form-data"
                                class="flex flex-wrap items-center gap-3"
                                data-loading-form
                            >
                                @csrf
                                <input
                                    type="file"
                                    name="gcash_qr"
                                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                    required
                                    class="max-w-full text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-700 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white hover:file:bg-emerald-800"
                                >
                                <button type="submit" data-loading-button class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-800">
                                    {{ $currentTenant?->getGcashQrUrl() ? 'Replace' : 'Upload' }} QR photo
                                </button>
                            </form>
                            @error('gcash_qr')
                                <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                    </details>
                @endif

                <article class="booking-show-card">
                    <header class="booking-show-card__header shrink-0 bg-gradient-to-r from-emerald-900 to-emerald-700 px-5 py-5 text-white sm:px-7 sm:py-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-emerald-200/90">Booking #{{ $booking->id }}</p>
                                <h1 class="mt-1.5 text-xl font-bold leading-tight tracking-tight sm:text-2xl lg:text-[1.65rem]">
                                    {{ $booking->accommodation->name ?? 'Reservation' }}
                                </h1>
                                <p class="mt-1.5 text-sm text-emerald-100">
                                    Booked {{ $booking->created_at->format('M d, Y') }}
                                </p>
                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex rounded-full bg-white/95 px-3 py-1 text-xs font-bold text-emerald-900">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $paymentToneClass }}">
                                        {{ $paymentUi['label'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="booking-show-card__total shrink-0 rounded-xl bg-white/10 px-4 py-3.5 ring-1 ring-inset ring-white/20 backdrop-blur-sm sm:min-w-[11rem]">
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-emerald-100">Total</p>
                                <p class="mt-0.5 text-2xl font-bold tabular-nums tracking-tight sm:text-3xl">₱{{ number_format((float) $booking->total_price, 2) }}</p>
                                <p class="mt-0.5 text-xs text-emerald-100/90">{{ $nights }} night{{ $nights === 1 ? '' : 's' }}</p>
                            </div>
                        </div>
                    </header>

                    <div class="flex min-h-0 flex-1 flex-col lg:flex-row">
                        {{-- Property (medium sidebar) --}}
                        <aside class="booking-show-card__aside flex shrink-0 flex-col border-b border-slate-100 lg:w-[min(100%,20rem)] lg:min-h-0 lg:self-stretch lg:border-b-0 lg:border-r xl:w-72">
                            <div class="booking-show-card__aside-media relative aspect-[4/3] max-h-52 w-full shrink-0 bg-slate-100 lg:aspect-auto lg:h-44 lg:max-h-44">
                                <img
                                    src="{{ $propertyImage }}"
                                    alt="{{ $booking->accommodation->name ?? 'Property' }}"
                                    class="absolute inset-0 h-full w-full object-cover"
                                >
                            </div>
                            <div class="booking-show-card__aside-body space-y-2.5 p-4 sm:p-5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Property</p>
                                <p class="text-base font-bold leading-snug text-slate-900">{{ $booking->accommodation->name ?? 'N/A' }}</p>
                                <p class="flex items-start gap-2 text-sm text-slate-600">
                                    <i class="fas fa-location-dot mt-0.5 shrink-0 text-emerald-600" aria-hidden="true"></i>
                                    <span>{{ $booking->accommodation->address ?? 'Impasugong, Bukidnon' }}</span>
                                </p>
                                <p class="text-xs font-medium uppercase tracking-wide text-emerald-800">
                                    {{ ucfirst(str_replace('-', ' ', $booking->accommodation->type ?? 'Standard')) }}
                                </p>
                                <p class="text-sm leading-relaxed text-slate-600 line-clamp-3">
                                    {{ $booking->accommodation->description ?? 'No description available.' }}
                                </p>
                            </div>
                        </aside>

                        {{-- Details (scrollable, medium cards) --}}
                        <div class="booking-show-card__content flex min-h-0 flex-1 flex-col gap-5 overflow-y-auto p-4 sm:gap-6 sm:p-6 lg:p-7">
                            <section>
                                <h2 class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Stay details</h2>
                                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                    <div class="booking-show-panel border-emerald-100 bg-emerald-50/60">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Check-in</p>
                                        <p class="mt-1 text-base font-bold text-slate-900">{{ $checkIn->format('M d, Y') }}</p>
                                    </div>
                                    <div class="booking-show-panel border-emerald-100 bg-emerald-50/60">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Check-out</p>
                                        <p class="mt-1 text-base font-bold text-slate-900">{{ $checkOut->format('M d, Y') }}</p>
                                    </div>
                                    <div class="booking-show-panel">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Guests</p>
                                        <p class="mt-1 text-base font-bold text-slate-900">{{ $booking->number_of_guests ?? 1 }}</p>
                                    </div>
                                    <div class="booking-show-panel">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Rate / night</p>
                                        <p class="mt-1 text-base font-bold tabular-nums text-slate-900">₱{{ number_format((float) ($booking->accommodation->price_per_night ?? 0), 2) }}</p>
                                    </div>
                                </div>
                            </section>

                            <section class="booking-show-panel">
                                <h2 class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Payment</h2>
                                <p class="mt-1 text-lg font-bold text-slate-900">{{ $paymentUi['label'] }}</p>
                                <dl class="mt-4 grid gap-x-5 gap-y-3 text-sm sm:grid-cols-2">
                                    <div>
                                        <dt class="text-xs text-slate-500">Channel</dt>
                                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $paymentUi['channel'] }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-slate-500">Method</dt>
                                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $paymentUi['method'] }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-slate-500">Reference</dt>
                                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $paymentUi['reference'] }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-slate-500">Paid at</dt>
                                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $paymentUi['paid_at'] ? $paymentUi['paid_at']->format('M d, Y h:i A') : '—' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-slate-500">Proof submitted</dt>
                                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $paymentUi['submitted_at'] ? $paymentUi['submitted_at']->format('M d, Y h:i A') : '—' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-slate-500">Reviewed at</dt>
                                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $paymentUi['reviewed_at'] ? $paymentUi['reviewed_at']->format('M d, Y h:i A') : '—' }}</dd>
                                    </div>
                                </dl>
                            </section>

                            @if(Auth::check() && $isTenantManager)
                                <section class="booking-show-panel bg-white">
                                    <h2 class="text-sm font-bold text-slate-900">Client payment proof</h2>
                                    @if($booking->gcash_payment_proof_url)
                                        <a href="{{ $booking->gcash_payment_proof_url }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-block">
                                            <img
                                                src="{{ $booking->gcash_payment_proof_url }}"
                                                alt="Payment proof"
                                                class="max-h-72 w-full max-w-md rounded-xl border border-slate-200 object-contain shadow-sm"
                                            >
                                        </a>
                                    @else
                                        <p class="mt-3 text-sm text-slate-500">No proof screenshot uploaded yet.</p>
                                    @endif
                                </section>
                            @endif

                            @if(isset($booking->messages) && count($booking->messages) > 0)
                                <section class="booking-show-panel">
                                    <h2 class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Conversation</h2>
                                    <ul class="mt-3 max-h-52 space-y-2 overflow-y-auto pr-1">
                                        @foreach($booking->messages as $message)
                                            <li class="rounded-xl border border-emerald-100/80 bg-emerald-50/40 px-4 py-3">
                                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                                    <span class="text-sm font-semibold text-emerald-900">{{ $message->sender->name ?? 'Unknown' }}</span>
                                                    <time class="text-xs text-slate-500">{{ $message->created_at->format('M d, Y h:i A') }}</time>
                                                </div>
                                                <p class="mt-1.5 text-sm leading-relaxed text-slate-700">{{ $message->content }}</p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </section>
                            @endif

                            @if(Auth::check() && $isTenantManager && $booking->status === 'pending')
                                <div class="booking-show-card__actions mt-auto flex flex-wrap gap-2.5 border-t border-slate-100 pt-5">
                                    <form action="{{ route('owner.bookings.update-status', $booking, false) }}" method="POST" data-loading-form>
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" data-loading-button class="rounded-xl bg-emerald-700 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-emerald-800">
                                            Approve booking
                                        </button>
                                    </form>
                                    <form action="{{ route('owner.bookings.update-status', $booking, false) }}" method="POST" data-loading-form>
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button
                                            type="submit"
                                            data-loading-button
                                            class="rounded-xl bg-red-600 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-red-700"
                                            onclick="return confirm('Decline this booking request?')"
                                        >
                                            Decline booking
                                        </button>
                                    </form>
                                </div>
                            @elseif(Auth::check() && Auth::user()->isClient() && in_array($booking->status, ['pending', 'confirmed'], true))
                                <div class="booking-show-card__actions mt-auto flex flex-col gap-3 border-t border-slate-100 pt-5">
                                    @if($booking->status === 'pending')
                                        <p class="text-sm leading-relaxed text-slate-600">
                                            Pay with Stripe or upload a GCash proof, then your host will review and approve.
                                        </p>
                                    @endif
                                    <div class="flex flex-wrap gap-3">
                                        @if($booking->status === 'pending' && ! $isPaymentRecorded)
                                            <a
                                                href="{{ route($bookingRouteGroup.'.payment', $booking, false) }}"
                                                class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-emerald-800"
                                            >
                                                Pay / Upload proof
                                            </a>
                                        @endif
                                        <form action="{{ route($bookingRouteGroup.'.cancel', $booking, false) }}" method="POST" class="inline" data-loading-form>
                                            @csrf
                                            @method('PUT')
                                            <button
                                                type="submit"
                                                data-loading-button
                                                class="rounded-xl border-2 border-red-200 bg-white px-6 py-3 text-sm font-bold text-red-700 transition hover:bg-red-50"
                                                onclick="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.')"
                                            >
                                                Cancel booking
                                            </button>
                                        </form>
                                        <a
                                            href="{{ route('messages.index', [], false) }}"
                                            class="inline-flex items-center justify-center rounded-xl border-2 border-emerald-600 bg-white px-6 py-3 text-sm font-bold text-emerald-800 transition hover:bg-emerald-50"
                                        >
                                            Contact host
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @else
                <div class="booking-show-card flex min-h-0 flex-1 flex-col items-center justify-center border-dashed border-emerald-200 bg-white/95 p-10 text-center sm:p-12">
                    <h2 class="text-xl font-bold text-slate-800">Booking not found</h2>
                    <p class="mt-2 max-w-md text-sm text-slate-600">The booking you are looking for does not exist or has been removed.</p>
                    <a
                        href="{{ route($bookingsIndexRouteName, [], false) }}"
                        class="mt-6 inline-flex rounded-xl bg-emerald-700 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-emerald-800"
                    >
                        Back to My Bookings
                    </a>
                </div>
            @endif
        </div>
    </main>
    <script>
        document.querySelectorAll('form[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const button = form.querySelector('[data-loading-button]');
                if (!button) return;
                button.disabled = true;
                button.textContent = 'Processing...';
            });
        });
    </script>
</body>
</html>
