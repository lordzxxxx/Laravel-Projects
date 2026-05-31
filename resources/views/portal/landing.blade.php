<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'IMPASUGONG TOURISM'])
    <style>
        .skip-link:focus { position: absolute; left: 1rem; top: 1rem; z-index: 2000; clip: auto; width: auto; height: auto; overflow: visible; padding: 0.75rem 1rem; margin: 0; }
        .skip-link { position: absolute; left: 1rem; top: -999px; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; border: 0; }
        .portal-hero-partners { --partner-logo-h: 5.25rem; --partner-logo-w: 11rem; }
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
            transform: scale(1.02);
            filter: blur(2px);
            pointer-events: none;
        }
        body.portal-root-landing-bg::after {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background: rgba(255, 255, 255, 0.72);
            pointer-events: none;
        }

        #featured-units {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(200, 230, 201, 0.85);
            border-radius: 1.25rem;
            padding: 1.25rem 1rem 1rem;
            box-shadow: 0 12px 40px -18px rgba(27, 94, 32, 0.2);
        }
        @media (min-width: 640px) {
            #featured-units {
                padding: 1.5rem 1.25rem 1.25rem;
            }
        }
        .portal-featured-header {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        @media (min-width: 768px) {
            .portal-featured-header {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 1.5rem;
            }
        }
        .portal-featured-header__text {
            min-width: 0;
            flex: 1 1 auto;
        }
        .portal-featured-header__title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--color-brand-dark, #1b5e20);
            line-height: 1.25;
        }
        @media (min-width: 768px) {
            .portal-featured-header__title {
                font-size: 1.5rem;
            }
        }
        .portal-featured-header__subtitle {
            margin: 0.35rem 0 0;
            max-width: 42rem;
            font-size: 0.8125rem;
            font-weight: 500;
            line-height: 1.5;
            color: #3d6b45;
        }
        @media (min-width: 768px) {
            .portal-featured-header__subtitle {
                font-size: 0.875rem;
            }
        }
        .portal-featured-header__cta {
            display: inline-flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            align-self: flex-start;
            border-radius: 0.75rem;
            border: 1px solid rgba(46, 125, 50, 0.35);
            background: #fff;
            padding: 0.55rem 1rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: #1b5e20;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(27, 94, 32, 0.08);
            transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }
        .portal-featured-header__cta:hover {
            background: #2e7d32;
            color: #fff;
            box-shadow: 0 4px 14px rgba(46, 125, 50, 0.22);
            transform: translateY(-1px);
        }
        @media (min-width: 768px) {
            .portal-featured-header__cta {
                align-self: center;
            }
        }

        .portal-featured-viewport {
            position: relative;
            overflow: hidden;
            width: 100%;
            container-type: inline-size;
            container-name: portal-carousel;
            /* Reserve height to avoid layout shift while images load */
            min-height: 22rem;
        }
        @media (min-width: 640px) {
            .portal-featured-viewport {
                min-height: 24rem;
            }
        }

        .portal-listing-track {
            display: flex;
            align-items: stretch;
            gap: 1rem;
            width: max-content;
            will-change: transform;
            transition: transform 0.55s cubic-bezier(0.22, 1, 0.36, 1);
        }
        @media (prefers-reduced-motion: reduce) {
            .portal-listing-track {
                transition: none;
            }
        }

        /* Exactly 3 / 2 / 1 cards visible (container = viewport) */
        .portal-listing-card {
            box-sizing: border-box;
            flex: 0 0 100cqw;
            width: 100cqw;
            max-width: 100cqw;
            min-width: 0;
        }
        @container portal-carousel (min-width: 640px) {
            .portal-listing-card {
                flex-basis: calc((100cqw - 1rem) / 2);
                width: calc((100cqw - 1rem) / 2);
                max-width: calc((100cqw - 1rem) / 2);
            }
        }
        @container portal-carousel (min-width: 1024px) {
            .portal-listing-card {
                flex-basis: calc((100cqw - 2rem) / 3);
                width: calc((100cqw - 2rem) / 3);
                max-width: calc((100cqw - 2rem) / 3);
            }
        }

        .portal-listing-card .portal-listing-card__media {
            position: relative;
            aspect-ratio: 4 / 3;
            width: 100%;
            flex-shrink: 0;
            background: rgba(200, 230, 201, 0.35);
        }

        .portal-listing-card .portal-listing-card__body {
            display: flex;
            flex: 1;
            flex-direction: column;
            gap: 0.375rem;
            padding: 1rem;
            min-height: 9.5rem;
        }
        .portal-landing-title,
        .portal-landing-copy {
            max-width: min(100%, calc(100vw - 2rem));
            overflow-wrap: break-word;
        }
        @media (max-width: 480px) {
            .portal-landing-title {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body
    class="portal-root-landing-bg flex min-h-screen flex-col font-sans text-brand-dark antialiased"
>
    <a href="#featured-units" class="skip-link rounded-lg bg-white text-sm font-semibold text-emerald-900 shadow-lg ring-2 ring-emerald-700/20">Skip to featured units</a>
    @include('partials.portal-public-nav', ['active' => 'landing', 'municipalityName' => $municipalityName])

    <main id="browse" tabindex="-1" class="flex flex-1 flex-col outline-none">
        <section class="flex min-h-screen min-h-[100svh] w-full flex-col items-center justify-center overflow-x-hidden bg-gradient-to-br from-[rgba(27,94,32,0.06)] to-[rgba(46,125,50,0.04)] px-4 pb-20 pt-40 text-center sm:px-5 sm:pt-36 md:px-10 md:pb-24 md:pt-32">
        {{-- Partner strip: no panel background — logos sit on the hero with caps + drop shadow for readability --}}
        <div class="portal-hero-partners mb-6 w-full max-w-4xl px-2 py-2 sm:mb-10 sm:px-4">
            <div class="portal-hero-partners__strip" role="list">
                <div class="flex min-h-[5.25rem] items-center justify-center sm:min-h-[7.5rem] lg:min-h-[8.5rem]" role="listitem">
                    <img
                        src="{{ asset('SYSTEMLOGO.png') }}"
                        alt="{{ $municipalityName }} — official seal"
                        loading="eager"
                        fetchpriority="high"
                        decoding="async"
                    >
                </div>
                <span class="portal-hero-partners__divider hidden sm:block" aria-hidden="true"></span>
                <div class="flex min-h-[5.25rem] items-center justify-center sm:min-h-[7.5rem] lg:min-h-[8.5rem]" role="listitem">
                    <img
                        src="{{ asset('Love Impasugong.png') }}"
                        alt="Love Impasugong"
                        loading="eager"
                        decoding="async"
                    >
                </div>
                <span class="portal-hero-partners__divider hidden sm:block" aria-hidden="true"></span>
                <div class="flex min-h-[4.75rem] items-center justify-center sm:min-h-[6.5rem] lg:min-h-[7.25rem]" role="listitem">
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

        <h1 class="portal-landing-title mb-5 w-full max-w-5xl break-words px-1 text-2xl font-extrabold leading-tight text-brand-dark opacity-0 animate-fade-in-up-d1 sm:text-4xl md:text-5xl lg:text-[3rem]">
            <span class="block sm:inline">Accredited Accommodation</span>
            <span class="block sm:inline"> Providers in <span class="text-brand-primary">{{ $municipalityName }}</span></span>
        </h1>

        <p class="portal-landing-copy mb-8 w-full max-w-[720px] px-1 text-base font-medium leading-relaxed text-brand-dark opacity-0 animate-fade-in-up-d2 drop-shadow-[0_1px_2px_rgba(255,255,255,0.85)] md:mb-10 md:text-lg">
            Browse accredited tulogan listings from trusted local hosts and enjoy a seamless booking experience.
        </p>

        {{-- Featured / browse available units: horizontal highlights carousel --}}
        <div id="featured-units" class="mb-0 w-full max-w-[1200px] shrink-0 scroll-mt-28 pb-6 opacity-0 animate-fade-in-up-d2 sm:pb-8">
            <header class="portal-featured-header text-left">
                <div class="portal-featured-header__text">
                    <h2 class="portal-featured-header__title">Explore Available Accommodations</h2>
                    @if($carouselAccommodations->isNotEmpty())
                        <p class="portal-featured-header__subtitle">
                            Three featured stays at a time. Photos rotate every 5 seconds — use the arrows below to see more units.
                        </p>
                    @endif
                </div>
                @if($carouselAccommodations->isNotEmpty())
                    <a href="{{ route('portal.accommodations.index') }}" class="portal-featured-header__cta">
                        See all listings
                        <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                    </a>
                @endif
            </header>

            @if($carouselAccommodations->isEmpty())
                <article
                    class="overflow-hidden rounded-2xl border border-brand-soft/90 bg-white/90 shadow-[0_8px_30px_-12px_rgba(27,94,32,0.15)] backdrop-blur-md"
                    aria-labelledby="featured-empty-heading"
                >
                    <div class="flex flex-col items-center px-6 py-12 text-center sm:px-10 sm:py-14">
                        <span class="mb-5 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-soft to-white text-2xl text-brand-primary shadow-inner ring-1 ring-brand-soft/60" aria-hidden="true">
                            <i class="fas fa-building-user"></i>
                        </span>
                        <h3 id="featured-empty-heading" class="max-w-xs text-base font-bold leading-snug text-brand-dark sm:max-w-md sm:text-xl">
                            Available units will show here soon
                        </h3>
                        <p class="mt-3 max-w-md text-sm leading-relaxed text-brand-medium sm:text-base">
                            Listings show up after municipality-approved hosts publish verified stays. Check back soon, or use the listings button below to browse what is available today.
                        </p>
                    </div>
                </article>
            @else
                <div
                    id="portalFeaturedCarousel"
                    class="portal-featured-viewport rounded-2xl border border-emerald-200/60 bg-white/50 p-2 shadow-[0_12px_40px_-16px_rgba(27,94,32,0.18)] backdrop-blur-sm sm:p-3"
                    role="region"
                    aria-roledescription="carousel"
                    aria-label="Available accommodation highlights"
                >
                    <div
                        id="portalListingTrack"
                        class="portal-listing-track"
                        tabindex="0"
                        aria-live="polite"
                    >
                        @foreach($carouselAccommodations as $acc)
                            <article class="portal-listing-card flex flex-col overflow-hidden rounded-xl border border-brand-soft/90 bg-white shadow-md">
                                <div class="portal-listing-card__media">
                                    <x-portal-listing-image-carousel
                                        :accommodation="$acc"
                                        :property-url="route('portal.accommodations.show', $acc)"
                                    />
                                    @if($acc->is_featured)
                                        <span class="pointer-events-none absolute left-2 top-2 z-20 rounded-md bg-amber-500 px-2 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-white shadow">Featured</span>
                                    @endif
                                </div>
                                <div class="portal-listing-card__body text-left">
                                    <h3 class="line-clamp-2 min-h-[2.5rem] text-base font-bold text-brand-dark">{{ $acc->name }}</h3>
                                    <p class="line-clamp-2 text-xs text-brand-medium">{{ $acc->barangay ?? $acc->address }}</p>
                                    @if($acc->max_guests)
                                        <p class="text-[0.7rem] font-medium text-brand-medium"><i class="fas fa-user-group mr-1 text-brand-primary" aria-hidden="true"></i>Up to {{ (int) $acc->max_guests }} guests</p>
                                    @endif
                                    <p class="mt-auto pt-2 text-base font-bold text-brand-primary">{{ $acc->formatted_price }} <span class="text-sm font-normal text-brand-medium">/ night</span></p>
                                    <a href="{{ route('portal.accommodations.show', $acc) }}" class="ui-btn ui-btn-primary ui-btn-sm ui-btn-block mt-1">
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
                        var viewport = document.getElementById('portalFeaturedCarousel');
                        var track = document.getElementById('portalListingTrack');
                        var prev = document.getElementById('portalListingPrev');
                        var next = document.getElementById('portalListingNext');
                        var dotsHost = document.getElementById('portalListingDots');
                        if (!viewport || !track || !prev || !next) return;

                        var cards = Array.prototype.slice.call(track.querySelectorAll('.portal-listing-card'));
                        if (!cards.length) return;

                        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                        var currentPage = 0;
                        var pageCount = 1;
                        var gapPx = 16;

                        function visiblePerPage() {
                            if (window.matchMedia('(min-width: 1024px)').matches) return 3;
                            if (window.matchMedia('(min-width: 640px)').matches) return 2;
                            return 1;
                        }

                        function readGap() {
                            var styles = window.getComputedStyle(track);
                            var g = parseFloat(styles.columnGap || styles.gap || '16');
                            gapPx = Number.isFinite(g) ? g : 16;
                        }

                        function cardWidth() {
                            var perPage = visiblePerPage();
                            var inner = viewport.clientWidth;
                            return (inner - gapPx * (perPage - 1)) / perPage;
                        }

                        function applyCardWidths() {
                            readGap();
                            var w = cardWidth();
                            cards.forEach(function (card) {
                                card.style.flexBasis = w + 'px';
                                card.style.width = w + 'px';
                                card.style.maxWidth = w + 'px';
                            });
                        }

                        function recalcPages() {
                            applyCardWidths();
                            var perPage = visiblePerPage();
                            pageCount = Math.max(1, Math.ceil(cards.length / perPage));
                            if (currentPage >= pageCount) currentPage = 0;
                            renderDots();
                            goToPage(currentPage, true);
                        }

                        function offsetForPage(page) {
                            var perPage = visiblePerPage();
                            var step = cardWidth() + gapPx;
                            return page * perPage * step;
                        }

                        function goToPage(page, instant) {
                            currentPage = ((page % pageCount) + pageCount) % pageCount;
                            var x = offsetForPage(currentPage);
                            if (instant || reducedMotion) {
                                track.style.transition = 'none';
                                track.style.transform = 'translate3d(' + (-x) + 'px, 0, 0)';
                                void track.offsetHeight;
                                track.style.transition = '';
                            } else {
                                track.style.transform = 'translate3d(' + (-x) + 'px, 0, 0)';
                            }
                            updateDots();
                            viewport.setAttribute('data-carousel-page', String(currentPage + 1));
                        }

                        function updateDots() {
                            if (!dotsHost) return;
                            var buttons = dotsHost.querySelectorAll('button');
                            buttons.forEach(function (b, idx) {
                                var active = idx === currentPage;
                                b.setAttribute('aria-selected', active ? 'true' : 'false');
                                b.classList.toggle('bg-brand-primary', active);
                                b.classList.toggle('w-6', active);
                                b.classList.toggle('bg-brand-soft', !active);
                                b.classList.toggle('w-2', !active);
                            });
                        }

                        function renderDots() {
                            if (!dotsHost) return;
                            dotsHost.innerHTML = '';
                            for (var p = 0; p < pageCount; p++) {
                                (function (pageIndex) {
                                    var b = document.createElement('button');
                                    b.type = 'button';
                                    b.className = 'h-2 w-2 rounded-full bg-brand-soft transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary';
                                    b.setAttribute('role', 'tab');
                                    b.setAttribute('aria-label', 'Show highlight set ' + (pageIndex + 1) + ' of ' + pageCount);
                                    b.addEventListener('click', function () {
                                        goToPage(pageIndex, false);
                                    });
                                    dotsHost.appendChild(b);
                                })(p);
                            }
                            updateDots();
                        }

                        function nextPage() {
                            goToPage(currentPage + 1, false);
                        }

                        function prevPage() {
                            goToPage(currentPage - 1, false);
                        }

                        prev.addEventListener('click', function () {
                            prevPage();
                        });
                        next.addEventListener('click', function () {
                            nextPage();
                        });

                        track.addEventListener('keydown', function (e) {
                            if (e.key === 'ArrowLeft') {
                                e.preventDefault();
                                prevPage();
                            } else if (e.key === 'ArrowRight') {
                                e.preventDefault();
                                nextPage();
                            }
                        });

                        var resizeTimer;
                        window.addEventListener('resize', function () {
                            window.clearTimeout(resizeTimer);
                            resizeTimer = window.setTimeout(recalcPages, 120);
                        });

                        recalcPages();

                        if (window.PortalListingImageCarousel && typeof window.PortalListingImageCarousel.init === 'function') {
                            window.PortalListingImageCarousel.init(viewport);
                        }
                    })();
                </script>
            @endif
        </div>

        <div class="mb-10 flex w-full max-w-[1200px] shrink-0 flex-col items-center gap-4 border-t border-brand-soft/30 pt-8 opacity-0 animate-fade-in-up-d3 sm:flex-row sm:justify-center sm:gap-12 sm:pt-12 md:gap-14">
            <a href="{{ route('portal.accommodations.index') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-brand-dark to-brand-primary px-8 py-3.5 text-base font-semibold text-white shadow-[0_4px_15px_rgba(46,125,50,0.3)] transition-all hover:-translate-y-0.5 hover:shadow-[0_6px_20px_rgba(46,125,50,0.38)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-primary sm:w-auto">
                <i class="fas fa-compass"></i> Explore all listings
            </a>
            @include('partials.register-choice-menu', [
                'buttonClass' => 'inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-brand-primary bg-white/80 px-8 py-3.5 text-base font-semibold text-brand-dark backdrop-blur-sm transition hover:bg-brand-primary hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-dark sm:w-auto',
                'menuClass' => 'absolute bottom-full left-1/2 z-30 mb-3 w-72 -translate-x-1/2',
            ])
        </div>
        </section>
    </main>

    @include('partials.portal-public-footer')

</body>
</html>
