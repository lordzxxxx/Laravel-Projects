<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'IMPASUGONG TOURISM | '.$municipalityName.' Stays'])
    <style>
        /* Accessibility skip-link */
        .skip-link { position: absolute; left: 1rem; top: -999px; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; border: 0; }
        .skip-link:focus { position: absolute; left: 1rem; top: 1rem; z-index: 2000; clip: auto; width: auto; height: auto; overflow: visible; padding: 0.75rem 1rem; margin: 0; }

        /* Cleaner page surface — soft scrim over hero photo so foreground content stays minimal */
        body.portal-root-landing-bg { background: #FAFAF8; min-height: 100vh; }
        body.portal-root-landing-bg::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -2;
            background: url("{{ asset('bg22.jpg') }}") center / cover no-repeat;
            filter: blur(8px);
            transform: scale(1.05);
            opacity: 0.55;
            pointer-events: none;
        }
        body.portal-root-landing-bg::after {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background: linear-gradient(180deg, rgba(255,255,255,0.78) 0%, rgba(255,255,255,0.88) 60%, rgba(250,250,248,0.95) 100%);
            pointer-events: none;
        }

        /* Restrained partner strip */
        .portal-partners {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 1px minmax(0, 1fr) 1px minmax(0, 1fr);
            align-items: center;
            justify-items: center;
            column-gap: clamp(1rem, 3vw, 2.25rem);
        }
        .portal-partners > .divider { width: 1px; height: 2.5rem; background: rgba(15, 23, 42, 0.1); }
        .portal-partners img { display: block; height: auto; max-height: 3.5rem; max-width: 11rem; object-fit: contain; }
        @media (min-width: 640px) {
            .portal-partners img { max-height: 4rem; max-width: 13rem; }
            .portal-partners > .divider { height: 3rem; }
        }
        @media (max-width: 639px) {
            .portal-partners { grid-template-columns: 1fr; row-gap: 1.25rem; }
            .portal-partners > .divider { display: none; }
        }

        /* Featured viewport mask for soft edge */
        .portal-featured-viewport {
            position: relative;
            mask-image: linear-gradient(to right, transparent 0, black 1.5rem, black calc(100% - 1.5rem), transparent 100%);
            -webkit-mask-image: linear-gradient(to right, transparent 0, black 1.5rem, black calc(100% - 1.5rem), transparent 100%);
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="portal-root-landing-bg flex min-h-screen flex-col font-sans text-slate-900 antialiased">
    <a href="#featured-units" class="skip-link rounded-lg bg-white text-sm font-semibold text-emerald-900 shadow-lg ring-2 ring-emerald-700/20">Skip to featured units</a>

    @include('partials.portal-public-nav', ['active' => 'landing', 'municipalityName' => $municipalityName])

    <main id="browse" tabindex="-1" class="flex flex-1 flex-col outline-none">
        {{-- =================== HERO =================== --}}
        <section class="mx-auto flex w-full max-w-6xl flex-col items-center px-6 pb-20 pt-36 text-center md:pt-40 lg:pt-44">
            {{-- Eyebrow --}}
            <p class="mb-6 text-[0.72rem] font-semibold uppercase tracking-[0.28em] text-emerald-800/85">
                Official tourism portal · {{ $municipalityName }}
            </p>

            {{-- H1 --}}
            <h1 class="font-display mb-5 max-w-3xl text-balance text-4xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl lg:text-[3.25rem]">
                Verified stays across
                <span class="text-emerald-700">{{ $municipalityName }}</span>
            </h1>

            {{-- Lede --}}
            <p class="mb-10 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                Browse municipality-approved tuloganan, save favorites, and book with a single guest account.
            </p>

            {{-- Primary CTA pair --}}
            <div class="mb-16 flex w-full max-w-md flex-col items-stretch gap-3 sm:max-w-none sm:w-auto sm:flex-row sm:items-center sm:justify-center sm:gap-4">
                <a
                    href="{{ route('portal.accommodations.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-7 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700 sm:text-base"
                >
                    <i class="fas fa-compass text-sm" aria-hidden="true"></i>
                    Browse listings
                </a>
                <a
                    href="{{ route('register.guest') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white/80 px-7 py-3.5 text-sm font-semibold text-slate-800 backdrop-blur-sm transition hover:border-emerald-300 hover:bg-white hover:text-emerald-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700 sm:text-base"
                >
                    <i class="fas fa-user-plus text-sm" aria-hidden="true"></i>
                    Create guest account
                </a>
            </div>

            {{-- Partner strip --}}
            <div class="w-full max-w-3xl">
                <p class="mb-5 text-center text-[0.7rem] font-semibold uppercase tracking-[0.24em] text-slate-500">
                    Official directory partners
                </p>
                <div class="portal-partners" role="list">
                    <div role="listitem" class="flex items-center justify-center">
                        <img src="{{ asset('SYSTEMLOGO.png') }}" alt="{{ $municipalityName }} — official seal" loading="eager" fetchpriority="high" decoding="async">
                    </div>
                    <span class="divider" aria-hidden="true"></span>
                    <div role="listitem" class="flex items-center justify-center">
                        <img src="{{ asset('Love Impasugong.png') }}" alt="Love Impasugong" loading="eager" decoding="async">
                    </div>
                    <span class="divider" aria-hidden="true"></span>
                    <div role="listitem" class="flex items-center justify-center">
                        <img src="{{ asset('Lgu Socmed Template-02.png') }}" alt="LGU {{ $municipalityName }}" loading="lazy" decoding="async">
                    </div>
                </div>
            </div>
        </section>

        {{-- Subtle divider --}}
        <div class="mx-auto h-px w-full max-w-6xl bg-slate-200/70"></div>

        {{-- =================== FEATURED UNITS =================== --}}
        <section id="featured-units" class="mx-auto w-full max-w-6xl scroll-mt-28 px-6 pb-24 pt-16 md:pt-20">
            <div class="mb-8 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="font-display text-2xl font-bold tracking-tight text-slate-900 md:text-[1.75rem]">
                        Browse available units
                    </h2>
                    <p class="mt-1.5 text-sm text-slate-600 md:text-base">
                        @if($carouselAccommodations->isEmpty())
                            Highlights will appear here as verified listings go live.
                        @else
                            Highlights from the directory — swipe or use the arrows.
                        @endif
                    </p>
                </div>
                @if($carouselAccommodations->isNotEmpty())
                    <a
                        href="{{ route('portal.accommodations.index') }}"
                        class="inline-flex items-center gap-2 self-start text-sm font-semibold text-emerald-700 hover:text-emerald-900 sm:self-auto"
                    >
                        See all listings
                        <i class="fas fa-arrow-right text-[0.7rem]" aria-hidden="true"></i>
                    </a>
                @endif
            </div>

            @if($carouselAccommodations->isEmpty())
                <article
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white"
                    aria-labelledby="featured-empty-heading"
                >
                    <div class="flex flex-col items-center px-8 py-16 text-center">
                        <span class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-xl text-emerald-700 ring-1 ring-inset ring-emerald-100" aria-hidden="true">
                            <i class="fas fa-building-user"></i>
                        </span>
                        <h3 id="featured-empty-heading" class="max-w-md text-lg font-semibold leading-snug text-slate-900 sm:text-xl">
                            Available units will show here soon
                        </h3>
                        <p class="mt-3 max-w-md text-sm leading-relaxed text-slate-600 sm:text-base">
                            Listings publish after municipality-approved hosts verify their stays. Open the directory to see what is already listed.
                        </p>
                        <a
                            href="{{ route('portal.accommodations.index') }}"
                            class="mt-8 inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700"
                        >
                            <i class="fas fa-compass" aria-hidden="true"></i>
                            Open full directory
                        </a>
                    </div>
                </article>
            @else
                <div
                    class="portal-featured-viewport rounded-2xl"
                    role="region"
                    aria-roledescription="carousel"
                    aria-label="Available accommodation highlights"
                >
                    <div
                        id="portalListingTrack"
                        class="portal-listing-track flex items-stretch gap-5 overflow-x-auto scroll-smooth pb-3 pt-1 [scrollbar-width:thin] snap-x snap-mandatory"
                        style="-webkit-overflow-scrolling: touch;"
                        tabindex="0"
                    >
                        @foreach($carouselAccommodations as $acc)
                            <article class="portal-listing-card group flex w-[min(100%,300px)] shrink-0 snap-start flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-[0_8px_24px_-12px_rgba(15,23,42,0.18)] sm:w-[min(100%,320px)]">
                                <a href="{{ route('portal.accommodations.show', $acc) }}" class="relative block aspect-[4/3] overflow-hidden bg-slate-100">
                                    <img
                                        src="{{ $acc->primary_image_url }}"
                                        alt="Photo of {{ $acc->name }}"
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                    @if($acc->is_featured)
                                        <span class="absolute left-3 top-3 inline-flex items-center rounded-md bg-amber-500/95 px-2 py-0.5 text-[0.65rem] font-semibold uppercase tracking-wide text-white shadow-sm">
                                            Featured
                                        </span>
                                    @endif
                                </a>
                                <div class="flex flex-1 flex-col gap-1.5 p-4 text-left">
                                    <h3 class="line-clamp-2 min-h-[2.5rem] text-[0.95rem] font-semibold leading-snug text-slate-900">
                                        {{ $acc->name }}
                                    </h3>
                                    <p class="line-clamp-1 text-xs text-slate-500">
                                        <i class="fas fa-location-dot mr-1 text-slate-400" aria-hidden="true"></i>
                                        {{ $acc->barangay ?? $acc->address }}
                                    </p>
                                    @if($acc->max_guests)
                                        <p class="text-[0.72rem] text-slate-500">
                                            <i class="fas fa-user-group mr-1 text-slate-400" aria-hidden="true"></i>
                                            Up to {{ (int) $acc->max_guests }} guests
                                        </p>
                                    @endif
                                    <p class="mt-auto pt-3 text-[0.95rem] font-bold text-slate-900">
                                        {{ $acc->formatted_price }}
                                        <span class="text-xs font-normal text-slate-500">/ night</span>
                                    </p>
                                    <a
                                        href="{{ route('portal.accommodations.show', $acc) }}"
                                        class="mt-2 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-800"
                                    >
                                        View details
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>

                {{-- Carousel controls --}}
                <div class="mt-6 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            id="portalListingPrev"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 transition hover:border-emerald-300 hover:text-emerald-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700"
                            aria-controls="portalListingTrack"
                            aria-label="Show previous units"
                        >
                            <i class="fas fa-chevron-left text-sm" aria-hidden="true"></i>
                        </button>
                        <div id="portalListingDots" class="flex max-w-[200px] flex-wrap justify-center gap-1.5 sm:max-w-none" role="tablist" aria-label="Carousel position"></div>
                        <button
                            type="button"
                            id="portalListingNext"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 transition hover:border-emerald-300 hover:text-emerald-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700"
                            aria-controls="portalListingTrack"
                            aria-label="Show next units"
                        >
                            <i class="fas fa-chevron-right text-sm" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <script>
                    (function () {
                        var track = document.getElementById('portalListingTrack');
                        var prev = document.getElementById('portalListingPrev');
                        var next = document.getElementById('portalListingNext');
                        var dotsHost = document.getElementById('portalListingDots');
                        if (!track || !prev || !next) return;
                        var cards = track.querySelectorAll('.portal-listing-card');
                        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                        var autoTimer = null;
                        function stepPx() {
                            var slide = cards[0];
                            if (!slide) return 320;
                            var rect = slide.getBoundingClientRect();
                            var gap = 20;
                            return rect.width + gap;
                        }
                        function scrollByDir(dir) {
                            track.scrollBy({ left: dir * stepPx(), behavior: reducedMotion ? 'auto' : 'smooth' });
                        }
                        function updateDots() {
                            if (!dotsHost || !cards.length) return;
                            var i = Math.round(track.scrollLeft / Math.max(1, stepPx()));
                            i = Math.min(cards.length - 1, Math.max(0, i));
                            var buttons = dotsHost.querySelectorAll('button');
                            buttons.forEach(function (b, idx) {
                                b.setAttribute('aria-selected', idx === i ? 'true' : 'false');
                                b.classList.toggle('bg-emerald-700', idx === i);
                                b.classList.toggle('w-5', idx === i);
                                b.classList.toggle('bg-slate-300', idx !== i);
                                b.classList.toggle('w-2', idx !== i);
                            });
                        }
                        if (dotsHost && cards.length) {
                            dotsHost.innerHTML = '';
                            for (var c = 0; c < cards.length; c++) {
                                (function (index) {
                                    var b = document.createElement('button');
                                    b.type = 'button';
                                    b.className = 'h-2 w-2 rounded-full bg-slate-300 transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700';
                                    b.setAttribute('aria-label', 'Go to unit ' + (index + 1));
                                    b.addEventListener('click', function () {
                                        cards[index].scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth', inline: 'start', block: 'nearest' });
                                    });
                                    dotsHost.appendChild(b);
                                })(c);
                            }
                            window.requestAnimationFrame(updateDots);
                        }
                        prev.addEventListener('click', function () { scrollByDir(-1); });
                        next.addEventListener('click', function () { scrollByDir(1); });
                        track.addEventListener('scroll', function () { window.requestAnimationFrame(updateDots); }, { passive: true });
                        function startAuto() {
                            if (reducedMotion || cards.length < 2) return;
                            stopAuto();
                            autoTimer = window.setInterval(function () {
                                var maxScroll = track.scrollWidth - track.clientWidth - 2;
                                if (track.scrollLeft >= maxScroll) {
                                    track.scrollTo({ left: 0, behavior: 'smooth' });
                                } else {
                                    scrollByDir(1);
                                }
                            }, 5500);
                        }
                        function stopAuto() {
                            if (autoTimer) {
                                window.clearInterval(autoTimer);
                                autoTimer = null;
                            }
                        }
                        var wrap = track.closest('.portal-featured-viewport');
                        if (wrap && !reducedMotion) {
                            wrap.addEventListener('mouseenter', stopAuto);
                            wrap.addEventListener('mouseleave', startAuto);
                            wrap.addEventListener('focusin', stopAuto);
                            wrap.addEventListener('focusout', startAuto);
                            startAuto();
                        }
                        track.addEventListener('keydown', function (e) {
                            if (e.key === 'ArrowLeft') { e.preventDefault(); scrollByDir(-1); }
                            else if (e.key === 'ArrowRight') { e.preventDefault(); scrollByDir(1); }
                        });
                    })();
                </script>
            @endif
        </section>
    </main>

    @include('partials.portal-public-footer')
</body>
</html>
