<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Checkout — Booking #{{ $booking->id }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.appearance-boot')
    @php
        $portalDirectory = $portalDirectory ?? false;
        $currentTenant = \App\Models\Tenant::current();
        $bookingRouteGroup = $currentTenant ? 'bookings' : 'portal.bookings';
        $bookingsIndexRoute = $portalDirectory ? 'portal.bookings.index' : ($currentTenant ? 'bookings.index' : 'portal.bookings.index');
        $showRoute = route($bookingRouteGroup.'.show', $booking, false);
        $stripeConfigured = filled(config('services.stripe.secret'));
        $gcashQrUrl = $currentTenant?->getGcashQrUrl();
        $isPaid = in_array($booking->status, ['paid', 'completed'], true);
        $isCancelled = $booking->status === 'cancelled';
        $canPay = ! $isPaid && ! $isCancelled;
        $checkIn = $booking->check_in_date;
        $checkOut = $booking->check_out_date;
        $nights = ($checkIn && $checkOut) ? max(1, $checkIn->diffInDays($checkOut)) : null;
    @endphp
    <style>
        @include('partials.typography-system')
        @include('partials.ui-foundation-styles')
        @include('client.partials.top-navbar-styles')
        @include('client.partials.guest-shell-styles')

        :root {
            @include('partials.tenant-theme-css-vars')
        }
    </style>
</head>
<body class="client-nav-page font-sans text-slate-800 antialiased">
    @include('client.partials.top-navbar', ['active' => 'bookings', 'portalDirectory' => $portalDirectory])

    <main class="client-guest-main client-guest-main--full flex min-h-0 flex-1 flex-col">
        <div class="mx-auto flex w-full min-h-0 flex-1 flex-col gap-5">
            @include('partials.flash-alerts')

            <a
                href="{{ $showRoute }}"
                class="inline-flex w-max items-center gap-2 text-sm font-semibold text-emerald-800 transition hover:text-emerald-950"
            >
                <i class="fas fa-arrow-left text-xs" aria-hidden="true"></i>
                Back to booking details
            </a>

            <article class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-2xl border border-emerald-100/90 bg-white shadow-lg">
                <header class="shrink-0 bg-gradient-to-r from-emerald-900 to-emerald-700 px-5 py-6 text-white sm:px-8 sm:py-8">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-emerald-200/90">Secure checkout</p>
                            <h1 class="mt-2 text-2xl font-bold leading-tight tracking-tight sm:text-3xl">
                                {{ $booking->accommodation->name ?? 'Your stay' }}
                            </h1>
                            <p class="mt-2 text-sm text-emerald-100">
                                Booking #{{ $booking->id }}
                                <span class="mx-2 text-emerald-300/80" aria-hidden="true">·</span>
                                {{ ucfirst($booking->status) }}
                            </p>
                        </div>
                        <div class="shrink-0 rounded-2xl bg-white/10 px-5 py-4 ring-1 ring-inset ring-white/20 backdrop-blur-sm sm:min-w-[12rem]">
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-100">Amount due</p>
                            <p class="mt-1 text-3xl font-bold tabular-nums tracking-tight sm:text-4xl">
                                ₱{{ number_format((float) $booking->total_price, 2) }}
                            </p>
                            @if($nights)
                                <p class="mt-1 text-xs text-emerald-100/90">{{ $nights }} night{{ $nights === 1 ? '' : 's' }}</p>
                            @endif
                        </div>
                    </div>
                </header>

                <div class="flex min-h-0 flex-1 flex-col lg:flex-row">
                    {{-- Booking summary --}}
                    <section class="flex flex-col border-b border-slate-100 p-5 sm:p-8 lg:w-[min(100%,22rem)] lg:shrink-0 lg:border-b-0 lg:border-r xl:w-80">
                        <h2 class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Reservation</h2>

                        <dl class="mt-5 flex flex-1 flex-col gap-4">
                            <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                                <dt class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Check-in</dt>
                                <dd class="mt-1 text-base font-bold text-slate-900">{{ $checkIn?->format('M d, Y') ?? '—' }}</dd>
                            </div>
                            <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                                <dt class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Check-out</dt>
                                <dd class="mt-1 text-base font-bold text-slate-900">{{ $checkOut?->format('M d, Y') ?? '—' }}</dd>
                            </div>
                            <div class="rounded-xl border border-slate-200/80 bg-slate-50/80 px-4 py-3.5">
                                <dt class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Guests</dt>
                                <dd class="mt-1 text-base font-bold text-slate-900">{{ $booking->number_of_guests }}</dd>
                            </div>
                        </dl>

                        @if($booking->status === 'pending')
                            <p class="mt-6 rounded-xl border border-sky-200/80 bg-sky-50 px-4 py-3 text-sm leading-relaxed text-sky-900">
                                <i class="fas fa-circle-info mr-1.5 text-sky-600" aria-hidden="true"></i>
                                Pay below, then your host reviews and approves the booking.
                            </p>
                        @endif

                        @if($isPaid)
                            <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-900">
                                <p class="font-semibold">Already paid</p>
                                <p class="mt-1 text-emerald-800/90">
                                    Reference: {{ $booking->payment_reference ?? 'On file' }}
                                </p>
                            </div>
                        @elseif($isCancelled)
                            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-900">
                                <p class="font-semibold">Booking cancelled</p>
                                <p class="mt-1 text-red-800/90">This reservation can no longer be paid.</p>
                            </div>
                        @endif

                        <a
                            href="{{ route($bookingsIndexRoute, [], false) }}"
                            class="mt-auto pt-8 text-sm font-semibold text-slate-500 transition hover:text-emerald-800"
                        >
                            View all bookings
                        </a>
                    </section>

                    {{-- Payment methods --}}
                    <section class="flex min-h-0 flex-1 flex-col gap-8 p-5 sm:p-8">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Payment options</h2>
                            <p class="mt-1 max-w-xl text-sm leading-relaxed text-slate-600">
                                Choose card checkout or upload a GCash proof screenshot for manual review.
                            </p>
                        </div>

                        {{-- Stripe --}}
                        <div class="rounded-2xl border border-slate-200/90 bg-slate-50/60 p-5 sm:p-6">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                                        <i class="fab fa-stripe text-lg" aria-hidden="true"></i>
                                    </span>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900">Card via Stripe</h3>
                                        <p class="mt-0.5 text-sm text-slate-600">Secure checkout — redirects to Stripe</p>
                                    </div>
                                </div>
                                @if(! $stripeConfigured)
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900">Unavailable</span>
                                @endif
                            </div>

                            <form
                                action="{{ route($bookingRouteGroup.'.payment.confirm', $booking, false) }}"
                                method="POST"
                                class="mt-5"
                                data-loading-form
                            >
                                @csrf
                                <button
                                    type="submit"
                                    data-loading-button
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-6 py-3.5 text-sm font-bold text-white shadow-md transition hover:bg-emerald-800 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
                                    @disabled(! $stripeConfigured || ! $canPay)
                                >
                                    <i class="fab fa-stripe" aria-hidden="true"></i>
                                    Pay with Stripe
                                </button>
                            </form>

                            @if(! $stripeConfigured)
                                <p class="mt-3 text-sm text-amber-800">Online card payments are not configured for this property yet.</p>
                            @endif
                        </div>

                        {{-- GCash --}}
                        <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm sm:p-6">
                            <div class="flex items-center gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-600 text-white shadow-sm">
                                    <i class="fas fa-mobile-screen text-lg" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">GCash</h3>
                                    <p class="mt-0.5 text-sm text-slate-600">Scan, pay, then upload your receipt screenshot</p>
                                </div>
                            </div>

                            @if($gcashQrUrl)
                                <div class="mt-6 flex flex-col gap-6 sm:flex-row sm:items-start">
                                    <a
                                        href="{{ $gcashQrUrl }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="mx-auto shrink-0 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm ring-1 ring-slate-100 sm:mx-0"
                                    >
                                        <img
                                            src="{{ $gcashQrUrl }}"
                                            alt="GCash QR code"
                                            class="h-40 w-40 rounded-xl object-contain sm:h-44 sm:w-44"
                                        >
                                    </a>
                                    <div class="min-w-0 flex-1 space-y-4">
                                        <ol class="list-decimal space-y-2 pl-5 text-sm leading-relaxed text-slate-600">
                                            <li>Scan the QR code and send <strong class="text-slate-900">₱{{ number_format((float) $booking->total_price, 2) }}</strong>.</li>
                                            <li>Save a screenshot of your successful transfer.</li>
                                            <li>Upload the proof below for host review.</li>
                                        </ol>

                                        <form
                                            action="{{ route($bookingRouteGroup.'.payment-proof.upload', $booking, false) }}"
                                            method="POST"
                                            enctype="multipart/form-data"
                                            class="rounded-xl border border-dashed border-emerald-200 bg-emerald-50/30 p-4"
                                            data-loading-form
                                        >
                                            @csrf
                                            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500" for="gcash_payment_proof">
                                                Proof screenshot
                                            </label>
                                            <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center">
                                                <input
                                                    id="gcash_payment_proof"
                                                    type="file"
                                                    name="gcash_payment_proof"
                                                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                                    required
                                                    @disabled(! $canPay)
                                                    class="w-full text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-700 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white hover:file:bg-emerald-800 disabled:opacity-60"
                                                >
                                                <button
                                                    type="submit"
                                                    data-loading-button
                                                    class="inline-flex shrink-0 items-center justify-center rounded-xl border-2 border-emerald-600 bg-white px-5 py-2.5 text-sm font-bold text-emerald-800 transition hover:bg-emerald-50 disabled:cursor-not-allowed disabled:opacity-50"
                                                    @disabled(! $canPay)
                                                >
                                                    Upload proof
                                                </button>
                                            </div>
                                        </form>

                                        @if($booking->gcash_payment_proof_url)
                                            <div class="flex flex-wrap items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3">
                                                <span class="text-sm font-semibold text-emerald-900">
                                                    <i class="fas fa-check-circle mr-1 text-emerald-600" aria-hidden="true"></i>
                                                    Proof on file
                                                </span>
                                                <a
                                                    href="{{ $booking->gcash_payment_proof_url }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="text-sm font-semibold text-emerald-800 underline decoration-emerald-300 underline-offset-2 hover:text-emerald-950"
                                                >
                                                    View screenshot
                                                </a>
                                                <form
                                                    action="{{ route($bookingRouteGroup.'.payment-proof.remove', $booking, false) }}"
                                                    method="POST"
                                                    data-loading-form
                                                    class="ml-auto"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        data-loading-button
                                                        class="text-sm font-semibold text-red-700 hover:text-red-900"
                                                        @disabled(! $canPay)
                                                    >
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <p class="mt-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950">
                                    The host has not uploaded a GCash QR code yet. Use Stripe or contact them via messages.
                                </p>
                            @endif
                        </div>

                        <div class="mt-auto flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                            <a
                                href="{{ $showRoute }}"
                                class="inline-flex items-center justify-center rounded-xl border-2 border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                            >
                                Cancel and return
                            </a>
                        </div>
                    </section>
                </div>
            </article>
        </div>
    </main>

    <script>
        document.querySelectorAll('form[data-loading-form]').forEach(function (form) {
            form.addEventListener('submit', function () {
                var button = form.querySelector('[data-loading-button]');
                if (!button) return;
                button.disabled = true;
                var label = button.getAttribute('data-loading-label') || 'Processing…';
                if (button.tagName === 'BUTTON') {
                    button.textContent = label;
                }
            });
        });
    </script>
</body>
</html>
