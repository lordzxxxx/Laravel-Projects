<main class="mx-auto w-full flex-1 max-w-6xl px-5 pb-12 pt-24 md:px-10 md:pt-28">
    <header class="mx-auto mb-10 max-w-3xl rounded-[1.65rem] border border-white/70 bg-white/85 px-5 py-8 text-center shadow-[0_18px_50px_-28px_rgba(27,94,32,0.45)] backdrop-blur-md sm:px-8 md:mb-12 md:py-10">
        <div class="mb-8 flex flex-wrap items-center justify-center gap-3.5">
            <img src="/Love%20Impasugong.png" alt="Love Impasugong" class="h-20 w-20 object-contain md:h-[120px] md:w-[120px]">
            <img src="/SYSTEMLOGO.png" alt="IMPASUGONG TOURISM" class="h-20 w-20 object-contain md:h-[120px] md:w-[120px]">
            <img src="/Lgu%20Socmed%20Template-02.png" alt="LGU Impasugong, Bukidnon" class="h-20 w-20 object-contain md:h-[120px] md:w-[120px]">
        </div>
        <h1 class="mb-4 text-3xl font-extrabold leading-tight tracking-tight text-brand-dark md:text-5xl">
            About <span class="text-brand-primary">Impasug-ong Tourism</span>
        </h1>
        <p class="mx-auto max-w-2xl text-base font-medium leading-relaxed text-slate-700 md:text-lg">
            Impasug-ong Tourism connects travelers with trusted and accredited accommodations across Impasug-ong — supporting local tourism, empowering accommodation providers, and strengthening the community through one unified platform.
        </p>
        <a href="{{ $aboutHomeUrl }}" class="mt-6 inline-flex items-center gap-2 rounded-full border border-brand-soft bg-white/80 px-4 py-2 text-sm font-semibold text-brand-dark transition hover:border-brand-primary hover:text-brand-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
            <i class="fas fa-arrow-left"></i> Back to home
        </a>
    </header>

    <div class="about-page__content">
        <div class="about-page__leadership" aria-labelledby="about-leadership">
            <div class="about-page__leadership-head">
                <h2 id="about-leadership">
                    <i class="fas fa-landmark" aria-hidden="true"></i>
                    Leadership
                </h2>
                <p>Municipal partners guiding tourism and community growth.</p>
            </div>
            <div class="about-page__grid about-page__grid--leadership">
                <section class="about-page__section about-page__section--leader" aria-label="Mayor">
                    <div class="about-page__section-body">
                        @include('partials.about-team-member', [
                            'stem' => $mayor['stem'],
                            'name' => $mayor['name'],
                            'role' => $mayor['role'],
                            'bio' => $mayor['bio'],
                            'imageUrl' => $resolveAboutImage($mayor['stem']),
                            'cardClass' => 'about-team-card',
                        ])
                    </div>
                </section>
                <section class="about-page__section about-page__section--leader" aria-label="Municipal Tourism Officer">
                    <div class="about-page__section-body">
                        @include('partials.about-team-member', [
                            'stem' => $tourismHead['stem'],
                            'name' => $tourismHead['name'],
                            'role' => $tourismHead['role'],
                            'bio' => $tourismHead['bio'],
                            'imageUrl' => $resolveAboutImage($tourismHead['stem']),
                            'cardClass' => 'about-team-card',
                        ])
                    </div>
                </section>
            </div>
        </div>

    <section class="mb-10 md:mb-12" aria-labelledby="about-development">
        <h2 id="about-development" class="mb-5 flex items-center justify-center gap-2 text-center text-xl font-bold text-brand-dark md:text-2xl">
            <i class="fas fa-users text-brand-primary"></i> Development team
        </h2>
        <p class="mx-auto mb-6 max-w-2xl text-center text-sm font-medium text-slate-700">
            Three programmers, one UX/UI designer, and one documentor.
        </p>
        <div class="mx-auto flex max-w-6xl flex-wrap justify-center gap-4">
            @foreach ($developmentTeam as $member)
                <div class="w-full max-w-xs sm:max-w-sm">
                    @include('partials.about-team-member', [
                        'stem' => $member['stem'],
                        'name' => $member['name'],
                        'role' => $member['role'],
                        'bio' => $member['bio'],
                        'imageUrl' => $resolveAboutImage($member['stem']),
                    ])
                </div>
            </div>
        </section>
    </div>
</main>
