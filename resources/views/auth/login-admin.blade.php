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
        @include('partials.ui-foundation-styles')
    </style>
    <style>
        :root {
            --auth-ink: #0f172a;
            --auth-muted: #475569;
            --auth-border: #e5e7eb;
            --auth-accent: #1b5e20;
            --auth-accent-strong: #0d4710;
            --auth-bg: #f7f9f7;
        }
        html, body { height: 100%; }
        body {
            color: var(--auth-ink);
            background: var(--auth-bg);
            -webkit-font-smoothing: antialiased;
        }

        .auth-skip { position:absolute; left:.75rem; top:-100px; clip:rect(0 0 0 0); height:1px; width:1px; overflow:hidden; white-space:nowrap; border:0; border-radius:.5rem; background:var(--auth-accent); color:#fff; box-shadow:0 10px 15px rgba(0,0,0,.2); }
        .auth-skip:focus { clip:auto; height:auto; width:auto; overflow:visible; outline:none; padding:.75rem 1rem; top:.75rem; left:.75rem; z-index:100; margin:0; }

        .auth-shell { display:flex; flex-direction:column; min-height:100dvh; }
        .auth-hero { position:relative; overflow:hidden; min-height: min(38vh, 22rem); flex-shrink:0; }
        .auth-hero__photo { position:absolute; inset:0; background-image:url('{{ asset('COMMUNAL.jpg') }}'); background-size:cover; background-position:center; transform:scale(1.04); filter:blur(2px) saturate(.95); }
        .auth-hero__scrim { position:absolute; inset:0; background:linear-gradient(180deg, rgba(255,255,255,.55) 0%, rgba(255,255,255,.78) 65%, rgba(247,249,247,1) 100%); }
        .auth-hero__content { position:relative; z-index:1; width:100%; max-width: 36rem; margin:0 auto; padding:3rem 1.5rem 2rem; }

        .auth-form-wrap { flex:1 1 auto; display:flex; align-items:flex-start; justify-content:center; padding: 2rem 1.5rem 3rem; }
        .auth-card { width:100%; max-width: 26rem; }

        .auth-eyebrow { display:inline-flex; align-items:center; gap:.5rem; font-size:.7rem; font-weight:600; letter-spacing:.18em; text-transform:uppercase; color:var(--auth-accent); }
        .auth-eyebrow::before { content:""; display:block; height:1px; width:1.25rem; background: rgba(27,94,32,.5); }

        .auth-input {
            width:100%;
            background:#fff;
            border:1px solid var(--auth-border);
            border-radius:.625rem;
            padding: .75rem .9rem;
            font-size:.95rem;
            color:var(--auth-ink);
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .auth-input::placeholder { color:#94a3b8; }
        .auth-input:hover { border-color:#cbd5e1; }
        .auth-input:focus { outline:none; border-color:var(--auth-accent); box-shadow:0 0 0 4px rgba(27,94,32,.10); }

        .auth-label { font-size:.78rem; font-weight:600; letter-spacing:.06em; text-transform:uppercase; color:#475569; }

        .auth-password { position:relative; display:flex; align-items:stretch; }
        .auth-password .auth-input { padding-right:2.75rem; }
        .auth-password__toggle { position:absolute; right:.25rem; top:50%; transform:translateY(-50%); display:inline-flex; align-items:center; justify-content:center; width:2.25rem; height:2.25rem; border-radius:.5rem; border:0; background:transparent; color:#64748b; cursor:pointer; }
        .auth-password__toggle:hover { background:#f1f5f9; color:var(--auth-accent); }

        .auth-submit { display:inline-flex; width:100%; align-items:center; justify-content:center; gap:.5rem; background:var(--auth-ink); color:#fff; font-weight:600; font-size:.95rem; padding:.85rem 1rem; border-radius:.75rem; transition: background .15s ease, transform .05s ease; }
        .auth-submit:hover { background:#000; }
        .auth-submit:active { transform: translateY(1px); }
        .auth-submit:disabled { opacity:.65; cursor:not-allowed; }

        .auth-link { color:var(--auth-accent); font-weight:600; text-decoration:underline; text-decoration-thickness:1px; text-underline-offset:.2em; }
        .auth-link:hover { color:var(--auth-accent-strong); }

        .auth-alert {
            display:flex; gap:.6rem; align-items:flex-start;
            border:1px solid #bbf7d0; background:#f0fdf4; color:#14532d;
            padding:.7rem .85rem; border-radius:.625rem; font-size:.9rem;
        }
        .auth-error {
            display:flex; gap:.5rem; align-items:flex-start;
            font-size:.825rem; color:#b91c1c; font-weight:500;
        }

        @media (min-width: 1024px) {
            .auth-shell { flex-direction: row; }
            .auth-hero { flex: 0 0 55%; max-width:55%; min-height: 100dvh; }
            .auth-hero__content { padding: 4.5rem 3.5rem; max-width: 38rem; margin-top: auto; margin-bottom: auto; }
            .auth-form-wrap { flex: 0 0 45%; max-width: 45%; align-items: center; padding: 3rem 3rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            .auth-hero__photo { filter: none; transform:none; }
        }
    </style>
</head>
<body>
    <a href="#admin-signin-main" class="auth-skip text-sm font-semibold">Skip to sign-in form</a>

    <div class="auth-shell">
        <aside class="auth-hero" aria-label="Impasugong Tourism administration">
            <div class="auth-hero__photo" aria-hidden="true"></div>
            <div class="auth-hero__scrim" aria-hidden="true"></div>
            <div class="auth-hero__content">
                <div class="flex flex-wrap items-center gap-x-2 gap-y-3 sm:gap-x-3">
                    <img src="{{ asset('images/love-impasugong-watermark.png') }}" alt="" class="h-24 w-auto object-contain sm:h-28 lg:h-36" decoding="async" role="presentation">
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
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Role-based</dt>
                        <dd class="mt-1.5 text-[13.5px] leading-snug text-slate-700">Access for authorized municipal personnel only.</dd>
                    </div>
                </dl>
            </div>
        </aside>

        <div class="auth-form-wrap">
            <main id="admin-signin-main" tabindex="-1" class="auth-card">
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
                                autocomplete="current-password"
                                aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                class="auth-input"
                                placeholder="Enter your password"
                            >
                            <button
                                type="button"
                                class="auth-password__toggle"
                                aria-controls="password"
                                aria-label="Show password"
                                aria-pressed="false"
                                data-password-toggle-admin
                            >
                                <i class="fas fa-eye text-base" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="auth-error mt-2" role="alert">
                                <i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex cursor-pointer items-center gap-2 text-[13.5px] text-slate-600">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-2 focus:ring-slate-900/20">
                            Keep me signed in
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="auth-link text-[13.5px]">Reset password</a>
                        @endif
                    </div>

                    <button type="submit" class="auth-submit" data-auth-submit>
                        <span>Sign in to console</span>
                        <i class="fas fa-arrow-right-to-bracket text-sm opacity-90" aria-hidden="true"></i>
                    </button>
                </form>

                <footer class="mt-10 border-t border-slate-200 pt-6 text-[13.5px] leading-relaxed text-slate-600">
                    <p>Accounts are provisioned by ICT or tourism leadership. Do not share credentials.</p>
                    <p class="mt-4">
                        <a href="{{ url('/') }}" class="auth-link inline-flex items-center gap-2">
                            <i class="fas fa-arrow-left text-xs" aria-hidden="true"></i>
                            Return to administration entry
                        </a>
                    </p>
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
