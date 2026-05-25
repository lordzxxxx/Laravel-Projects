<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'IMPASUGONG TOURISM'])
    <style>
        .skip-link:focus { position: absolute; left: 1rem; top: 1rem; z-index: 2000; clip: auto; width: auto; height: auto; overflow: visible; padding: 0.75rem 1rem; margin: 0; }
        .skip-link { position: absolute; left: 1rem; top: -999px; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; border: 0; }
        .portal-hero-partners { --partner-logo-h: 6.25rem; --partner-logo-w: 14.5rem; }
        @media (min-width: 640px) {
            .portal-hero-partners { --partner-logo-h: 7.5rem; --partner-logo-w: 17rem; }
        }
        @media (min-width: 1024px) {
            .portal-hero-partners { --partner-logo-h: 8.5rem; --partner-logo-w: 18.5rem; }
        }
        .portal-hero-partners img {
            display: block;
            height: auto;
            width: auto;
            max-height: var(--partner-logo-h);
            max-width: var(--partner-logo-w);
            object-fit: contain;
            object-position: center;
            filter: drop-shadow(0 2px 8px rgba(255, 255, 255, 0.85)) drop-shadow(0 4px 14px rgba(27, 94, 32, 0.12));
        }
        /* Equal-width columns so gaps beside dividers read symmetrically */
        .portal-hero-partners__strip {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: clamp(0.5rem, 1.75vw, 1rem);
            width: 100%;
        }
        .portal-hero-partners__strip > [role="listitem"] {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 0;
        }
        @media (max-width: 639px) {
            .portal-hero-partners__strip {
                grid-template-columns: 1fr;
                row-gap: 2rem;
            }
            .portal-hero-partners__strip .portal-hero-partners__divider {
                display: none;
            }
        }
        .portal-hero-partners__divider {
            height: var(--partner-logo-h);
            width: 1px;
            flex-shrink: 0;
            align-self: center;
            background-color: rgba(255, 255, 255, 0.7);
            box-shadow: 1px 0 0 rgba(27, 94, 32, 0.12);
        }
        .portal-hero-partners img.portal-hero-partners__wide {
            max-height: 5.5rem;
            max-width: min(100%, 22rem);
        }
        @media (min-width: 640px) {
            .portal-hero-partners img.portal-hero-partners__wide {
                max-height: 6.5rem;
                max-width: min(100%, 25rem);
            }
        }
        @media (min-width: 1024px) {
            .portal-hero-partners img.portal-hero-partners__wide {
                max-height: 7.25rem;
                max-width: min(100%, 29rem);
            }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Root landing only: bg22.jpg + blurred photo + 60% white scrim */
        body.portal-root-landing-bg {
            background: transparent;
            min-height: 100vh;
        }
        body.portal-root-landing-bg::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -2;
            background: url("{{ asset('bg22.jpg') }}") center / cover no-repeat;
            transform: scale(1.045);
            filter: blur(5px);
            pointer-events: none;
        }
        body.portal-root-landing-bg::after {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background: rgba(255, 255, 255, 0.6);
            pointer-events: none;
        }

        .portal-featured-viewport {
            position: relative;
            mask-image: linear-gradient(to right, transparent 0, black 1.5rem, black calc(100% - 1.5rem), transparent 100%);
            -webkit-mask-image: linear-gradient(to right, transparent 0, black 1.5rem, black calc(100% - 1.5rem), transparent 100%);
        }
    </style>
</head>
<body
    class="portal-root-landing-bg flex min-h-screen flex-col font-sans text-brand-dark antialiased"
>
    <a href="#featured-units" class="skip-link rounded-lg bg-white text-sm font-semibold text-emerald-900 shadow-lg ring-2 ring-emerald-700/20">Skip to featured units</a>
    @include('partials.portal-public-nav', ['active' => 'landing', 'municipalityName' => $municipalityName])

    <main id="browse" tabindex="-1" class="flex flex-1 flex-col outline-none">
        <section class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-[rgba(27,94,32,0.06)] to-[rgba(46,125,50,0.04)] px-5 pb-24 pt-28 text-center md:px-10 md:pt-32">
        {{-- Partner strip: no panel background — logos sit on the hero with caps + drop shadow for readability --}}
        <div class="portal-hero-partners mb-8 w-full max-w-4xl px-2 py-2 sm:mb-10 sm:px-4">
            <div class="portal-hero-partners__strip" role="list">
                <div class="flex min-h-[6.25rem] items-center justify-center sm:min-h-[7.5rem] lg:min-h-[8.5rem]" role="listitem">
                    <img
                        src="{{ asset('SYSTEMLOGO.png') }}"
                        alt="{{ $municipalityName }} — official seal"
                        loading="eager"
                        fetchpriority="high"
                        decoding="async"
                    >
                </div>
                <span class="portal-hero-partners__divider hidden sm:block" aria-hidden="true"></span>
                <div class="flex min-h-[6.25rem] items-center justify-center sm:min-h-[7.5rem] lg:min-h-[8.5rem]" role="listitem">
                    <img
                        src="{{ asset('Love Impasugong.png') }}"
                        alt="Love Impasugong"
                        loading="eager"
                        decoding="async"
                    >
                </div>
                <span class="portal-hero-partners__divider hidden sm:block" aria-hidden="true"></span>
                <div class="flex min-h-[5.5rem] items-center justify-center sm:min-h-[6.5rem] lg:min-h-[7.25rem]" role="listitem">
                    <img
                        src="{{ asset('Lgu Socmed Template-02.png') }}"
                        alt="LGU {{ $municipalityName }}"
                        class="portal-hero-partners__wide"
                        loading="lazy"
                        decoding="async"
                    >
                </div>
            </div>
        </div>

        <h1 class="mb-5 text-3xl font-extrabold leading-tight text-brand-dark opacity-0 animate-fade-in-up-d1 md:text-5xl lg:text-[3rem]">
            Accredited Accommodation Providers in <span class="text-brand-primary">{{ $municipalityName }}</span>
        </h1>

        <p class="mb-8 max-w-[720px] text-base font-medium leading-relaxed text-brand-dark opacity-0 animate-fade-in-up-d2 drop-shadow-[0_1px_2px_rgba(255,255,255,0.85)] md:text-lg md:mb-10">
            Browse accredited tulogan listings from trusted local hosts and enjoy a seamless booking experience.
        </p>

        {{-- Featured / browse available units: horizontal highlights carousel --}}
        <div id="featured-units" class="mb-0 w-full max-w-[1200px] shrink-0 scroll-mt-28 pb-10 opacity-0 animate-fade-in-up-d2 sm:pb-12">
            <div class="mb-5 flex flex-col gap-2 text-left sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-xl font-extrabold tracking-tight text-brand-dark md:text-2xl">
                        Explore Available Accommodations
                    </h2>
                    @if($carouselAccommodations->isNotEmpty())
                        <p class="mt-1 text-sm font-semibold text-brand-dark drop-shadow-[0_1px_2px_rgba(255,255,255,0.75)] md:text-base">
                            Highlights from the directory — swipe or use arrows to explore.
                        </p>
                    @endif
                </div>
                @if($carouselAccommodations->isNotEmpty())
                    <a href="{{ route('portal.accommodations.index') }}" class="mt-2 inline-flex items-center gap-2 self-start text-sm font-bold text-brand-primary underline decoration-2 underline-offset-2 hover:text-brand-dark sm:mt-0 sm:self-auto">
                        See all listings <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                    </a>
                @endif
            </div>

            @if($carouselAccommodations->isEmpty())
                <article
                    class="overflow-hidden rounded-2xl border border-brand-soft/90 bg-white/90 shadow-[0_8px_30px_-12px_rgba(27,94,32,0.15)] backdrop-blur-md"
                    aria-labelledby="featured-empty-heading"
                >
                    <div class="flex flex-col items-center px-6 py-12 text-center sm:px-10 sm:py-14">
                        <span class="mb-5 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-soft to-white text-2xl text-brand-primary shadow-inner ring-1 ring-brand-soft/60" aria-hidden="true">
                            <i class="fas fa-building-user"></i>
                        </span>
                        <h3 id="featured-empty-heading" class="max-w-md text-lg font-bold leading-snug text-brand-dark sm:text-xl">
                            Available units will show here soon
                        </h3>
                        <p class="mt-3 max-w-md text-sm leading-relaxed text-brand-medium sm:text-base">
                            Listings show up after municipality-approved hosts publish verified stays. Check back soon, or use the listings button below to browse what is available today.
                        </p>
                    </div>
                </article>
            @else
                <div
                    class="portal-featured-viewport rounded-2xl border border-emerald-200/60 bg-white/50 p-2 shadow-[0_12px_40px_-16px_rgba(27,94,32,0.18)] backdrop-blur-sm sm:p-3"
                    role="region"
                    aria-roledescription="carousel"
                    aria-label="Available accommodation highlights"
                >
                    <div
                        id="portalListingTrack"
                        class="portal-listing-track flex items-stretch gap-4 overflow-x-auto scroll-smooth pb-2 pt-1 [scrollbar-width:thin] snap-x snap-mandatory md:gap-5"
                        style="-webkit-overflow-scrolling: touch;"
                        tabindex="0"
                    >
                        @foreach($carouselAccommodations as $acc)
                            <article class="portal-listing-card flex w-[min(100%,300px)] shrink-0 snap-start flex-col overflow-hidden rounded-xl border border-brand-soft/90 bg-white shadow-md sm:w-[min(100%,320px)]">
                                <a href="{{ route('portal.accommodations.show', $acc) }}" class="relative block aspect-[4/3] overflow-hidden bg-brand-soft/30">
                                    <img src="{{ $acc->primary_image_url }}" alt="Photo of {{ $acc->name }}" class="h-full w-full object-cover transition duration-300 hover:scale-105" loading="lazy" decoding="async">
                                    @if($acc->is_featured)
                                        <span class="absolute left-2 top-2 rounded-md bg-amber-500 px-2 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-white shadow">Featured</span>
                                    @endif
                                </a>
                                <div class="flex flex-1 flex-col gap-1.5 p-4 text-left">
                                    <h3 class="line-clamp-2 min-h-[2.5rem] text-base font-bold text-brand-dark">{{ $acc->name }}</h3>
                                    <p class="line-clamp-2 text-xs text-brand-medium">{{ $acc->barangay ?? $acc->address }}</p>
                                    @if($acc->max_guests)
                                        <p class="text-[0.7rem] font-medium text-brand-medium"><i class="fas fa-user-group mr-1 text-brand-primary" aria-hidden="true"></i>Up to {{ (int) $acc->max_guests }} guests</p>
                                    @endif
                                    <p class="mt-auto pt-2 text-base font-bold text-brand-primary">{{ $acc->formatted_price }} <span class="text-sm font-normal text-brand-medium">/ night</span></p>
                                    <a href="{{ route('portal.accommodations.show', $acc) }}" class="mt-1 inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-brand-dark to-brand-primary px-3 py-2.5 text-xs font-bold text-white transition hover:brightness-105">
                                        View details
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
                <div class="mt-4 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                    <div class="flex items-center gap-3">
                        <button type="button" id="portalListingPrev" class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-soft text-lg text-brand-dark shadow-sm transition hover:scale-105 hover:bg-brand-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary" aria-controls="portalListingTrack" aria-label="Show previous units">
                            <i class="fas fa-chevron-left" aria-hidden="true"></i>
                        </button>
                        <div id="portalListingDots" class="flex max-w-[200px] flex-wrap justify-center gap-1.5 sm:max-w-none" role="tablist" aria-label="Carousel position"></div>
                        <button type="button" id="portalListingNext" class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-soft text-lg text-brand-dark shadow-sm transition hover:scale-105 hover:bg-brand-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary" aria-controls="portalListingTrack" aria-label="Show next units">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
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
                            var gap = 16;
                            if (window.matchMedia('(min-width:768px)').matches) gap = 20;
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
                                b.classList.toggle('bg-brand-primary', idx === i);
                                b.classList.toggle('w-6', idx === i);
                                b.classList.toggle('bg-brand-soft', idx !== i);
                                b.classList.toggle('w-2', idx !== i);
                            });
                        }
                        if (dotsHost && cards.length) {
                            dotsHost.innerHTML = '';
                            for (var c = 0; c < cards.length; c++) {
                                (function (index) {
                                    var b = document.createElement('button');
                                    b.type = 'button';
                                    b.className = 'h-2 w-2 rounded-full bg-brand-soft transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary';
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
                            if (e.key === 'ArrowLeft') {
                                e.preventDefault();
                                scrollByDir(-1);
                            } else if (e.key === 'ArrowRight') {
                                e.preventDefault();
                                scrollByDir(1);
                            }
                        });
                    })();
                </script>
            @endif
        </div>

        <div class="flex w-full max-w-[1200px] shrink-0 flex-col items-center gap-6 border-t border-brand-soft/30 pt-10 opacity-0 animate-fade-in-up-d3 sm:flex-row sm:justify-center sm:gap-12 md:gap-14 sm:pt-12 mb-10">
            <a href="{{ route('portal.accommodations.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-brand-dark to-brand-primary px-8 py-3.5 text-base font-semibold text-white shadow-[0_4px_15px_rgba(46,125,50,0.3)] transition-all hover:-translate-y-0.5 hover:shadow-[0_6px_20px_rgba(46,125,50,0.38)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-primary">
                <i class="fas fa-compass"></i> Explore all listings
            </a>
            @include('partials.register-choice-menu', [
                'buttonClass' => 'inline-flex items-center gap-2 rounded-xl border-2 border-brand-primary bg-white/80 px-8 py-3.5 text-base font-semibold text-brand-dark backdrop-blur-sm transition hover:bg-brand-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-dark',
                'menuClass' => 'absolute bottom-full left-1/2 z-30 mb-3 w-72 -translate-x-1/2',
            ])
        </div>
        </section>
    </main>

    @include('partials.portal-public-footer')

</body>
</html>
