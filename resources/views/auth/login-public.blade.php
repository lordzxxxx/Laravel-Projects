@php($municipality = config('portals.municipality_name', 'Impasug-ong'))
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="color-scheme" content="light">
    <meta name="description" content="Sign in to explore verified stays, manage wishlists, and coordinate bookings for {{ $municipality }}.">
    {{-- Login uses a dedicated light green / white presentation --}}
    <script>
        (function () {
            try {
                document.documentElement.classList.remove('dark');
            } catch (e) {}
        })();
    </script>
    <title>Hospitality sign-in — {{ $municipality }} | IMPASUGONG TOURISM</title>
    @include('partials.tenant-favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .auth-skip:focus { clip: auto; height: auto; width: auto; overflow: visible; outline: none; padding: .75rem 1rem; top: .75rem; left: .75rem; z-index: 100; margin: 0; }
        .auth-skip { position: absolute; left: .75rem; top: -100px; clip: rect(0 0 0 0); height: 1px; width: 1px; overflow: hidden; white-space: nowrap; border: 0; border-radius: .5rem; background: rgb(27 94 32); color: rgb(255 255 255); box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2); }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
            .auth-shell.auth-shell-public-glass {
                -webkit-backdrop-filter: none !important;
                backdrop-filter: none !important;
                background: rgba(255, 255, 255, 0.96) !important;
            }
        }
        @media (prefers-contrast: more) {
            .auth-field { border-width: 2px !important; border-color: CanvasText !important; }
            .auth-shell { outline: 2px solid CanvasText !important; outline-offset: 2px; }
        }
        .auth-partner-img {
            display: block;
            height: auto;
            max-height: 6.25rem;
            width: auto;
            max-width: 14rem;
            object-fit: contain;
            object-position: center;
            filter: drop-shadow(0 2px 8px rgba(27, 94, 32, 0.1)) drop-shadow(0 1px 2px rgba(255, 255, 255, 0.8));
        }
        @media (min-width: 640px) {
            .auth-partner-img { max-height: 7.5rem; max-width: 16rem; }
        }
        @media (min-width: 1024px) {
            .auth-partner-img { max-height: 8.75rem; max-width: 18rem; }
        }
        .auth-partner-img--wide {
            max-height: 5.25rem;
            max-width: min(100%, 20rem);
        }
        @media (min-width: 640px) {
            .auth-partner-img--wide { max-height: 6.25rem; max-width: min(100%, 24rem); }
        }
        @media (min-width: 1024px) {
            .auth-partner-img--wide { max-height: 7rem; max-width: min(100%, 28rem); }
        }
        /* Dark green headline copy on scenic background — halo keeps it readable */
        .auth-public-hero-copy,
        .auth-public-hero-copy h2,
        .auth-public-hero-copy p {
            color: #1b5e20 !important;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.95), 0 0 20px rgba(255, 255, 255, 0.45);
        }
        /* White card: ~10% of ~64px reference blur (“frosted” layer) */
        .auth-shell.auth-shell-public-glass {
            background: rgba(255, 255, 255, 0.87);
            -webkit-backdrop-filter: blur(6.4px);
            backdrop-filter: blur(6.4px);
        }
        /* Page background: see partials/auth-public-carousel-bg (rotating photos + 60% white scrim) */
    </style>
</head>
<body class="auth-public-carousel-page min-h-[100dvh] overflow-x-hidden overflow-y-auto text-base text-brand-dark antialiased">
    @include('partials.auth-public-carousel-bg')
    <a href="#public-signin-main" class="auth-skip text-sm font-semibold">Skip to sign-in form</a>

    {{-- Content biased toward upper area: large partner marks first, dark green headlines on photo --}}
    <div class="relative z-10 flex min-h-[100dvh] w-full flex-col items-center justify-start px-4 pb-12 pt-5 sm:px-8 sm:pb-16 sm:pt-10 lg:pt-14">
        <div class="flex w-full max-w-3xl flex-col items-stretch">
            <header class="auth-public-brand shrink-0 bg-transparent pb-2 pt-0">
                <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-6 sm:gap-x-14 sm:gap-y-7 md:gap-x-16" aria-label="Official partner marks">
                    <img src="{{ asset('SYSTEMLOGO.png') }}" alt="{{ $municipality }} official seal" class="auth-partner-img" width="260" height="174" fetchpriority="high" decoding="async">
                    <img src="{{ asset('Love Impasugong.png') }}" alt="Love Impasugong" class="auth-partner-img" width="260" height="174" loading="eager" decoding="async">
                    <img src="{{ asset('Lgu Socmed Template-02.png') }}" alt="LGU {{ $municipality }}" class="auth-partner-img auth-partner-img--wide" width="400" height="140" loading="lazy" decoding="async">
                </div>
                {{-- Clear separation from logos: hero copy lowered with even spacing between each line/block --}}
                <div class="auth-public-hero-copy mx-auto mt-12 max-w-2xl space-y-5 px-4 text-center sm:mt-16 sm:space-y-6 sm:px-6 lg:mt-[4.25rem] lg:max-w-3xl lg:space-y-7">
                    <p class="text-[0.7rem] font-bold uppercase tracking-[0.22em] sm:text-xs sm:tracking-[0.26em]">
                        Impasugong Tourism · Hospitality gateway
                    </p>
                    <p class="text-[0.68rem] font-bold uppercase tracking-[0.26em] sm:text-[0.72rem]">Trusted stays</p>
                    <h2 id="public-welcome-heading" class="text-xl font-extrabold leading-snug tracking-tight sm:text-2xl lg:text-[1.625rem]">{{ $municipality }} welcomes you</h2>
                    <p class="mx-auto max-w-xl text-sm font-medium leading-[1.65] text-[#1b5e20] sm:text-[0.975rem] sm:leading-relaxed lg:text-base">Municipality-reviewed listings. Plan your visit, message hosts, and manage reservations in one place.</p>
                </div>
            </header>

            <main id="public-signin-main" tabindex="-1" class="mt-10 flex w-full flex-col items-center pb-8 sm:mt-12 lg:mt-14 sm:pb-12">
                <div class="auth-shell auth-shell-public-glass w-full max-w-md rounded-2xl border border-emerald-200/65 p-7 shadow-[0_20px_50px_-12px_rgba(27,94,32,0.14)] ring-1 ring-emerald-900/[0.05] sm:p-9">
                    <header class="mb-7 px-0.5 text-center pt-2 sm:mb-8 sm:text-left">
                        <h1 class="text-2xl font-extrabold tracking-tight text-[#14532d] sm:text-[1.65rem]">Welcome back</h1>
                        <p class="mt-4 text-sm font-medium leading-relaxed text-[#1b5e20] sm:mt-[1.125rem] sm:text-[0.9375rem]">
                            Sign in for verified stays and bookings in <span class="font-semibold text-[#14532d]">{{ $municipality }}</span>.
                        </p>
                    </header>

                    @if (session('status'))
                        <div class="mb-5 flex gap-2.5 rounded-xl border border-emerald-300/70 bg-emerald-50 px-4 py-3 text-sm text-emerald-950" role="status" aria-live="polite">
                            <i class="fas fa-circle-check mt-0.5 shrink-0 text-emerald-600" aria-hidden="true"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <div id="public-signin-errors" class="sr-only" aria-live="polite"></div>

                    <form method="POST" action="{{ url('/login') }}" class="space-y-6" autocomplete="on" novalidate data-auth-form aria-describedby="form-intro-public">
                        @csrf
                        <p id="form-intro-public" class="sr-only">Sign in with email and password for {{ $municipality }}.</p>

                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#14532d]">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                inputmode="email"
                                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                aria-describedby="email-hint-public{{ $errors->has('email') ? ' email-error-public' : '' }}"
                                class="auth-field block w-full rounded-xl border-2 border-brand-soft/80 bg-white/95 px-4 py-2.5 text-[0.9375rem] text-brand-dark placeholder:text-slate-400 transition focus:border-brand-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                                placeholder="you@example.com"
                            >
                            <p id="email-hint-public" class="text-xs font-medium text-[#1b5e20]">Guest or host account email.</p>
                            @error('email')
                                <p id="email-error-public" class="mt-1.5 flex items-start gap-1.5 text-xs font-semibold text-red-700" role="alert">
                                    <i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#14532d]">Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                class="auth-field block w-full rounded-xl border-2 border-brand-soft/80 bg-white/95 px-4 py-2.5 text-[0.9375rem] text-brand-dark transition focus:border-brand-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                                placeholder="Enter your password"
                            >
                            @error('password')
                                <p class="mt-1.5 flex items-start gap-1.5 text-xs font-semibold text-red-700" role="alert">
                                    <i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-3 pt-1 sm:flex-row sm:items-center sm:justify-between">
                            <label class="flex cursor-pointer items-center gap-2.5 text-sm font-semibold text-[#1b5e20]">
                                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-brand-soft text-brand-primary focus:ring-brand-primary focus:ring-offset-2">
                                Stay signed in
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-center text-sm font-bold text-[#166534] underline decoration-brand-dark/35 underline-offset-2 hover:text-[#14532d] sm:text-right">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="mt-1 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-dark to-brand-primary py-3 text-sm font-bold text-white shadow-[0_10px_28px_rgba(46,125,50,0.28)] transition hover:brightness-[1.04] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-dark active:translate-y-px disabled:cursor-not-allowed disabled:opacity-60" data-auth-submit>
                            <span>Sign in</span>
                            <i class="fas fa-arrow-right text-xs opacity-90" aria-hidden="true"></i>
                        </button>
                    </form>

                    <div class="mt-8 space-y-4 border-t border-emerald-200/80 pb-1 pt-8 text-center text-xs font-medium text-[#1b5e20] sm:mt-9 sm:space-y-5 sm:pt-9 sm:text-[0.8125rem]">
                        <p>
                            New here?
                            <a href="{{ route('register.guest') }}" class="font-bold text-[#14532d] underline decoration-[#1b5e20] decoration-2 underline-offset-2 hover:text-[#0d4710]">Guest registration</a>
                            <span class="text-emerald-700/55" aria-hidden="true"> · </span>
                            <a href="{{ route('register.owner') }}" class="font-bold text-[#14532d] underline decoration-[#1b5e20] decoration-2 underline-offset-2 hover:text-[#0d4710]">Host onboarding</a>
                        </p>
                        <p>
                            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-1.5 font-bold text-[#166534] underline decoration-brand-dark/30 underline-offset-2 hover:text-[#14532d]">
                                <i class="fas fa-chevron-left text-[0.65rem]" aria-hidden="true"></i>
                                Back to {{ $municipality }} directory
                            </a>
                        </p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        (function () {
            document.querySelectorAll('[data-auth-form]').forEach(function (form) {
                form.addEventListener('submit', function () {
                    var b = form.querySelector('[data-auth-submit]');
                    if (b) { b.disabled = true; b.setAttribute('aria-busy', 'true'); }
                });
            });
        })();
    </script>
</body>
</html>
