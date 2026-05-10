@php($municipality = config('portals.municipality_name', 'Impasug-ong'))
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="color-scheme" content="light dark">
    <meta name="description" content="Secure administration sign-in for accredited IMPASUGONG TOURISM platform operators.">
    <script>
        (function () {
            try {
                var t = localStorage.getItem('impa_auth_theme_admin');
                document.documentElement.classList.toggle(
                    'dark',
                    t === 'dark' || (!t && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)
                );
            } catch (e) {}
        })();
    </script>
    <title>Administration sign-in — IMPASUGONG TOURISM</title>
    @include('admin.partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .auth-skip:focus { clip: auto; height: auto; width: auto; overflow: visible; outline: none; padding: .75rem 1rem; top: .75rem; left: .75rem; z-index: 100; margin: 0; }
        .auth-skip { position: absolute; left: .75rem; top: -100px; clip: rect(0 0 0 0); height: 1px; width: 1px; overflow: hidden; white-space: nowrap; border: 0; border-radius: .5rem; background: rgb(27 94 32); color: rgb(255 255 255); box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2); }
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
                rgba(27, 94, 32, 0.6) 0%,
                rgba(46, 125, 50, 0.58) 45%,
                rgba(27, 94, 32, 0.62) 100%
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
                rgba(14, 55, 18, 0.94) 0%,
                rgba(27, 94, 32, 0.9) 42%,
                rgba(18, 70, 24, 0.93) 100%
            );
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.22), inset 0 1px 0 rgba(255, 255, 255, 0.08);
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
    </style>
</head>
<body class="min-h-full min-h-[100dvh] bg-white text-base text-slate-900 antialiased">
    <a href="#admin-signin-main" class="auth-skip text-sm font-semibold">Skip to sign-in form</a>

    <div class="admin-login-shell">
        {{-- Left: communal / branding ~60% (desktop) --}}
        <aside
            class="admin-login-shell__hero relative order-1 flex min-h-0 flex-col lg:order-1"
            aria-label="Impasugong Tourism administration"
        >
            <div class="admin-login-hero-photo pointer-events-none absolute inset-0" aria-hidden="true"></div>
            <div class="admin-login-hero-green pointer-events-none absolute inset-0" aria-hidden="true"></div>
            <div class="relative z-[1] flex w-full flex-1 flex-col items-center justify-center px-6 py-12 sm:px-10 sm:py-14 lg:px-12 lg:py-16">
                <div class="flex w-full flex-shrink-0 flex-wrap items-center justify-center gap-x-8 gap-y-6 sm:gap-x-10 sm:gap-y-7 lg:gap-x-12" aria-label="Official partner marks">
                    <img src="{{ asset('Love Impasugong.png') }}" alt="" class="admin-login-logo-tall" width="260" height="156" fetchpriority="high" decoding="async" role="presentation">
                    <img src="{{ asset('SYSTEMLOGO.png') }}" alt="" class="admin-login-logo-tall" width="260" height="260" decoding="async" role="presentation">
                    <img src="{{ asset('Lgu Socmed Template-02 2.png') }}" alt="" class="admin-login-logo-wide w-full max-w-xl basis-full sm:basis-auto sm:w-auto sm:max-w-none" width="480" height="150" loading="lazy" decoding="async" role="presentation">
                </div>
                <section class="admin-login-copy-panel mt-10 px-6 py-8 sm:mt-11 sm:px-7 sm:py-9" aria-labelledby="admin-login-hero-heading">
                    <div class="text-left text-white [text-shadow:_0_1px_2px_rgba(0,0,0,0.35)]">
                        <p id="admin-login-console-label" class="text-xs font-bold uppercase tracking-[0.22em] text-white sm:text-[0.8rem]">Administration console</p>
                        <h1 id="admin-login-hero-heading" class="mt-4 text-2xl font-extrabold leading-tight tracking-tight sm:text-3xl lg:text-[2rem] lg:leading-snug xl:text-[2.05rem]">Municipal operations &amp; compliance</h1>
                        <p class="mt-4 text-[0.95rem] font-medium leading-relaxed text-white/96 sm:text-base">
                            Review lodging applications, manage directory compliance, and perform delegated oversight for <span class="font-semibold text-white">{{ $municipality }}</span>.
                        </p>
                    </div>
                    <ul class="mt-8 space-y-4 border-t border-white/15 pt-8 text-sm font-semibold leading-relaxed text-white sm:text-[0.95rem]" aria-labelledby="admin-login-console-label">
                        <li class="flex gap-3 text-left">
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/12 text-white ring-1 ring-white/20 backdrop-blur-[2px]" aria-hidden="true"><i class="fas fa-shield-halved text-sm"></i></span>
                            <span>Secure authentication with password recovery.</span>
                        </li>
                        <li class="flex gap-3 text-left">
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/12 text-white ring-1 ring-white/20 backdrop-blur-[2px]" aria-hidden="true"><i class="fas fa-user-shield text-sm"></i></span>
                            <span>Role-based access for authorized municipal personnel.</span>
                        </li>
                        <li class="flex gap-3 text-left">
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/12 text-white ring-1 ring-white/20 backdrop-blur-[2px]" aria-hidden="true"><i class="fas fa-clipboard-check text-sm"></i></span>
                            <span>Audit-ready workflows for verification and reporting.</span>
                        </li>
                    </ul>
                </section>
            </div>
        </aside>

        {{-- Right: sign-in ~40% — balanced, centered card --}}
        <div class="admin-login-shell__form order-2 flex min-h-0 flex-col bg-white lg:order-2">
            <main id="admin-signin-main" tabindex="-1" class="flex min-h-0 flex-1 flex-col items-center justify-center overflow-y-auto px-6 py-12 sm:px-10 sm:py-14 lg:px-10 lg:py-12 xl:px-12">
                <div class="admin-login-signin-card admin-login-form-panel auth-form-panel [color-scheme:light] w-full max-w-md rounded-3xl border border-slate-200/90 bg-white p-7 text-[#1b5e20] shadow-[0_8px_30px_rgba(15,23,42,0.08)] ring-1 ring-slate-900/[0.03] sm:p-8 lg:max-w-[22.5rem] lg:rounded-[1.65rem] xl:max-w-sm">
                    <header class="mb-8 text-left">
                        <h2 class="text-[1.62rem] font-extrabold tracking-tight text-brand-dark sm:text-[1.72rem]">Administrator sign-in</h2>
                        <p class="admin-login-form-body mt-3 text-[0.95rem] leading-relaxed sm:text-[1.02rem]">
                            Enter the credentials issued to your municipal or ICT role. Need access? Contact your security delegate.
                        </p>
                    </header>

                    @if (session('status'))
                        <div class="mb-6 flex gap-3 rounded-2xl border-2 border-emerald-400/70 bg-emerald-50 px-4 py-3.5 text-[0.9375rem] font-medium text-emerald-950 dark:border-emerald-600 dark:bg-emerald-950/35 dark:text-emerald-50" role="status" aria-live="polite">
                            <i class="fas fa-circle-check mt-0.5 shrink-0 text-emerald-700 dark:text-emerald-400" aria-hidden="true"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <div id="admin-signin-errors" class="sr-only" aria-live="polite"></div>

                    <form method="POST" action="{{ url('/login') }}" class="space-y-6" autocomplete="on" novalidate data-auth-form aria-describedby="form-intro-admin">
                        @csrf
                        <input type="hidden" name="portal" value="admin">
                        <p id="form-intro-admin" class="sr-only">Sign in using your municipality-issued organizational email address and password.</p>

                        <div class="space-y-2">
                            <label for="email" class="admin-login-form-label block text-sm font-bold uppercase tracking-wide">Organizational email</label>
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
                                class="auth-field admin-login-form-input mt-2 block w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3.5 text-[0.9625rem] shadow-sm transition hover:border-brand-soft focus:border-brand-primary focus:outline-none focus:ring-4 focus:ring-brand-primary/16"
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
                                class="admin-login-password-wrap mt-2 flex w-full min-w-0 items-stretch overflow-hidden rounded-xl border-2 border-slate-200 bg-white shadow-sm transition hover:border-brand-soft focus-within:border-brand-primary focus-within:outline-none focus-within:ring-4 focus-within:ring-brand-primary/16"
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
                                    class="flex w-11 shrink-0 items-center justify-center self-stretch border-0 bg-transparent text-[#1b5e20] transition hover:bg-slate-100 hover:text-[#0d4710] focus-visible:z-10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-brand-dark"
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

                        <div class="flex flex-col gap-4 pt-1 sm:flex-row sm:items-center sm:justify-between">
                            <label class="admin-login-form-body flex cursor-pointer items-center gap-3 text-[0.9375rem] font-medium">
                                <input type="checkbox" name="remember" class="h-4 w-4 shrink-0 rounded border-slate-400 text-brand-dark focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 focus:ring-offset-white">
                                Keep me signed in on this device
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="admin-login-form-link text-[0.9375rem] font-bold underline decoration-2 underline-offset-[0.22em] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-dark sm:text-right">
                                    Reset password
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-dark to-brand-primary px-4 py-4 text-[0.9625rem] font-bold text-white shadow-[0_12px_32px_-8px_rgba(27,94,32,0.42)] transition hover:brightness-[1.05] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-dark active:translate-y-px disabled:cursor-not-allowed disabled:opacity-60" data-auth-submit>
                            <span>Sign in to console</span>
                            <i class="fas fa-arrow-right-to-bracket text-sm opacity-90" aria-hidden="true"></i>
                        </button>
                    </form>

                    <footer class="mt-9 border-t border-slate-200 pt-8 text-left text-[0.9rem] leading-relaxed sm:text-[0.9375rem]">
                        <p class="admin-login-form-body">Accounts are provisioned by ICT or tourism leadership. Do not share credentials.</p>
                        <p class="mt-5">
                            <a href="{{ url('/') }}" class="admin-login-form-link inline-flex items-center gap-2 text-[0.9375rem] font-bold underline decoration-2 underline-offset-[0.22em] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-dark">
                                <i class="fas fa-arrow-left text-sm" aria-hidden="true"></i>
                                Return to administration entry
                            </a>
                        </p>
                    </footer>
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
