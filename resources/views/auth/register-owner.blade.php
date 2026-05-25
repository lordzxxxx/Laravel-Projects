<!DOCTYPE html>
<html lang="en" class="min-h-full">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'Host registration | IMPASUGONG TOURISM'])
    @include('partials.auth-registration-head-extra')
    <meta name="description" content="Submit your lodging operator profile and compliance verification for {{ config('portals.municipality_name', 'Impasug-ong') }}.">
    <script>
        (function () { try { document.documentElement.classList.remove('dark'); } catch (e) {} })();
    </script>
    <meta name="color-scheme" content="light">
    <style>
        /* Rotating backgrounds: partials/auth-public-carousel-bg */
        .auth-shell {
            box-shadow: 0 20px 50px -12px rgba(27, 94, 32, 0.12);
        }
        #register-owner-main {
            padding-top: calc(env(safe-area-inset-top, 0px) + 7rem);
        }
        @media (max-width: 767px) {
            #register-owner-main {
                padding-top: calc(env(safe-area-inset-top, 0px) + 8rem);
            }
        }
        .reg-owner-dropzone {
            position: relative;
            isolation: isolate;
        }
        .reg-owner-dropzone-surface {
            border: 2px dashed rgba(27, 94, 32, 0.28);
            background: rgba(236, 253, 245, 0.65);
            transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
        }
        .reg-owner-dropzone:hover .reg-owner-dropzone-surface {
            border-color: rgba(27, 94, 32, 0.45);
            background: rgba(236, 253, 245, 0.9);
        }
        .reg-owner-dropzone--active .reg-owner-dropzone-surface {
            border-color: rgb(46 125 50);
            background: rgb(240 253 244);
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.2);
        }
        .reg-owner-file-input:focus-visible ~ .reg-owner-dropzone-surface {
            outline: 2px solid rgb(46 125 50);
            outline-offset: 2px;
        }
    </style>
</head>
@php($municipality = config('portals.municipality_name', 'Impasug-ong'))
@php($fieldClass = 'auth-field block w-full rounded-xl border-2 border-brand-soft/80 bg-white/95 px-4 py-2.5 text-[0.9375rem] text-brand-dark placeholder:text-slate-400 transition focus:border-brand-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 focus-visible:ring-offset-white')
<body class="auth-public-carousel-page flex min-h-[100dvh] flex-col overflow-x-hidden text-base text-brand-dark antialiased">
    @include('partials.auth-public-carousel-bg')
    <a href="#register-owner-main" class="reg-skip">Skip to registration form</a>

    @include('partials.portal-public-nav', [
        'active' => '',
        'municipalityName' => $municipality,
        'registerHighlight' => 'host',
    ])

    <main id="register-owner-main" class="relative z-10 flex flex-1 flex-col px-4 pb-10 pt-0 sm:px-6 sm:pb-12 md:px-8 md:pb-14" tabindex="-1">
        <div class="auth-shell mx-auto mt-3 w-full max-w-xl rounded-2xl border border-emerald-200/60 bg-white/90 p-6 ring-1 ring-emerald-900/[0.04] sm:mt-4 sm:p-7 md:max-w-2xl md:p-8">
            <header class="mb-6 border-b border-emerald-100 pb-5 sm:mb-7">
                <p class="text-[0.7rem] font-bold uppercase tracking-[0.22em] text-brand-dark sm:text-xs sm:tracking-[0.26em]">Host · lodging operator</p>
                <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-brand-dark sm:text-[1.65rem]">Host registration</h1>
                <p class="mt-2 text-sm leading-relaxed text-brand-medium sm:text-[0.9375rem]">
                    Opens an operator record for <span class="font-semibold text-brand-dark">{{ $municipality }}</span>. Listings stay hidden until staff complete compliance review.
                </p>
                <p class="mt-3 text-xs leading-relaxed text-brand-medium sm:text-sm">
                    <span class="font-semibold text-brand-dark">Profile first, then upload verification documents.</span>
                    Travellers without compliance files should use
                    <a href="{{ route('register.guest') }}" class="font-bold text-brand-primary underline decoration-brand-soft decoration-2 underline-offset-2 hover:text-brand-dark">guest registration</a>.
                    Commercial fees follow official correspondence after approval.
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

            <form method="POST" action="{{ url('/register/owner') }}" enctype="multipart/form-data" class="space-y-6" autocomplete="on" data-registration-form aria-describedby="reg-owner-intro">
                @csrf
                <p id="reg-owner-intro" class="sr-only">Host registration: profile, then four compliance files.</p>

                <ol class="flex flex-wrap items-stretch gap-3 sm:gap-4" aria-label="Registration steps">
                    <li class="flex min-h-[2.75rem] flex-1 items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50/60 px-4 py-2.5 sm:flex-initial">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-primary text-sm font-extrabold text-white" aria-hidden="true">1</span>
                        <span class="text-sm font-bold text-brand-dark">Profile</span>
                    </li>
                    <li class="flex min-h-[2.75rem] flex-1 items-center gap-2 rounded-xl border border-emerald-100 bg-white px-4 py-2.5 sm:flex-initial">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border-2 border-brand-soft text-sm font-extrabold text-brand-dark" aria-hidden="true">2</span>
                        <span class="text-sm font-semibold text-brand-medium">Compliance</span>
                    </li>
                </ol>

                <div class="flex items-start gap-3 rounded-xl border border-emerald-100 bg-emerald-50/35 p-4 sm:items-center sm:p-5">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-brand-primary" aria-hidden="true">
                        <i class="fas fa-id-card"></i>
                    </span>
                    <div class="min-w-0">
                        <span class="block text-base font-extrabold text-brand-dark">Account profile</span>
                        <span class="mt-0.5 block text-xs leading-snug text-brand-medium sm:text-sm">Representative and routing information for review.</span>
                    </div>
                </div>

                <section class="rounded-xl border border-emerald-100/80 bg-white/80 p-4 shadow-sm sm:p-5" aria-labelledby="reg-owner-contact-heading">
                    <h2 id="reg-owner-contact-heading" class="mb-4 flex items-center gap-2 text-sm font-extrabold text-brand-dark">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-brand-primary" aria-hidden="true"><i class="fas fa-address-book text-xs"></i></span>
                        Contact &amp; organisation
                    </h2>
                    <div class="flex flex-col gap-5">
                        <div class="space-y-1.5">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-brand-dark">Full legal name <span class="text-red-600" aria-hidden="true">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" aria-required="true" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                class="{{ $fieldClass }} min-h-[2.75rem] sm:min-h-11"
                                placeholder="Authorised representative">
                            @error('name')
                                <p class="mt-1 text-xs font-semibold text-red-700" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-brand-dark">Business email <span class="text-red-600" aria-hidden="true">*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" inputmode="email" aria-describedby="hint-email-owner" aria-required="true" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                class="{{ $fieldClass }} min-h-[2.75rem] sm:min-h-11"
                                placeholder="operations@establishment.tld">
                            <p id="hint-email-owner" class="text-xs text-brand-medium/95">Reviews and official notices.</p>
                            @error('email')
                                <p class="mt-1 text-xs font-semibold text-red-700" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2 sm:items-start">
                            <div class="space-y-1.5">
                                <label for="phone" class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-dark">
                                    Phone
                                    <span class="rounded-md bg-emerald-100 px-1.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-brand-dark">Optional</span>
                                </label>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel" aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                                    class="{{ $fieldClass }} min-h-[2.75rem] sm:min-h-11">
                                @error('phone')
                                    <p class="mt-1 text-xs font-semibold text-red-700" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label for="app_title" class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-dark">
                                    Trade name
                                    <span class="rounded-md bg-emerald-100 px-1.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-brand-dark">Optional</span>
                                </label>
                                <input id="app_title" type="text" name="app_title" value="{{ old('app_title') }}" autocomplete="organization" aria-invalid="{{ $errors->has('app_title') ? 'true' : 'false' }}"
                                    class="{{ $fieldClass }} min-h-[2.75rem] sm:min-h-11"
                                    placeholder="Public listing name">
                                @error('app_title')
                                    <p class="mt-1 text-xs font-semibold text-red-700" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </section>

                @include('partials.auth-registration-password-fields')

                <section class="rounded-xl border border-emerald-100/80 bg-white/80 p-4 shadow-sm sm:p-5" aria-labelledby="reg-owner-compliance-heading">
                    <h2 id="reg-owner-compliance-heading" class="mb-3 flex items-center gap-2 text-sm font-extrabold text-brand-dark">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-brand-primary" aria-hidden="true"><i class="fas fa-clipboard-check text-xs"></i></span>
                        Compliance verification
                    </h2>
                    <p id="documents-help" class="mb-4 text-xs leading-relaxed text-brand-medium sm:text-sm">
                        PDF or image (JPEG, PNG), <span class="font-semibold text-brand-dark">up to 10 MB each</span>. Drag and drop or click each zone. Clear scans avoid delays.
                    </p>

                    <div class="grid gap-3 sm:grid-cols-2 sm:gap-4">
                        @foreach ([
                            'business_permit' => ['label' => 'Business permit', 'help' => 'Current business registration.'],
                            'mayors_permit' => ['label' => "Mayor's permit", 'help' => 'Valid executive authorization.'],
                            'barangay_clearance' => ['label' => 'Barangay clearance', 'help' => 'Local clearance on file.'],
                            'valid_id' => ['label' => 'Government photo ID', 'help' => 'Operator or delegated signatory.'],
                        ] as $field => $meta)
                            <div class="flex flex-col rounded-xl border border-emerald-100 bg-emerald-50/40 p-3 sm:p-4">
                                <p id="lbl-{{ $field }}" class="text-xs font-bold text-brand-dark sm:text-sm">{{ $meta['label'] }}</p>
                                <p class="mt-0.5 text-[0.7rem] leading-snug text-brand-medium sm:text-xs">{{ $meta['help'] }}</p>
                                <div
                                    class="reg-owner-dropzone mt-2"
                                    data-reg-owner-dropzone
                                    role="group"
                                    aria-labelledby="lbl-{{ $field }}"
                                    aria-describedby="documents-help fn-{{ $field }}"
                                >
                                    <input
                                        id="{{ $field }}"
                                        type="file"
                                        name="{{ $field }}"
                                        required
                                        accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                                        aria-labelledby="lbl-{{ $field }}"
                                        aria-describedby="fn-{{ $field }} documents-help"
                                        data-registration-file
                                        data-filename-span="fn-{{ $field }}"
                                        data-reg-owner-max-bytes="10485760"
                                        class="reg-owner-file-input absolute inset-0 z-[2] h-full min-h-[4.25rem] w-full cursor-pointer opacity-0"
                                    >
                                    <div class="reg-owner-dropzone-surface relative z-[1] flex min-h-[4.25rem] flex-col items-center justify-center gap-0.5 px-2 py-2.5 text-center pointer-events-none">
                                        <i class="fas fa-cloud-arrow-up text-base text-brand-primary" aria-hidden="true"></i>
                                        <span class="text-[0.7rem] font-semibold text-brand-dark sm:text-xs">Drop or browse</span>
                                        <span class="text-[0.6rem] leading-tight text-brand-medium sm:text-[0.65rem]">PDF, JPEG, PNG — 10 MB max</span>
                                    </div>
                                </div>
                                <p id="fn-{{ $field }}" class="mt-2 flex items-start gap-1.5 text-[0.7rem] text-brand-medium sm:text-xs" aria-live="polite">
                                    <i class="fas fa-paperclip mt-0.5 shrink-0 text-brand-primary" aria-hidden="true"></i>
                                    <span>No file selected.</span>
                                </p>
                                @error($field)
                                    <p class="mt-2 text-xs font-semibold text-red-700" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </section>

                <div class="space-y-4 border-t border-emerald-100 pt-6">
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-dark to-brand-primary py-3 text-sm font-bold text-white shadow-[0_10px_28px_rgba(46,125,50,0.28)] transition hover:brightness-[1.04] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-dark disabled:cursor-not-allowed disabled:opacity-60 sm:py-3.5" data-reg-submit>
                        <span>Submit registration for review</span>
                        <i class="fas fa-paper-plane text-xs opacity-90" aria-hidden="true"></i>
                    </button>
                    <p class="text-center text-xs leading-relaxed text-brand-medium sm:text-[0.8125rem]">You will route to onboarding status once the upload succeeds. By submitting you confirm your details are accurate and accept the municipality’s participation rules.</p>
                </div>
            </form>

            <div class="mt-6 border-t border-emerald-100 pt-6 text-center text-xs text-brand-medium sm:text-[0.8125rem]">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-1.5 font-bold text-brand-primary hover:text-brand-dark">
                    <i class="fas fa-chevron-left text-[0.65rem]" aria-hidden="true"></i>
                    Back to sign in
                </a>
            </div>
        </div>
    </main>

    @include('partials.auth-registration-scripts', ['fileInputs' => true])
    <script>
        (function () {
            var MAX_BYTES = 10485760;
            var ACCEPT_EXT = /\.(pdf|jpe?g|png)$/i;
            function allowedType(file) {
                var t = (file && file.type) || '';
                var mimeOk = /^(application\/pdf|image\/(jpeg|png))$/i.test(t);
                var name = (file && file.name) || '';
                return mimeOk || ACCEPT_EXT.test(name);
            }
            function showFileClientError(input, message) {
                try {
                    input.value = '';
                } catch (e) {}
                var sid = input.getAttribute('data-filename-span');
                var el = sid ? document.getElementById(sid) : null;
                if (!el) return;
                var span = el.querySelector('span');
                var line = span || el;
                line.textContent = message;
                line.classList.add('font-semibold', 'text-red-700');
                el.classList.remove('text-brand-dark', 'dark:text-emerald-200', 'font-semibold');
            }
            function passesFileRules(input, file) {
                var maxAttr = input.getAttribute('data-reg-owner-max-bytes');
                var maxB = maxAttr ? parseInt(maxAttr, 10) : MAX_BYTES;
                if (!file) return false;
                if (!allowedType(file)) {
                    showFileClientError(input, 'Use PDF, JPEG, or PNG only.');
                    return false;
                }
                if (file.size > maxB) {
                    showFileClientError(input, 'File must be 10 MB or smaller.');
                    return false;
                }
                return true;
            }
            function clearFileLineErrorClass(input) {
                var sid = input.getAttribute('data-filename-span');
                var el = sid ? document.getElementById(sid) : null;
                var span = el && el.querySelector('span');
                if (span) {
                    span.classList.remove('font-semibold', 'text-red-700');
                }
            }
            document.querySelectorAll('[data-reg-owner-dropzone]').forEach(function (zone) {
                var input = zone.querySelector('input[type="file"]');
                if (!input) return;
                ['dragenter', 'dragover'].forEach(function (type) {
                    zone.addEventListener(
                        type,
                        function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            zone.classList.add('reg-owner-dropzone--active');
                        },
                        false
                    );
                });
                zone.addEventListener(
                    'dragleave',
                    function (e) {
                        e.preventDefault();
                        if (e.relatedTarget !== null && zone.contains(e.relatedTarget)) return;
                        zone.classList.remove('reg-owner-dropzone--active');
                    },
                    false
                );
                zone.addEventListener(
                    'drop',
                    function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        zone.classList.remove('reg-owner-dropzone--active');
                        var files = e.dataTransfer && e.dataTransfer.files;
                        if (!files || !files.length) return;
                        var file = files[0];
                        if (!passesFileRules(input, file)) return;
                        try {
                            var dt = new DataTransfer();
                            dt.items.add(file);
                            input.files = dt.files;
                        } catch (err) {
                            showFileClientError(input, 'Could not attach this file. Try browsing instead.');
                            return;
                        }
                        clearFileLineErrorClass(input);
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    },
                    false
                );
                input.addEventListener('change', function () {
                    var f = input.files && input.files[0];
                    if (!f) return;
                    if (!passesFileRules(input, f)) return;
                    clearFileLineErrorClass(input);
                });
            });
        })();
    </script>
</body>
</html>
