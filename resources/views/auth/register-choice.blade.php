<!DOCTYPE html>
<html lang="en" class="min-h-full">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'Choose registration type | IMPASUGONG TOURISM'])
    @include('partials.auth-registration-head-extra')
    <meta name="description" content="Choose whether to register as a guest traveler or host accommodation provider.">
    <meta name="color-scheme" content="light">
</head>
@php($municipality = config('portals.municipality_name', 'Impasug-ong'))
<body class="auth-public-carousel-page flex min-h-[100dvh] flex-col overflow-x-hidden text-base text-brand-dark antialiased">
    @include('partials.auth-public-carousel-bg')
    <a href="#register-choice-main" class="reg-skip">Skip to registration choices</a>

    @include('partials.portal-public-nav', [
        'active' => '',
        'municipalityName' => $municipality,
    ])

    <main id="register-choice-main" class="relative z-10 flex flex-1 items-center justify-center px-4 pb-10 pt-32 sm:px-6 md:px-8 md:pt-36" tabindex="-1">
        <section class="auth-shell mx-auto w-full max-w-3xl rounded-[1.75rem] border border-emerald-200/70 bg-white/[0.92] p-6 text-center shadow-[0_22px_60px_-20px_rgba(27,94,32,0.28)] ring-1 ring-emerald-900/[0.04] backdrop-blur-md sm:p-8 md:p-10" aria-labelledby="register-choice-heading">
            <p class="text-xs font-bold uppercase tracking-[0.26em] text-brand-primary">Create your account</p>
            <h1 id="register-choice-heading" class="mt-3 text-3xl font-extrabold tracking-tight text-brand-dark sm:text-4xl">How would you like to register?</h1>
            <p class="mx-auto mt-4 max-w-2xl text-sm font-medium leading-relaxed text-slate-700 sm:text-base">
                Choose the path that matches your role in {{ $municipality }}. You can sign in with the same login page after your account is created.
            </p>

            <div class="mt-8 grid gap-4 text-left sm:grid-cols-2">
                <a href="{{ route('register.guest') }}" class="group rounded-2xl border-2 border-emerald-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-primary hover:shadow-[0_18px_38px_-24px_rgba(27,94,32,0.42)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-xl text-brand-primary transition group-hover:bg-brand-primary group-hover:text-white" aria-hidden="true">
                        <i class="fas fa-user"></i>
                    </span>
                    <span class="mt-4 block text-xl font-extrabold text-brand-dark">Guest</span>
                    <span class="mt-2 block text-sm font-medium leading-relaxed text-slate-700">Browse trusted accommodations, save stays, and manage reservations.</span>
                </a>

                <a href="{{ route('register.owner') }}" class="group rounded-2xl border-2 border-brand-primary bg-gradient-to-br from-brand-dark to-brand-primary p-5 text-white shadow-[0_18px_38px_-20px_rgba(27,94,32,0.55)] transition hover:-translate-y-0.5 hover:brightness-105 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/95 text-xl text-brand-primary" aria-hidden="true">
                        <i class="fas fa-home"></i>
                    </span>
                    <span class="mt-4 block text-xl font-extrabold">Host</span>
                    <span class="mt-2 block text-sm font-medium leading-relaxed text-emerald-50">Submit your accommodation profile and compliance documents for review.</span>
                </a>
            </div>

            <div class="mt-8 border-t border-emerald-100 pt-6 text-sm font-medium text-slate-700">
                Already registered?
                <a href="{{ route('login') }}" class="font-extrabold text-brand-primary underline decoration-brand-soft decoration-2 underline-offset-2 hover:text-brand-dark">Sign in</a>
            </div>
        </section>
    </main>
</body>
</html>
