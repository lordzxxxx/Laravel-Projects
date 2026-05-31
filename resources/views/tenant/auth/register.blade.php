<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="color-scheme" content="light dark">
    <meta name="description" content="Create a guest account for {{ $tenant->name ?? 'this business' }} — book stays and manage reservations.">
    <title>Create account — {{ $tenant->name ?? 'Tenant' }}</title>
    @include('partials.tenant-favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @include('partials.appearance-boot')
    <style>
        @include('partials.ui-foundation-styles')
    </style>
    <style>
        :root {
            @include('partials.tenant-theme-css-vars', ['themeTenant' => $tenant])
            --auth-ink: #0f172a;
            --auth-muted: #475569;
            --auth-border: #e5e7eb;
            --auth-accent: var(--green-primary, #1b5e20);
            --auth-accent-strong: var(--green-dark, #0d4710);
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

        .auth-form-wrap { flex:1 1 auto; display:flex; align-items:flex-start; justify-content:center; padding: 2rem 1.5rem 3rem; min-height: 0; }
        .auth-card { width:100%; max-width: 30rem; }

        .auth-eyebrow { display:inline-flex; align-items:center; gap:.5rem; font-size:.7rem; font-weight:600; letter-spacing:.18em; text-transform:uppercase; color:var(--auth-accent); }
        .auth-eyebrow::before { content:""; display:block; height:1px; width:1.25rem; background: color-mix(in srgb, var(--auth-accent) 50%, transparent); }

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
        .auth-input:focus { outline:none; border-color:var(--auth-accent); box-shadow:0 0 0 4px color-mix(in srgb, var(--auth-accent) 12%, transparent); }

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

        .auth-error {
            display:flex; gap:.5rem; align-items:flex-start;
            font-size:.825rem; color:#b91c1c; font-weight:500;
        }

        .reg-section + .reg-section { margin-top: 1.75rem; border-top: 1px solid var(--auth-border); padding-top: 1.75rem; }
        .reg-section-label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: #334155; }
        .reg-optional { font-size: 0.62rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; color: #64748b; margin-left: 0.35rem; }

        @media (min-width: 1024px) {
            .auth-shell { flex-direction: row; align-items: stretch; }
            .auth-hero { flex: 0 0 55%; max-width:55%; min-height: 100dvh; }
            .auth-hero__content { padding: 4.5rem 3.5rem; max-width: 38rem; margin-top: auto; margin-bottom: auto; }
            .auth-form-wrap { flex: 0 0 45%; max-width: 45%; align-items: center; padding: 3rem clamp(2rem, 4vw, 3.5rem); min-height: 100dvh; }
            .auth-card { max-width: min(100%, 32rem); }
        }

        @media (prefers-reduced-motion: reduce) {
            .auth-hero__photo { filter: none; transform:none; }
        }
    </style>
</head>
<body>
    <a href="#tenant-register-main" class="auth-skip text-sm font-semibold">Skip to registration form</a>

    <div class="auth-shell">
        <aside class="auth-hero" aria-label="{{ $tenant->name }} registration">
            <div class="auth-hero__photo" aria-hidden="true"></div>
            <div class="auth-hero__scrim" aria-hidden="true"></div>
            <div class="auth-hero__content">
                @include('tenant.partials.auth-brand-logos', ['tenant' => $tenant])

                <div class="mt-3 sm:mt-4">
                    <span class="auth-eyebrow">Guest portal</span>
                    <h1 class="auth-display mt-4 text-[2rem] font-semibold leading-[1.1] text-slate-900 sm:text-[2.4rem] lg:text-[2.75rem]">
                        Create your account.
                    </h1>
                    <p class="mt-4 max-w-md text-[15px] leading-relaxed text-slate-600">
                        Browse verified stays, coordinate bookings, and message hosts for
                        <span class="font-medium text-slate-800">{{ $tenant->name ?? 'this business' }}</span>.
                    </p>
                </div>

                <dl class="mt-10 hidden grid-cols-1 gap-5 sm:grid-cols-2 lg:mt-14 lg:grid">
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Book stays</dt>
                        <dd class="mt-1.5 text-[13.5px] leading-snug text-slate-700">Reserve accommodations and track your bookings in one place.</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Direct messaging</dt>
                        <dd class="mt-1.5 text-[13.5px] leading-snug text-slate-700">Message property owners and staff from your account.</dd>
                    </div>
                </dl>
            </div>
        </aside>

        <div class="auth-form-wrap">
            <main id="tenant-register-main" tabindex="-1" class="auth-card">
                <header class="mb-6">
                    <h2 class="auth-display text-[1.55rem] font-semibold tracking-tight text-slate-900">Create client account</h2>
                    <p class="mt-2 text-[14.5px] leading-relaxed text-slate-600">
                        Name, email, and a password — that's all you need to start booking on this portal.
                    </p>
                </header>

                <form method="POST" action="/register" class="space-y-0" autocomplete="on" novalidate data-auth-form aria-describedby="form-intro-register">
                    @csrf
                    <input type="hidden" name="role" value="client">
                    <p id="form-intro-register" class="sr-only">Create a guest account for {{ $tenant->name }}.</p>

                    <section class="reg-section" aria-labelledby="reg-contact-heading">
                        <h3 id="reg-contact-heading" class="reg-section-label mb-5">Your details</h3>
                        <div class="space-y-5">
                            <div>
                                <label for="name" class="auth-label block">Full name</label>
                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                    class="auth-input mt-2"
                                    placeholder="As shown on your ID"
                                >
                                @error('name')
                                    <p class="auth-error mt-2" role="alert">
                                        <i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="email" class="auth-label block">Email address</label>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="username"
                                        inputmode="email"
                                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                        aria-describedby="email-hint-register"
                                        class="auth-input mt-2"
                                        placeholder="you@example.com"
                                    >
                                    <p id="email-hint-register" class="mt-1.5 text-[12.5px] text-slate-500">Used for sign-in and booking updates.</p>
                                    @error('email')
                                        <p class="auth-error mt-2" role="alert">
                                            <i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="auth-label block">
                                        Phone
                                        <span class="reg-optional">Optional</span>
                                    </label>
                                    <input
                                        id="phone"
                                        type="tel"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        autocomplete="tel"
                                        inputmode="tel"
                                        aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                                        class="auth-input mt-2"
                                        placeholder="e.g. +63 917 123 4567"
                                    >
                                    @error('phone')
                                        <p class="auth-error mt-2" role="alert">
                                            <i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="reg-section" aria-labelledby="reg-password-heading">
                        <h3 id="reg-password-heading" class="reg-section-label">Password</h3>
                        <p class="mt-1.5 mb-5 text-[12.5px] leading-relaxed text-slate-500">Use your email and this password to sign in later.</p>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="password" class="auth-label block">Password</label>
                                <div class="auth-password mt-2">
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="new-password"
                                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                        class="auth-input"
                                        placeholder="Create a strong password"
                                    >
                                    <button
                                        type="button"
                                        class="auth-password__toggle"
                                        aria-controls="password"
                                        aria-label="Show password"
                                        aria-pressed="false"
                                        data-password-toggle-register
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

                            <div>
                                <label for="password_confirmation" class="auth-label block">Confirm password</label>
                                <div class="auth-password mt-2">
                                    <input
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                                        class="auth-input"
                                        placeholder="Same password again"
                                    >
                                    <button
                                        type="button"
                                        class="auth-password__toggle"
                                        aria-controls="password_confirmation"
                                        aria-label="Show confirm password"
                                        aria-pressed="false"
                                        data-password-toggle-register
                                    >
                                        <i class="fas fa-eye text-base" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="pt-6">
                        <button type="submit" class="auth-submit" data-auth-submit>
                            <span>Create account</span>
                            <i class="fas fa-user-plus text-sm opacity-90" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>

                <footer class="mt-10 border-t border-slate-200 pt-6 text-[13.5px] leading-relaxed text-slate-600">
                    <p>
                        Already registered?
                        <a href="/login?portal=client" class="auth-link">Sign in to client portal</a>
                    </p>
                    <p class="mt-4">
                        <a href="/" class="auth-link inline-flex items-center gap-2">
                            <i class="fas fa-arrow-left text-xs" aria-hidden="true"></i>
                            Return to {{ $tenant->name }} landing
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
            document.querySelectorAll('[data-password-toggle-register]').forEach(function (btn) {
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
                    btn.setAttribute('aria-label', visible ? 'Hide password' : (id === 'password_confirmation' ? 'Show confirm password' : 'Show password'));
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
