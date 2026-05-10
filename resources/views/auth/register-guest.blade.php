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
        /* Rotating backgrounds: partials/auth-public-carousel-bg (+ 60% white + blurred photos) */
        .auth-shell {
            box-shadow: 0 20px 50px -12px rgba(27, 94, 32, 0.12);
        }
        #register-guest-main {
            padding-top: calc(env(safe-area-inset-top, 0px) + 7rem);
        }
        @media (max-width: 767px) {
            #register-guest-main {
                padding-top: calc(env(safe-area-inset-top, 0px) + 8rem);
            }
        }
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
            border-radius: 0 0.75rem 0.75rem 0;
            transition: background-color 0.15s ease;
        }
        .reg-auth-password-toggle:hover {
            background-color: rgba(200, 230, 201, 0.5);
        }
        .reg-auth-password-toggle:focus-visible {
            outline: 2px solid rgb(46 125 50);
            outline-offset: 2px;
        }
    </style>
</head>
@php($municipality = config('portals.municipality_name', 'Impasug-ong'))
@php($fieldClass = 'auth-field block w-full rounded-xl border-2 border-brand-soft/80 bg-white/95 px-4 py-2.5 text-[0.9375rem] text-brand-dark placeholder:text-slate-400 transition focus:border-brand-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 focus-visible:ring-offset-white')
<body class="auth-public-carousel-page flex min-h-[100dvh] flex-col overflow-x-hidden text-base text-brand-dark antialiased">
    @include('partials.auth-public-carousel-bg')
    <a href="#register-guest-main" class="reg-skip">Skip to registration form</a>

    @include('partials.portal-public-nav', [
        'active' => '',
        'municipalityName' => $municipality,
        'registerHighlight' => 'guest',
    ])

    <main id="register-guest-main" class="relative z-10 flex flex-1 flex-col px-4 pb-10 pt-0 sm:px-6 sm:pb-12 md:px-8 md:pb-14" tabindex="-1">
        <div class="auth-shell mx-auto mt-3 w-full max-w-xl rounded-2xl border border-emerald-200/60 bg-white/90 p-6 ring-1 ring-emerald-900/[0.04] sm:mt-4 sm:p-7 md:p-8">
            <header class="mb-6 border-b border-emerald-100 pb-5 sm:mb-7">
                <p class="text-[0.7rem] font-bold uppercase tracking-[0.22em] text-brand-dark sm:text-xs sm:tracking-[0.26em]">Guest · traveller</p>
                <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-brand-dark sm:text-[1.65rem]">Guest registration</h1>
                <p class="mt-2 text-sm leading-relaxed text-brand-medium sm:text-[0.9375rem]">
                    Verified directory across <span class="font-semibold text-brand-dark">{{ $municipality }}</span> — discovery, saved stays, and bookings.
                </p>
                <p class="mt-3 text-xs leading-relaxed text-brand-medium sm:text-sm">
                    Hosting a property?
                    <a href="{{ route('register.owner') }}" class="font-bold text-brand-primary underline decoration-brand-soft decoration-2 underline-offset-2 hover:text-brand-dark">Host registration</a>
                </p>
            </header>

            @if ($errors->any())
                <div class="mb-5 flex gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert" aria-live="assertive">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-700" aria-hidden="true"><i class="fas fa-circle-exclamation"></i></span>
                    <div>
                        <p class="font-bold">Please fix the items below</p>
                        <ul class="mt-1.5 list-disc space-y-1 pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 flex gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950" role="status">
                    <i class="fas fa-triangle-exclamation mt-0.5 text-amber-600" aria-hidden="true"></i>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ url('/register/guest') }}" class="space-y-6" autocomplete="on" data-registration-form aria-describedby="reg-guest-intro">
                @csrf
                <p id="reg-guest-intro" class="sr-only">Guest account: name, email, optional phone, password.</p>

                <div class="flex items-start gap-3 rounded-xl border border-emerald-100 bg-emerald-50/35 p-4 sm:items-center sm:p-5">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-brand-primary" aria-hidden="true">
                        <i class="fas fa-id-card"></i>
                    </span>
                    <div class="min-w-0">
                        <span class="block text-base font-extrabold text-brand-dark">Account profile</span>
                        <span class="mt-0.5 block text-xs leading-snug text-brand-medium sm:text-sm">We use this to identify your account and reach you about reservations.</span>
                    </div>
                </div>

                <section class="rounded-xl border border-emerald-100/80 bg-white/80 p-4 shadow-sm sm:p-5" aria-labelledby="reg-section-contact-heading">
                    <h2 id="reg-section-contact-heading" class="mb-4 flex items-center gap-2 text-sm font-extrabold text-brand-dark">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-brand-primary" aria-hidden="true"><i class="fas fa-address-book text-xs"></i></span>
                        Your details
                    </h2>
                    <div class="grid gap-5">
                        <div class="space-y-1.5">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-brand-dark">Full name <span class="text-red-600" aria-hidden="true">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" aria-required="true" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                class="{{ $fieldClass }} min-h-[2.75rem] sm:min-h-11"
                                placeholder="As shown on your ID">
                            @error('name')
                                <p class="mt-1 flex items-start gap-1.5 text-xs font-semibold text-red-700" role="alert"><i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2 sm:items-start">
                            <div class="space-y-1.5">
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-brand-dark">Email <span class="text-red-600" aria-hidden="true">*</span></label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" inputmode="email" aria-required="true" aria-describedby="hint-email-guest" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                    class="{{ $fieldClass }} min-h-[2.75rem] sm:min-h-11"
                                    placeholder="you@example.com">
                                <p id="hint-email-guest" class="text-xs text-brand-medium/95">Used for sign-in and booking updates.</p>
                                @error('email')
                                    <p class="mt-1 flex items-start gap-1.5 text-xs font-semibold text-red-700" role="alert"><i class="fas fa-circle-xmark mt-0.5 shrink-0" aria-hidden="true"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label for="phone" class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-dark">
                                    Mobile number
                                    <span class="rounded-md bg-emerald-100 px-1.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-brand-dark">Optional</span>
                                </label>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel" aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                                    class="{{ $fieldClass }} min-h-[2.75rem] sm:min-h-11"
                                    placeholder="e.g. +63 917 123 4567">
                                @error('phone')
                                    <p class="mt-1 text-xs font-semibold text-red-700" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl border border-emerald-100/80 bg-white/80 p-4 shadow-sm sm:p-5" aria-labelledby="reg-section-password-heading">
                    <h2 id="reg-section-password-heading" class="mb-3 flex items-center gap-2 text-sm font-extrabold text-brand-dark">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-brand-primary" aria-hidden="true"><i class="fas fa-lock text-xs"></i></span>
                        Sign-in password
                    </h2>
                    <p class="mb-4 text-xs leading-snug text-brand-medium">Choose a password only you know. You will use it with your email to log in.</p>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="space-y-1.5">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-brand-dark">Password <span class="text-red-600" aria-hidden="true">*</span></label>
                            <div class="reg-auth-password-wrap mt-1">
                                <input id="password" type="password" name="password" required autocomplete="new-password" aria-required="true" aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                    class="{{ $fieldClass }} min-h-[2.75rem] py-2.5 pl-4 pr-12 sm:min-h-11"
                                    placeholder="Create a strong password">
                                <button type="button" class="reg-auth-password-toggle" id="toggle-password" aria-label="Show password" aria-pressed="false" tabindex="0" data-password-toggle="password">
                                    <i class="fas fa-eye text-base" aria-hidden="true"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs font-semibold text-red-700" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-brand-dark">Confirm password <span class="text-red-600" aria-hidden="true">*</span></label>
                            <div class="reg-auth-password-wrap mt-1">
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" aria-required="true" aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                                    class="{{ $fieldClass }} min-h-[2.75rem] py-2.5 pl-4 pr-12 sm:min-h-11"
                                    placeholder="Same password again">
                                <button type="button" class="reg-auth-password-toggle" id="toggle-password-confirmation" aria-label="Show confirm password" aria-pressed="false" tabindex="0" data-password-toggle="password_confirmation">
                                    <i class="fas fa-eye text-base" aria-hidden="true"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-1 text-xs font-semibold text-red-700" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                <div class="space-y-4 border-t border-emerald-100 pt-6">
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-dark to-brand-primary py-3 text-sm font-bold text-white shadow-[0_10px_28px_rgba(46,125,50,0.28)] transition hover:brightness-[1.04] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-dark disabled:cursor-not-allowed disabled:opacity-60 sm:py-3.5" data-reg-submit>
                        <span>Create guest profile</span>
                        <i class="fas fa-arrow-right text-xs opacity-90" aria-hidden="true"></i>
                    </button>
                    <p class="text-center text-xs leading-relaxed text-brand-medium sm:text-[0.8125rem]">By submitting you confirm your details are accurate and accept the municipality’s participation rules.</p>
                </div>
            </form>

            <div class="mt-6 border-t border-emerald-100 pt-6 text-center text-xs text-brand-medium sm:text-[0.8125rem]">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-1.5 font-bold text-brand-primary hover:text-brand-dark">
                    <i class="fas fa-chevron-left text-[0.65rem]" aria-hidden="true"></i>
                    Already have an account? Sign in
                </a>
            </div>
        </div>
    </main>

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
