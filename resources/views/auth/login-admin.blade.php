@php($municipality = config('portals.municipality_name', 'Impasug-ong'))
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="color-scheme" content="light">
    <meta name="description" content="Secure administration sign-in for accredited IMPASUGONG TOURISM platform operators.">
    <title>Administration sign-in — IMPASUGONG TOURISM</title>
    @include('admin.partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Match admin dashboard chart palette (Location / Age / Guests Per Month) */
        :root {
            --admin-chart-forest: #1b5e20;
            --admin-chart-header: #166534;
            --admin-chart-primary: #22c55e;
            --admin-chart-mint: #4ade80;
            --admin-chart-light: #bbf7d0;
            --admin-chart-surface: #fafdfb;
            --admin-chart-border: rgba(34, 197, 94, 0.22);
            --admin-chart-muted: #94a3b8;
        }
        .auth-skip:focus { clip: auto; height: auto; width: auto; overflow: visible; outline: none; padding: .75rem 1rem; top: .75rem; left: .75rem; z-index: 100; margin: 0; }
        .auth-skip { position: absolute; left: .75rem; top: -100px; clip: rect(0 0 0 0); height: 1px; width: 1px; overflow: hidden; white-space: nowrap; border: 0; border-radius: .5rem; background: var(--admin-chart-header); color: #fff; box-shadow: 0 10px 15px rgba(22, 101, 52, 0.25); }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
            .admin-login-hero-photo { filter: none !important; transform: none !important; }
            .admin-login-copy-panel { backdrop-filter: none !important; -webkit-backdrop-filter: none !important; }
        }
        .admin-login-hero-photo {
            background-image: url('{{ asset('COMMUNAL.jpg') }}');
            background-size: cover;
            background-position: center;
            /* ~10% blur vs strong reference — soften photo before green wash */
            filter: blur(6.4px);
            transform: scale(1.09);
            transform-origin: center;
        }
        /* 60% opacity brand-green layer — boosts contrast for foreground copy */
        .admin-login-hero-green {
            background: linear-gradient(
                165deg,
                rgba(22, 101, 52, 0.72) 0%,
                rgba(34, 197, 94, 0.45) 42%,
                rgba(27, 94, 32, 0.68) 100%
            );
        }
        @media (prefers-contrast: more) {
            .auth-field { border-width: 2px !important; border-color: CanvasText !important; }
            .auth-form-panel { outline: 2px solid CanvasText !important; outline-offset: 2px; }
        }
        .admin-login-logo-tall {
            height: auto;
            max-height: 6.5rem;
            width: auto;
            max-width: min(46vw, 14.5rem);
            object-fit: contain;
            filter: drop-shadow(0 4px 16px rgba(0, 0, 0, 0.28));
        }
        @media (min-width: 640px) {
            .admin-login-logo-tall { max-height: 7.75rem; max-width: 17rem; }
        }
        @media (min-width: 1024px) {
            .admin-login-logo-tall { max-height: 9rem; max-width: 19rem; }
        }
        .admin-login-logo-wide {
            height: auto;
            max-height: 5.25rem;
            width: auto;
            max-width: min(94vw, 26rem);
            object-fit: contain;
            filter: drop-shadow(0 4px 16px rgba(0, 0, 0, 0.24));
        }
        @media (min-width: 640px) {
            .admin-login-logo-wide { max-height: 6.25rem; max-width: 28rem; }
        }
        @media (min-width: 1024px) {
            .admin-login-logo-wide { max-height: 7.25rem; max-width: 30rem; }
        }
        /* Dark green gradient card behind hero copy (~10% blur) */
        .admin-login-copy-panel {
            background: linear-gradient(
                152deg,
                rgba(20, 83, 45, 0.96) 0%,
                rgba(22, 101, 52, 0.92) 45%,
                rgba(27, 94, 32, 0.94) 100%
            );
            border-radius: 1.25rem;
            border: 1px solid rgba(187, 247, 208, 0.35);
            box-shadow: 0 20px 50px rgba(22, 101, 52, 0.28), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(6.4px);
            -webkit-backdrop-filter: blur(6.4px);
            width: 100%;
            max-width: 26rem;
            margin-left: auto;
            margin-right: auto;
        }
        @media (min-width: 640px) {
            .admin-login-copy-panel { max-width: 30rem; }
        }
        /* 60 / 40 split — plain CSS so layout never depends on Tailwind JIT for arbitrary widths */
        .admin-login-shell {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            width: 100%;
            min-height: 100dvh;
        }
        .admin-login-shell__hero {
            position: relative;
            flex-shrink: 0;
            width: 100%;
            min-width: 0;
            overflow: hidden;
            min-height: min(46vh, 26rem);
        }
        .admin-login-shell__form {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            width: 100%;
            min-width: 0;
            min-height: 0;
        }
        @media (min-width: 1024px) {
            .admin-login-shell {
                flex-direction: row;
                min-height: 100vh;
                min-height: 100dvh;
            }
            .admin-login-shell__hero {
                flex: 0 0 60%;
                width: 60%;
                max-width: 60%;
                min-height: 100vh !important;
                min-height: 100dvh !important;
            }
            .admin-login-shell__form {
                flex: 0 0 40%;
                width: 40%;
                max-width: 40%;
                min-height: 100vh;
                min-height: 100dvh;
            }
        }
        .admin-login-form-panel {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        /*
         * Sign-in card: always dark forest green on white.
         *
         * When html.dark is set (prefers OS dark theme), removing light-mint typography here avoids
         * pale text on what is still visually a white card — that looked like missing copy.
         * Shell/background stays dark-capable via Tailwind; the card intentionally stays readable.
         */
        .admin-login-form-panel.admin-login-signin-card .admin-login-form-body,
        .admin-login-form-panel.admin-login-signin-card label.admin-login-form-label {
            color: #1b5e20 !important;
        }
        .admin-login-form-panel.admin-login-signin-card a.admin-login-form-link {
            color: #1b5e20 !important;
        }
        .admin-login-form-panel.admin-login-signin-card a.admin-login-form-link:hover,
        .admin-login-form-panel.admin-login-signin-card a.admin-login-form-link:focus-visible {
            color: #0d4710 !important;
            text-decoration-color: currentColor !important;
        }
        .admin-login-form-panel.admin-login-signin-card input.admin-login-form-input {
            color: #1b5e20 !important;
            -webkit-text-fill-color: #1b5e20 !important;
            caret-color: #1b5e20 !important;
        }
        .admin-login-form-panel.admin-login-signin-card input.admin-login-form-input::placeholder {
            color: rgba(27, 94, 32, 0.76) !important;
            opacity: 1 !important;
        }
        /* Chrome/WebKit autofill uses its own palette and can fight text color */
        .admin-login-form-panel.admin-login-signin-card input.admin-login-form-input:-webkit-autofill,
        .admin-login-form-panel.admin-login-signin-card input.admin-login-form-input:-webkit-autofill:hover,
        .admin-login-form-panel.admin-login-signin-card input.admin-login-form-input:-webkit-autofill:focus {
            -webkit-text-fill-color: #1b5e20 !important;
            caret-color: #1b5e20 !important;
            box-shadow: 0 0 0 100rem #ffffff inset !important;
            transition: background-color 99999s ease-out;
        }
        /* Password + visibility toggle: one box; inner input has no second border */
        .admin-login-signin-card .admin-login-password-wrap .auth-field.admin-login-form-input {
            border-width: 0 !important;
            box-shadow: none !important;
        }
        @media (prefers-contrast: more) {
            .admin-login-signin-card .admin-login-password-wrap .auth-field.admin-login-form-input {
                border-width: 0 !important;
            }
            .admin-login-signin-card .admin-login-password-wrap {
                border-width: 2px !important;
                border-color: CanvasText !important;
            }
        }
        .admin-login-shell__form {
            background: linear-gradient(180deg, #f9fafb 0%, var(--admin-chart-surface) 100%);
        }
        .admin-login-signin-card {
            overflow: hidden;
            border: 1px solid var(--admin-chart-border) !important;
            background: #ffffff !important;
            box-shadow: 0 8px 28px rgba(22, 101, 52, 0.1), 0 4px 18px rgba(22, 101, 52, 0.06) !important;
        }
        .admin-login-signin-card__head {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            margin: 0 -1.75rem 1.5rem;
            padding: 0.85rem 1.25rem;
            background: var(--admin-chart-header);
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }
        @media (min-width: 640px) {
            .admin-login-signin-card__head { margin: 0 -2rem 1.5rem; padding: 0.9rem 1.5rem; }
        }
        .admin-login-signin-card__head i {
            color: var(--admin-chart-light);
            font-size: 1.05rem;
        }
        .admin-login-signin-card__head span {
            font-size: 0.88rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .admin-login-chart-accent {
            display: flex;
            height: 4px;
            margin: -1.75rem -1.75rem 0.75rem;
            border-radius: 1.65rem 1.65rem 0 0;
            overflow: hidden;
        }
        @media (min-width: 640px) {
            .admin-login-chart-accent { margin: -2rem -2rem 0.85rem; }
        }
        .admin-login-chart-accent span:nth-child(1) { flex: 1; background: #14532d; }
        .admin-login-chart-accent span:nth-child(2) { flex: 1; background: var(--admin-chart-mint); }
        .admin-login-chart-accent span:nth-child(3) { flex: 1; background: var(--admin-chart-muted); }
        .admin-login-form-panel.admin-login-signin-card .auth-field.admin-login-form-input,
        .admin-login-form-panel.admin-login-signin-card .admin-login-password-wrap {
            border-color: rgba(34, 197, 94, 0.35) !important;
            background: var(--admin-chart-surface) !important;
        }
        .admin-login-form-panel.admin-login-signin-card .auth-field.admin-login-form-input:hover,
        .admin-login-form-panel.admin-login-signin-card .admin-login-password-wrap:hover {
            border-color: rgba(74, 222, 128, 0.65) !important;
        }
        .admin-login-form-panel.admin-login-signin-card .auth-field.admin-login-form-input:focus,
        .admin-login-form-panel.admin-login-signin-card .admin-login-password-wrap:focus-within {
            border-color: var(--admin-chart-primary) !important;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.18) !important;
        }
        .admin-login-form-panel.admin-login-signin-card button[type="submit"] {
            background: linear-gradient(180deg, var(--admin-chart-mint) 0%, var(--admin-chart-header) 55%, var(--admin-chart-forest) 100%) !important;
            box-shadow: 0 12px 28px -6px rgba(22, 101, 52, 0.45) !important;
        }
        .admin-login-form-panel.admin-login-signin-card footer {
            border-color: rgba(34, 197, 94, 0.2) !important;
        }
        .admin-login-hero-bullet--local { background: rgba(20, 83, 45, 0.55) !important; }
        .admin-login-hero-bullet--accent { background: rgba(74, 222, 128, 0.35) !important; }
    </style>
</head>
<body class="min-h-full min-h-[100dvh] bg-[#f9fafb] text-base text-slate-900 antialiased">
    <a href="#admin-signin-main" class="auth-skip text-sm font-semibold">Skip to sign-in form</a>

    <div class="auth-shell">
        <aside class="auth-hero" aria-label="Impasugong Tourism administration">
            <div class="auth-hero__photo" aria-hidden="true"></div>
            <div class="auth-hero__scrim" aria-hidden="true"></div>
            <div class="auth-hero__content">
                <div class="flex flex-wrap items-center gap-x-2 gap-y-3 sm:gap-x-3">
                    <img src="{{ asset('images/love-impasugong-transparent.png') }}" alt="" class="h-24 w-auto object-contain sm:h-28 lg:h-36" decoding="async" role="presentation">
                    <img src="{{ asset('SYSTEMLOGO.png') }}" alt="" class="h-24 w-auto object-contain sm:h-28 lg:h-36" decoding="async" role="presentation">
                    <img src="{{ asset('Lgu Socmed Template-02 2.png') }}" alt="" class="h-20 w-auto object-contain sm:h-24 lg:h-32" decoding="async" role="presentation">
                </div>

                <div class="mt-3 sm:mt-4">
                    <span class="auth-eyebrow">Administration console</span>
                    <h1 class="auth-display mt-4 text-[2rem] font-semibold leading-[1.1] text-slate-900 sm:text-[2.4rem] lg:text-[2.75rem]">
                        Welcome back.
                    </h1>
                    <p class="mt-4 max-w-md text-[15px] leading-relaxed text-slate-600">
                        Review lodging applications, manage directory compliance, and perform delegated oversight for <span class="font-medium text-slate-800">{{ $municipality }}</span>.
                    </p>
                </div>

                <dl class="mt-10 hidden grid-cols-1 gap-5 sm:grid-cols-2 lg:mt-14 lg:grid">
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Secure</dt>
                        <dd class="mt-1.5 text-[13.5px] leading-snug text-slate-700">Password recovery and audit-ready workflows.</dd>
                    </div>
                    <ul class="mt-8 space-y-4 border-t border-white/15 pt-8 text-sm font-semibold leading-relaxed text-white sm:text-[0.95rem]" aria-labelledby="admin-login-console-label">
                        <li class="flex gap-3 text-left">
                            <span class="admin-login-hero-bullet--local mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/12 text-white ring-1 ring-white/20 backdrop-blur-[2px]" aria-hidden="true"><i class="fas fa-shield-halved text-sm"></i></span>
                            <span>Secure authentication with password recovery.</span>
                        </li>
                        <li class="flex gap-3 text-left">
                            <span class="admin-login-hero-bullet--accent mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/12 text-white ring-1 ring-white/20 backdrop-blur-[2px]" aria-hidden="true"><i class="fas fa-user-shield text-sm"></i></span>
                            <span>Role-based access for authorized municipal personnel.</span>
                        </li>
                        <li class="flex gap-3 text-left">
                            <span class="admin-login-hero-bullet--local mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/12 text-white ring-1 ring-white/20 backdrop-blur-[2px]" aria-hidden="true"><i class="fas fa-clipboard-check text-sm"></i></span>
                            <span>Audit-ready workflows for verification and reporting.</span>
                        </li>
                    </ul>
                </section>
            </div>
        </aside>

        {{-- Right: sign-in ~40% — balanced, centered card --}}
        <div class="admin-login-shell__form order-2 flex min-h-0 flex-col lg:order-2">
            <main id="admin-signin-main" tabindex="-1" class="flex min-h-0 flex-1 flex-col items-center justify-center overflow-y-auto px-6 py-12 sm:px-10 sm:py-14 lg:px-10 lg:py-12 xl:px-12">
                <div class="admin-login-signin-card admin-login-form-panel auth-form-panel [color-scheme:light] w-full max-w-md rounded-3xl p-7 text-[#14532d] sm:p-8 lg:max-w-[22.5rem] lg:rounded-[1.65rem] xl:max-w-sm">
                    <div class="admin-login-chart-accent" aria-hidden="true">
                        <span></span><span></span><span></span>
                    </div>
                    <div class="admin-login-signin-card__head" aria-hidden="true">
                        <i class="fas fa-chart-column"></i>
                        <span>Municipal analytics console</span>
                    </div>
                    <header class="mb-8 text-left">
                        <h2 class="text-[1.62rem] font-extrabold tracking-tight text-[#14532d] sm:text-[1.72rem]">Administrator sign-in</h2>
                        <p class="admin-login-form-body mt-3 text-[0.95rem] leading-relaxed sm:text-[1.02rem]">
                            Enter the credentials issued to your municipal or ICT role. Need access? Contact your security delegate.
                        </p>
                    </header>

                <header class="mb-8">
                    <h2 class="auth-display text-[1.55rem] font-semibold tracking-tight text-slate-900">Administrator sign-in</h2>
                    <p class="mt-2 text-[14.5px] leading-relaxed text-slate-600">
                        Enter the credentials issued to your municipal or ICT role.
                    </p>
                </header>

                @if (session('status'))
                    <div class="auth-alert mb-6" role="status" aria-live="polite">
                        <i class="fas fa-circle-check mt-0.5 shrink-0" aria-hidden="true"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <div id="admin-signin-errors" class="sr-only" aria-live="polite"></div>

                <form method="POST" action="{{ url('/login') }}" class="space-y-5" autocomplete="on" novalidate data-auth-form aria-describedby="form-intro-admin">
                    @csrf
                    <input type="hidden" name="portal" value="admin">
                    <p id="form-intro-admin" class="sr-only">Sign in using your municipality-issued organizational email address and password.</p>

                    <div>
                        <label for="email" class="auth-label block">Organizational email</label>
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
                            aria-describedby="email-hint-admin{{ $errors->has('email') ? ' email-error-admin' : '' }}"
                            class="auth-input mt-2"
                            placeholder="name@department.gov.ph"
                        >
                        <p id="email-hint-admin" class="mt-1.5 text-[12.5px] text-slate-500">Use the official email tied to your administrator record.</p>
                        @error('email')
                            <p id="email-error-admin" class="auth-error mt-2" role="alert">
                                <i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="auth-label block">Password</label>
                        <div class="auth-password mt-2">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autofocus
                                autocomplete="username"
                                inputmode="email"
                                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                aria-describedby="email-hint-admin{{ $errors->has('email') ? ' email-error-admin' : '' }}"
                                class="auth-field admin-login-form-input mt-2 block w-full rounded-xl border-2 bg-[#fafdfb] px-4 py-3.5 text-[0.9625rem] shadow-sm transition"
                                placeholder="name@department.gov.ph"
                            >
                            <p id="email-hint-admin" class="admin-login-form-body mt-2 text-[0.8125rem] leading-snug sm:text-sm">Use the official email tied to your administrator record.</p>
                            @error('email')
                                <p id="email-error-admin" class="mt-2 flex items-start gap-2 text-sm font-semibold text-red-700 dark:text-red-300" role="alert">
                                    <i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="admin-login-form-label block text-sm font-bold uppercase tracking-wide">Password</label>
                            <div
                                class="admin-login-password-wrap mt-2 flex w-full min-w-0 items-stretch overflow-hidden rounded-xl border-2 bg-[#fafdfb] shadow-sm transition"
                                role="group"
                                aria-label="Password"
                            >
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                    class="auth-field admin-login-form-input min-h-[3.25rem] min-w-0 flex-1 border-0 bg-transparent py-3.5 pl-4 pr-2 text-[0.9625rem] shadow-none ring-0 focus:border-0 focus:outline-none focus:ring-0 focus-visible:ring-0"
                                    placeholder="Enter your password"
                                >
                                <button
                                    type="button"
                                    class="flex w-11 shrink-0 items-center justify-center self-stretch border-0 bg-transparent text-[#166534] transition hover:bg-[#ecfdf5] hover:text-[#14532d] focus-visible:z-10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-[#166534]"
                                    aria-controls="password"
                                    aria-label="Show password"
                                    aria-pressed="false"
                                    data-password-toggle-admin
                                >
                                    <i class="fas fa-eye text-base" aria-hidden="true"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 flex items-start gap-2 text-sm font-semibold text-red-700 dark:text-red-300" role="alert">
                                    <i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                        @error('password')
                            <p class="auth-error mt-2" role="alert">
                                <i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                        <div class="flex flex-col gap-4 pt-1 sm:flex-row sm:items-center sm:justify-between">
                            <label class="admin-login-form-body flex cursor-pointer items-center gap-3 text-[0.9375rem] font-medium">
                                <input type="checkbox" name="remember" class="h-4 w-4 shrink-0 rounded border-[#86efac] text-[#166534] focus:ring-2 focus:ring-[#22c55e] focus:ring-offset-2 focus:ring-offset-white">
                                Keep me signed in on this device
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="admin-login-form-link text-[0.9375rem] font-bold underline decoration-2 underline-offset-[0.22em] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-dark sm:text-right">
                                    Reset password
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-4 text-[0.9625rem] font-bold text-white transition hover:brightness-[1.06] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#166534] active:translate-y-px disabled:cursor-not-allowed disabled:opacity-60" data-auth-submit>
                            <span>Sign in to console</span>
                            <i class="fas fa-arrow-right-to-bracket text-sm opacity-90" aria-hidden="true"></i>
                        </button>
                    </form>

                <footer class="mt-10 border-t border-slate-200 pt-6 text-[13.5px] leading-relaxed text-slate-600">
                    <p>Accounts are provisioned by ICT or tourism leadership. Do not share credentials.</p>
                </footer>
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
            document.querySelectorAll('[data-password-toggle-admin]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = btn.getAttribute('aria-controls');
                    var input = id ? document.getElementById(id) : null;
                    if (!input || (input.type !== 'password' && input.type !== 'text')) {
                        return;
                    }
                    var willShow = input.type === 'password';
                    input.type = willShow ? 'text' : 'password';
                    var visible = input.type === 'text';
                    btn.setAttribute('aria-pressed', visible ? 'true' : 'false');
                    btn.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');
                    var icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-eye', !visible);
                        icon.classList.toggle('fa-eye-slash', visible);
                    }
                });
            });
        })();
    </script>
</body>
</html>
