<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="color-scheme" content="light dark">
    <meta name="description" content="Reset your password for {{ $tenant->name ?? 'this business' }}.">
    <title>Reset password — {{ $tenant->name ?? 'Tenant' }}</title>
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
        .auth-card { width:100%; max-width: 26rem; }

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
            .auth-shell { flex-direction: row; align-items: stretch; }
            .auth-hero { flex: 0 0 55%; max-width:55%; min-height: 100dvh; }
            .auth-hero__content { padding: 4.5rem 3.5rem; max-width: 38rem; margin-top: auto; margin-bottom: auto; }
            .auth-form-wrap { flex: 0 0 45%; max-width: 45%; align-items: center; padding: 3rem clamp(2rem, 4vw, 3.5rem); min-height: 100dvh; }
        }

        @media (prefers-reduced-motion: reduce) {
            .auth-hero__photo { filter: none; transform:none; }
        }
    </style>
</head>
<body>
    <a href="#tenant-forgot-main" class="auth-skip text-sm font-semibold">Skip to password reset form</a>

    <div class="auth-shell">
        <aside class="auth-hero" aria-label="{{ $tenant->name }} password reset">
            <div class="auth-hero__photo" aria-hidden="true"></div>
            <div class="auth-hero__scrim" aria-hidden="true"></div>
            <div class="auth-hero__content">
                @include('tenant.partials.auth-brand-logos', ['tenant' => $tenant])

                <div class="mt-3 sm:mt-4">
                    <span class="auth-eyebrow">Account recovery</span>
                    <h1 class="auth-display mt-4 text-[2rem] font-semibold leading-[1.1] text-slate-900 sm:text-[2.4rem] lg:text-[2.75rem]">
                        Reset your password.
                    </h1>
                    <p class="mt-4 max-w-md text-[15px] leading-relaxed text-slate-600">
                        We'll email a secure link so you can choose a new password for your account on
                        <span class="font-medium text-slate-800">{{ $tenant->name ?? 'this business' }}</span>.
                    </p>
                </div>

                <dl class="mt-10 hidden grid-cols-1 gap-5 sm:grid-cols-2 lg:mt-14 lg:grid">
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Secure</dt>
                        <dd class="mt-1.5 text-[13.5px] leading-snug text-slate-700">Reset links expire and are tied to your registered email.</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Check inbox</dt>
                        <dd class="mt-1.5 text-[13.5px] leading-snug text-slate-700">Look for the message in spam or promotions if it doesn't arrive.</dd>
                    </div>
                </dl>
            </div>
        </aside>

        <div class="auth-form-wrap">
            <main id="tenant-forgot-main" tabindex="-1" class="auth-card">
                <header class="mb-6">
                    <h2 class="auth-display text-[1.55rem] font-semibold tracking-tight text-slate-900">Forgot password</h2>
                    <p class="mt-2 text-[14.5px] leading-relaxed text-slate-600">
                        Enter the email address for your account. We'll send a link to reset your password.
                    </p>
                </header>

                @if (session('status'))
                    <div class="auth-alert mb-6" role="status" aria-live="polite">
                        <i class="fas fa-circle-check mt-0.5 shrink-0" aria-hidden="true"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="/forgot-password" class="space-y-5" autocomplete="on" novalidate data-auth-form aria-describedby="form-intro-forgot">
                    @csrf
                    <p id="form-intro-forgot" class="sr-only">Request a password reset link for {{ $tenant->name }}.</p>

                    <div>
                        <label for="email" class="auth-label block">Email address</label>
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
                            aria-describedby="email-hint-forgot{{ $errors->has('email') ? ' email-error-forgot' : '' }}"
                            class="auth-input mt-2"
                            placeholder="you@example.com"
                        >
                        <p id="email-hint-forgot" class="mt-1.5 text-[12.5px] text-slate-500">Use the email tied to your guest or staff account on this portal.</p>
                        @error('email')
                            <p id="email-error-forgot" class="auth-error mt-2" role="alert">
                                <i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <button type="submit" class="auth-submit" data-auth-submit>
                        <span>Email reset link</span>
                        <i class="fas fa-paper-plane text-sm opacity-90" aria-hidden="true"></i>
                    </button>
                </form>

                <footer class="mt-10 border-t border-slate-200 pt-6 text-[13.5px] leading-relaxed text-slate-600">
                    <p>
                        Remember your password?
                        <a href="/login" class="auth-link">Back to sign in</a>
                    </p>
                    <p class="mt-3">
                        <a href="/login?portal=client" class="auth-link">Client sign-in</a>
                        ·
                        <a href="/login?portal=owner" class="auth-link">Unit owner</a>
                        ·
                        <a href="/login?portal=admin" class="auth-link">Tenant admin</a>
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
        })();
    </script>
</body>
</html>
