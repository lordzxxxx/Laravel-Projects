<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.central-public-head', [
        'pageTitle' => 'IMPASUGONG TOURISM | '.$municipalityName.' · Administration',
        'faviconStem' => 'lgu',
    ])
    <style>
        .skip-link:focus { position: absolute; left: 1rem; top: 1rem; z-index: 2000; clip: auto; width: auto; height: auto; overflow: visible; padding: 0.75rem 1rem; margin: 0; }
        .skip-link { position: absolute; left: 1rem; top: -999px; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; border: 0; }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
        }
        /* Layered surface: communal photo at 50% opacity + 50% white scrim, no blur */
        .admin-landing-photo {
            background-image: url('/COMMUNAL.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: scroll;
            opacity: 0.5;
        }
        .admin-landing-design {
            background-image:
                radial-gradient(circle at 1px 1px, rgba(27, 94, 32, 0.045) 1px, transparent 0),
                radial-gradient(ellipse 110% 75% at 50% -5%, rgba(46, 125, 50, 0.09), transparent 52%),
                radial-gradient(ellipse 70% 55% at 100% 85%, rgba(200, 230, 201, 0.45), transparent 58%),
                radial-gradient(ellipse 55% 45% at 0% 60%, rgba(67, 160, 71, 0.06), transparent 52%),
                linear-gradient(168deg, rgba(255, 255, 255, 0.55) 0%, rgba(255, 255, 255, 0) 38%, rgba(248, 250, 252, 0.35) 100%);
            background-size: 28px 28px, auto, auto, auto, auto;
        }
        .admin-landing-white-scrim {
            background-color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="flex min-h-screen min-h-[100dvh] flex-col bg-slate-50 font-sans text-base text-brand-dark antialiased">
    <a href="#staff-main" class="skip-link rounded-lg bg-white text-base font-semibold text-brand-dark shadow-lg ring-2 ring-brand-primary/30">Skip to main content</a>
    @include('partials.portal-admin-nav', ['municipalityName' => $municipalityName])

    <main id="staff-main" tabindex="-1" class="relative isolate z-0 flex flex-1 flex-col overflow-hidden outline-none">
        <div class="admin-landing-photo pointer-events-none absolute inset-0 -z-20 saturate-[0.92]" aria-hidden="true"></div>
        <div class="admin-landing-white-scrim pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="admin-landing-design pointer-events-none absolute inset-0 -z-[5]" aria-hidden="true"></div>
        <section class="relative z-[1] mx-auto flex w-full max-w-6xl flex-1 flex-col justify-center px-4 pb-12 pt-20 sm:px-6 sm:pb-14 sm:pt-24 lg:px-8">
            {{-- Brand row --}}
            <div class="mb-7 flex shrink-0 -translate-y-2 flex-wrap items-center justify-center gap-7 sm:gap-10 md:mb-10 md:gap-12 lg:gap-16">
                <img src="{{ asset('Love Impasugong.png') }}" alt="" class="h-28 w-auto max-h-[184px] max-w-[min(52vw,270px)] object-contain sm:h-36 md:h-44 md:max-w-[310px]" loading="lazy" width="270" height="158" role="presentation">
                <img src="{{ asset('SYSTEMLOGO.png') }}" alt="" class="h-28 w-auto max-h-[184px] max-w-[min(52vw,270px)] object-contain sm:h-36 md:h-44 md:max-w-[310px]" loading="lazy" width="270" height="270" role="presentation">
                <img src="{{ asset('Lgu Socmed Template-02 2.png') }}" alt="" class="h-24 w-auto max-h-[168px] max-w-[min(94vw,460px)] object-contain sm:h-36 md:h-40 md:max-w-[min(520px,54vw)] lg:h-44" loading="lazy" width="460" height="142" role="presentation">
            </div>

            <header class="mx-auto w-full max-w-3xl shrink-0 text-center">
                <p class="text-sm font-extrabold uppercase tracking-[0.24em] text-brand-dark [text-shadow:_0_1px_0_rgb(255_255_255_/_0.92),_0_0_28px_rgb(255_255_255_/_0.65)] sm:text-[0.85rem]">Internal Operations</p>
                <h1 class="mt-4 text-[1.75rem] font-extrabold leading-[1.2] tracking-tight text-brand-dark [text-shadow:_0_1px_0_rgb(255_255_255_/_0.92),_0_2px_24px_rgb(255_255_255_/_0.55)] sm:mt-5 sm:text-[2rem] md:text-[2.35rem]">
                    Administration Console · <span class="text-brand-primary">{{ $municipalityName }}, Bukidnon</span>
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-base font-medium leading-[1.65] text-slate-800 [text-shadow:_0_1px_0_rgb(255_255_255_/_0.9),_0_0_20px_rgb(255_255_255_/_0.55)] sm:mt-6 sm:text-[1.075rem]">
                    A centralized municipal workspace for accommodation verification, compliance management, onboarding oversight, reporting, and internal administrative support — separate from the public traveler and accommodation provider platform.
                </p>
            </header>
        </section>
    </main>

    @include('partials.portal-admin-footer')
</body>
</html>
