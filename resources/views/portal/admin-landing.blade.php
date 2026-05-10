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
            .admin-landing-white-scrim {
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
            }
        }
        /* Layered surface: communal photo + ~50% white + accent design */
        .admin-landing-photo {
            background-image: url('/COMMUNAL.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: scroll;
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
            /* ~15% of strong blur (64px reference) */
            backdrop-filter: blur(9.6px);
            -webkit-backdrop-filter: blur(9.6px);
        }
    </style>
</head>
<body class="flex min-h-screen min-h-[100dvh] flex-col bg-slate-50 font-sans text-base text-brand-dark antialiased">
    <a href="#staff-main" class="skip-link rounded-lg bg-white text-base font-semibold text-brand-dark shadow-lg ring-2 ring-brand-primary/30">Skip to main content</a>
    @include('partials.portal-admin-nav', ['municipalityName' => $municipalityName])

    <main id="staff-main" tabindex="-1" class="relative isolate z-0 flex flex-1 flex-col overflow-hidden outline-none">
        {{-- Base: communal photography --}}
        <div class="admin-landing-photo pointer-events-none absolute inset-0 -z-20 saturate-[0.92]" aria-hidden="true"></div>
        {{-- 50% white + ~15% blur frosted scrim over communal photo --}}
        <div class="admin-landing-white-scrim pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></div>
        {{-- Accent: soft brand glow, corner washes, micro-grid texture --}}
        <div class="admin-landing-design pointer-events-none absolute inset-0 -z-[5]" aria-hidden="true"></div>
        <section class="relative z-[1] mx-auto flex w-full max-w-6xl flex-1 flex-col px-4 pb-12 pt-24 sm:px-6 sm:pb-14 sm:pt-28 lg:px-8">
            {{-- Brand row --}}
            <div class="mb-8 flex shrink-0 flex-wrap items-center justify-center gap-7 sm:gap-10 md:mb-11 md:gap-12 lg:gap-16">
                <img src="{{ asset('Love Impasugong.png') }}" alt="" class="h-24 w-auto max-h-[164px] max-w-[min(48vw,240px)] object-contain sm:h-32 md:h-40 md:max-w-[280px]" loading="lazy" width="240" height="140" role="presentation">
                <img src="{{ asset('SYSTEMLOGO.png') }}" alt="" class="h-24 w-auto max-h-[164px] max-w-[min(48vw,240px)] object-contain sm:h-32 md:h-40 md:max-w-[280px]" loading="lazy" width="240" height="240" role="presentation">
                <img src="{{ asset('Lgu Socmed Template-02 2.png') }}" alt="" class="h-[5.25rem] w-auto max-h-[148px] max-w-[min(94vw,420px)] object-contain sm:h-32 md:h-36 md:max-w-[min(480px,50vw)] lg:h-40" loading="lazy" width="420" height="130" role="presentation">
            </div>

            <header class="mx-auto w-full max-w-3xl shrink-0 text-center">
                <p class="text-sm font-extrabold uppercase tracking-[0.24em] text-brand-dark [text-shadow:_0_1px_0_rgb(255_255_255_/_0.92),_0_0_28px_rgb(255_255_255_/_0.65)] sm:text-[0.85rem]">Internal operations</p>
                <h1 class="mt-4 text-[1.75rem] font-extrabold leading-[1.2] tracking-tight text-brand-dark [text-shadow:_0_1px_0_rgb(255_255_255_/_0.92),_0_2px_24px_rgb(255_255_255_/_0.55)] sm:mt-5 sm:text-[2rem] md:text-[2.35rem]">
                    Administration console · <span class="text-brand-primary">{{ $municipalityName }}</span>
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-base font-medium leading-[1.65] text-slate-800 [text-shadow:_0_1px_0_rgb(255_255_255_/_0.9),_0_0_20px_rgb(255_255_255_/_0.55)] sm:mt-6 sm:text-[1.075rem]">
                    Municipality workspace for lodging verification, compliance decisions, onboarding oversight, reporting, and internal support—not the public traveller or operator site.
                </p>
            </header>

            <div class="mt-10 flex flex-1 flex-col sm:mt-12">
                <h2 id="staff-overview-heading" class="mb-6 text-center text-lg font-extrabold tracking-tight text-brand-dark [text-shadow:_0_1px_0_rgb(255_255_255_/_0.92),_0_0_24px_rgb(255_255_255_/_0.55)] sm:mb-7 sm:text-xl md:text-2xl">
                    Operational overview
                </h2>
                <div class="grid flex-1 auto-rows-fr gap-5 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6" role="list" aria-labelledby="staff-overview-heading">
                    <article role="listitem" class="flex min-h-[11rem] flex-col rounded-2xl border border-slate-200/95 bg-white/95 p-5 text-center shadow-[0_4px_24px_rgba(15,23,42,0.07)] ring-1 ring-slate-100/90 backdrop-blur-[2px] dark:border-slate-700 dark:bg-slate-900/95 md:min-h-0 md:p-6">
                        <div class="mx-auto flex min-h-[5.5rem] w-full max-w-[12rem] items-center justify-center rounded-xl bg-gradient-to-b from-brand-soft/80 to-white">
                            <span class="text-4xl font-extrabold tabular-nums text-brand-primary md:text-5xl" aria-hidden="true">{{ number_format(max(0, $pendingReviews)) }}</span>
                        </div>
                        <h3 class="mt-5 text-base font-bold text-brand-dark dark:text-white">Review queues</h3>
                        <p class="mt-2 flex-1 text-base leading-snug text-brand-medium dark:text-slate-400">Applicants and operators awaiting municipal review prior to activation.</p>
                    </article>

                    <article role="listitem" class="flex min-h-[11rem] flex-col rounded-2xl border border-slate-200/95 bg-white/95 p-5 text-center shadow-[0_4px_24px_rgba(15,23,42,0.07)] ring-1 ring-slate-100/90 backdrop-blur-[2px] dark:border-slate-700 dark:bg-slate-900/95 md:min-h-0 md:p-6">
                        <div class="mx-auto flex min-h-[5.5rem] w-full max-w-[12rem] items-center justify-center rounded-xl bg-brand-soft/35">
                            <i class="fas fa-lock text-5xl text-brand-primary" aria-hidden="true"></i>
                        </div>
                        <h3 class="mt-5 text-base font-bold text-brand-dark dark:text-white">Secure workspace</h3>
                        <p class="mt-2 flex-1 text-base leading-snug text-brand-medium dark:text-slate-400">Privileged dashboards and tooling are confined to authenticated staff sessions on this pathway.</p>
                    </article>

                    <article role="listitem" class="flex min-h-[11rem] flex-col rounded-2xl border border-slate-200/95 bg-white/95 p-5 text-center shadow-[0_4px_24px_rgba(15,23,42,0.07)] ring-1 ring-slate-100/90 backdrop-blur-[2px] dark:border-slate-700 dark:bg-slate-900/95 md:min-h-0 md:p-6">
                        <div class="mx-auto flex min-h-[5.5rem] w-full max-w-[12rem] items-center justify-center rounded-xl bg-brand-soft/35">
                            <i class="fas fa-file-signature text-5xl text-brand-primary" aria-hidden="true"></i>
                        </div>
                        <h3 class="mt-5 text-base font-bold text-brand-dark dark:text-white">Document review</h3>
                        <p class="mt-2 flex-1 text-base leading-snug text-brand-medium dark:text-slate-400">Permits, clearances, and identification submitted through onboarding with traceable decisions.</p>
                    </article>

                    <article role="listitem" class="flex min-h-[11rem] flex-col rounded-2xl border border-slate-200/95 bg-white/95 p-5 text-center shadow-[0_4px_24px_rgba(15,23,42,0.07)] ring-1 ring-slate-100/90 backdrop-blur-[2px] dark:border-slate-700 dark:bg-slate-900/95 md:min-h-0 md:p-6">
                        <div class="mx-auto flex min-h-[5.5rem] w-full max-w-[12rem] items-center justify-center rounded-xl bg-brand-soft/35">
                            <i class="fas fa-building-columns text-5xl text-brand-primary" aria-hidden="true"></i>
                        </div>
                        <h3 class="mt-5 text-base font-bold text-brand-dark dark:text-white">Directory oversight</h3>
                        <p class="mt-2 flex-1 text-base leading-snug text-brand-medium dark:text-slate-400">Monitor listings, lifecycle events, exports, and support threads with audit-friendly updates.</p>
                    </article>
                </div>
            </div>
        </section>
    </main>

    @include('partials.portal-admin-footer')
</body>
</html>
