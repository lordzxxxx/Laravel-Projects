<!DOCTYPE html>
<html lang="en" class="min-h-full">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'Create guest profile | IMPASUGONG TOURISM'])
    @include('partials.auth-registration-head-extra')
    <meta name="description" content="Register for municipality-verified lodging discovery and booking in {{ config('portals.municipality_name', 'Impasug-ong') }}.">
    <script>
        (function () { try { document.documentElement.classList.remove('dark'); } catch (e) {} })();
    </script>
    <meta name="color-scheme" content="light">
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

        .reg-skip { position:absolute; left:.75rem; top:-100px; clip:rect(0 0 0 0); height:1px; width:1px; overflow:hidden; white-space:nowrap; border:0; border-radius:.5rem; background:var(--auth-accent); color:#fff; box-shadow:0 10px 15px rgba(0,0,0,.2); padding:.75rem 1rem; }
        .reg-skip:focus { clip:auto; height:auto; width:auto; overflow:visible; outline:none; top:.75rem; left:.75rem; z-index:100; margin:0; }

        .auth-shell { display:flex; flex-direction:column; min-height:100dvh; }
        .auth-hero { position:relative; overflow:hidden; min-height: min(34vh, 18rem); flex-shrink:0; }
        .auth-hero__photo { position:absolute; inset:0; background-image:url('{{ asset('COMMUNAL.jpg') }}'); background-size:cover; background-position:center; transform:scale(1.04); filter:blur(2px) saturate(.95); }
        .auth-hero__scrim { position:absolute; inset:0; background:linear-gradient(180deg, rgba(255,255,255,.55) 0%, rgba(255,255,255,.78) 65%, rgba(247,249,247,1) 100%); }
        .auth-hero__content { position:relative; z-index:1; width:100%; max-width: 36rem; margin:0 auto; padding:2.5rem 1.5rem 2rem; }

        .auth-form-wrap { flex:1 1 auto; display:flex; align-items:flex-start; justify-content:center; padding: 2rem 1.5rem 3rem; }
        .auth-card { width:100%; max-width: 30rem; }

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

        .reg-section + .reg-section { margin-top: 1.75rem; border-top: 1px solid #e5e7eb; padding-top: 1.75rem; }
        .reg-section-label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: #334155; }
        .reg-field-label { font-size: 0.74rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #334155; }
        .reg-helper { font-size: 0.8rem; line-height: 1.5; color: #475569; }

        .reg-auth-password-wrap { position: relative; }
        .reg-auth-password-toggle {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            display: flex;
            width: 2.75rem;
            align-items: center;
            justify-content: center;
            color: rgb(27 94 32);
            border-radius: 0 0.625rem 0.625rem 0;
            transition: background-color 0.15s ease;
        }
        .reg-auth-password-toggle:hover { background-color: rgba(200, 230, 201, 0.5); }
        .reg-auth-password-toggle:focus-visible { outline: 2px solid rgb(46 125 50); outline-offset: 2px; }

        .auth-submit { display:inline-flex; width:100%; align-items:center; justify-content:center; gap:.5rem; background:var(--auth-ink); color:#fff; font-weight:600; font-size:.95rem; padding:.75rem 1rem; border-radius:.625rem; transition: background .15s ease, transform .05s ease; }
        .auth-submit:hover { background:#000; }
        .auth-submit:active { transform: translateY(1px); }
        .auth-submit:disabled { opacity:.65; cursor:not-allowed; }

        .auth-link { color:var(--auth-accent); font-weight:600; text-decoration:underline; text-decoration-thickness:1px; text-underline-offset:.2em; }
        .auth-link:hover { color:var(--auth-accent-strong); }

        @media (min-width: 1024px) {
            .auth-shell { flex-direction: row; }
            .auth-hero { flex: 0 0 55%; max-width:55%; min-height: 100dvh; position: sticky; top: 0; align-self: flex-start; height: 100dvh; }
            .auth-hero__content { padding: 4rem 3.5rem; max-width: 38rem; margin-top: auto; margin-bottom: auto; }
            .auth-form-wrap { flex: 0 0 45%; max-width: 45%; padding: 3rem 3rem; align-items: flex-start; }
        }

        @media (prefers-reduced-motion: reduce) {
            .auth-hero__photo { filter: none; transform:none; }
        }
    </style>
</head>
@php($municipality = config('portals.municipality_name', 'Impasug-ong'))
@php($fieldClass = 'auth-input')
<body>
    <a href="#register-guest-main" class="reg-skip">Skip to registration form</a>

    <div class="auth-shell">
        <aside class="auth-hero" aria-label="Impasugong Tourism guest registration">
            <div class="auth-hero__photo" aria-hidden="true"></div>
            <div class="auth-hero__scrim" aria-hidden="true"></div>
            <div class="auth-hero__content">
                <div class="flex flex-wrap items-center gap-x-2 gap-y-3 sm:gap-x-3">
                    <img src="{{ asset('images/love-impasugong-watermark.png') }}" alt="" class="h-24 w-auto object-contain sm:h-28 lg:h-36" decoding="async" role="presentation">
                    <img src="{{ asset('SYSTEMLOGO.png') }}" alt="" class="h-24 w-auto object-contain sm:h-28 lg:h-36" decoding="async" role="presentation">
                    <img src="{{ asset('Lgu Socmed Template-02 2.png') }}" alt="" class="h-20 w-auto object-contain sm:h-24 lg:h-32" decoding="async" role="presentation">
                </div>

                <div class="mt-3 sm:mt-4">
                    <span class="auth-eyebrow">Guest registration</span>
                    <h1 class="auth-display mt-4 text-[2rem] font-semibold leading-[1.1] text-slate-900 sm:text-[2.4rem] lg:text-[2.75rem]">
                        Create your profile.
                    </h1>
                    <p class="mt-4 max-w-md text-[15px] leading-relaxed text-slate-700">
                        Discover verified stays across <span class="font-semibold text-slate-900">{{ $municipality }}</span>. Save favourites and manage bookings in one place.
                    </p>
                </div>

                <dl class="mt-10 hidden grid-cols-1 gap-5 sm:grid-cols-2 lg:mt-14 lg:grid">
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Verified</dt>
                        <dd class="mt-1.5 text-[13.5px] leading-snug text-slate-700">Only municipality-approved hosts and listings.</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Centralised</dt>
                        <dd class="mt-1.5 text-[13.5px] leading-snug text-slate-700">Favourites, bookings, and messages in one place.</dd>
                    </div>
                </dl>
            </div>
        </aside>

        <div class="auth-form-wrap">
            <main id="register-guest-main" tabindex="-1" class="auth-card">
                <header class="mb-8">
                    <h2 class="auth-display text-[1.55rem] font-semibold tracking-tight text-slate-900">Guest registration</h2>
                    <p class="mt-2 text-[14.5px] leading-relaxed text-slate-600">
                        Name, email, and a password — that's all you need to start.
                    </p>
                </header>

                @if ($errors->any())
                    <div class="mb-6 flex gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert" aria-live="assertive">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-red-100 text-red-700" aria-hidden="true"><i class="fas fa-circle-exclamation"></i></span>
                        <div>
                            <p class="font-semibold">Please fix the items below</p>
                            <ul class="mt-1.5 list-disc space-y-1 pl-4 text-[13px]">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 flex gap-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950" role="status">
                        <i class="fas fa-triangle-exclamation mt-0.5 text-amber-600" aria-hidden="true"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ url('/register/guest') }}" autocomplete="on" data-registration-form aria-describedby="reg-guest-intro">
                    @csrf
                    <p id="reg-guest-intro" class="sr-only">Guest account: name, email, optional phone, password.</p>

                    <section class="reg-section" aria-labelledby="reg-section-contact-heading">
                        <h3 id="reg-section-contact-heading" class="reg-section-label mb-5">Your details</h3>
                        <div class="grid gap-5">
                            <div class="space-y-1.5">
                                <label for="name" class="reg-field-label block">Full name <span class="text-red-600" aria-hidden="true">*</span></label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" aria-required="true" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                    class="{{ $fieldClass }}"
                                    placeholder="As shown on your ID">
                                @error('name')
                                    <p class="mt-1.5 flex items-start gap-1.5 text-xs font-semibold text-red-700" role="alert"><i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2 sm:items-start">
                                <div class="space-y-1.5">
                                    <label for="email" class="reg-field-label block">Email <span class="text-red-600" aria-hidden="true">*</span></label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" inputmode="email" aria-required="true" aria-describedby="hint-email-guest" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                        class="{{ $fieldClass }}"
                                        placeholder="you@example.com">
                                    <p id="hint-email-guest" class="reg-helper">Used for sign-in and booking updates.</p>
                                    @error('email')
                                        <p class="mt-1.5 flex items-start gap-1.5 text-xs font-semibold text-red-700" role="alert"><i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label for="phone" class="reg-field-label flex flex-wrap items-center gap-2">
                                        Mobile number
                                        <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[0.62rem] font-semibold uppercase tracking-wide text-slate-600">Optional</span>
                                    </label>
                                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel" aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                                        class="{{ $fieldClass }}"
                                        placeholder="e.g. +63 917 123 4567">
                                    @error('phone')
                                        <p class="mt-1.5 text-xs font-semibold text-red-700" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="reg-section" aria-labelledby="reg-section-password-heading">
                        <h3 id="reg-section-password-heading" class="reg-section-label">Sign-in password</h3>
                        <p class="mb-5 mt-1.5 reg-helper">Use your email and this password to sign in.</p>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="space-y-1.5">
                                <label for="password" class="reg-field-label block">Password <span class="text-red-600" aria-hidden="true">*</span></label>
                                <div class="reg-auth-password-wrap mt-1">
                                    <input id="password" type="password" name="password" required autocomplete="new-password" aria-required="true" aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                        class="{{ $fieldClass }} pr-12"
                                        placeholder="Create a strong password">
                                    <button type="button" class="reg-auth-password-toggle" id="toggle-password" aria-label="Show password" aria-pressed="false" tabindex="0" data-password-toggle="password">
                                        <i class="fas fa-eye text-base" aria-hidden="true"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1.5 text-xs font-semibold text-red-700" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label for="password_confirmation" class="reg-field-label block">Confirm password <span class="text-red-600" aria-hidden="true">*</span></label>
                                <div class="reg-auth-password-wrap mt-1">
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" aria-required="true" aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                                        class="{{ $fieldClass }} pr-12"
                                        placeholder="Same password again">
                                    <button type="button" class="reg-auth-password-toggle" id="toggle-password-confirmation" aria-label="Show confirm password" aria-pressed="false" tabindex="0" data-password-toggle="password_confirmation">
                                        <i class="fas fa-eye text-base" aria-hidden="true"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="mt-1.5 text-xs font-semibold text-red-700" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <div class="mt-8 space-y-3">
                        <button type="submit" class="auth-submit" data-reg-submit>
                            <span>Create profile</span>
                            <i class="fas fa-arrow-right text-xs opacity-90" aria-hidden="true"></i>
                        </button>
                        <p class="text-center text-[12.5px] leading-relaxed text-slate-600">By submitting you confirm your details are accurate and accept the municipality’s participation rules.</p>
                    </div>
                </form>

                <footer class="mt-10 flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 pt-6 text-[13.5px] text-slate-700">
                    <a href="{{ route('login') }}" class="auth-link inline-flex items-center gap-1.5">
                        <i class="fas fa-chevron-left text-[0.65rem]" aria-hidden="true"></i>
                        Already have an account? Sign in
                    </a>
                    <a href="{{ route('register.owner') }}" class="font-semibold text-slate-700 underline decoration-slate-300 underline-offset-[3px] hover:text-slate-900 hover:decoration-slate-500">
                        Register as a host instead
                    </a>
                </footer>
            </main>
        </div>
    </div>

    @include('partials.auth-registration-scripts', ['fileInputs' => false])
    <script>
        (function () {
            document.querySelectorAll('[data-password-toggle]').forEach(function (btn) {
                var fieldId = btn.getAttribute('data-password-toggle');
                var input = fieldId ? document.getElementById(fieldId) : null;
                var icon = btn.querySelector('i');
                if (!input || !icon) return;
                var isConfirm = fieldId === 'password_confirmation';
                function setLabel(visible) {
                    if (isConfirm) {
                        btn.setAttribute('aria-label', visible ? 'Hide confirm password' : 'Show confirm password');
                    } else {
                        btn.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');
                    }
                }
                function sync() {
                    var visible = input.type === 'text';
                    btn.setAttribute('aria-pressed', visible ? 'true' : 'false');
                    setLabel(visible);
                    icon.className = visible ? 'fas fa-eye-slash text-base' : 'fas fa-eye text-base';
                }
                btn.addEventListener('click', function () {
                    input.type = input.type === 'password' ? 'text' : 'password';
                    sync();
                });
                sync();
            });
        })();
    </script>
</body>
</html>
