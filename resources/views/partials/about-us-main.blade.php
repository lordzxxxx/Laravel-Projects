<style>
    body.about-portal-page {
        min-height: 100dvh;
        background-color: #f8fafc;
        background-image: linear-gradient(
            135deg,
            rgba(255, 255, 255, 0.95) 0%,
            rgba(255, 255, 255, 0.85) 50%,
            rgba(27, 94, 32, 0.1) 100%
        ), url('/COMMUNAL.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    /* Clear fixed portal nav: bar height + 20–30px breathing room */
    body.about-portal-page main.about-page {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: none;
        margin: 0;
        padding: var(--portal-content-below-nav, calc(var(--app-topbar-height, 4rem) + clamp(1.25rem, 2vw, 1.875rem)))
            clamp(1rem, 2.5vw, 2rem)
            clamp(2rem, 4vw, 3rem);
        min-height: calc(100dvh - var(--app-topbar-height, 4rem));
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        gap: clamp(1rem, 2vw, 1.35rem);
    }

    @media (max-width: 767px) {
        body.about-portal-page main.about-page {
            padding-top: var(--portal-content-below-nav, calc(var(--app-topbar-height-mobile, 5.75rem) + clamp(1rem, 2vw, 1.5rem)));
            min-height: calc(100dvh - var(--app-topbar-height-mobile, 5.75rem));
        }
    }

    .about-page__hero {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(min-content, 20rem);
        align-items: center;
        gap: clamp(1rem, 2vw, 1.75rem);
        margin: 0;
        padding: 0 0 clamp(0.85rem, 1.5vw, 1.15rem);
        border-bottom: 1px solid rgba(15, 23, 42, 0.08);
    }

    .about-page__hero-main {
        min-width: 0;
    }

    .about-page__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin: 0 0 0.35rem;
        font-size: 0.6875rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--green-primary, #457359);
    }

    .about-page__title {
        margin: 0 0 0.4rem;
        font-family: var(--app-font-display, inherit);
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -0.03em;
        color: var(--gray-900, #0f172a);
    }

    .about-page__title span {
        color: var(--green-primary, #457359);
    }

    .about-page__lede {
        margin: 0;
        max-width: 40rem;
        font-size: 0.9375rem;
        line-height: 1.6;
        color: var(--gray-600, #4b5563);
    }

    .about-page__logos {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: flex-end;
        gap: clamp(0.5rem, 1.5vw, 1rem);
        flex-shrink: 0;
        max-width: min(100%, 20rem);
        padding-top: 0;
    }

    .about-page__logos img {
        height: clamp(5.5rem, 11vw, 11rem);
        width: auto;
        max-width: min(7.25rem, 30vw);
        object-fit: contain;
        flex: 0 1 auto;
    }

    .about-page__content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: clamp(1.25rem, 2.5vw, 1.75rem);
    }

    .about-page__section {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 0.75rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .about-page__section-head {
        padding: 0.85rem 1.15rem;
        border-bottom: 1px solid var(--gray-100, #f3f4f6);
    }

    .about-page__section-head h2 {
        margin: 0;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--gray-800, #1f2937);
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .about-page__section-head h2 i {
        color: var(--green-primary, #457359);
        font-size: 0.8rem;
    }

    .about-page__section-head p {
        margin: 0.25rem 0 0;
        font-size: 0.6875rem;
        color: var(--gray-500, #6b7280);
        line-height: 1.4;
    }

    .about-page__section-body {
        padding: clamp(0.85rem, 1.5vw, 1.15rem);
    }

    .about-page__leadership-head {
        margin: 0 0 0.15rem;
    }

    .about-page__leadership-head h2 {
        margin: 0;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--gray-800, #1f2937);
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .about-page__leadership-head h2 i {
        color: var(--green-primary, #457359);
        font-size: 0.8rem;
    }

    .about-page__leadership-head p {
        margin: 0.25rem 0 0;
        font-size: 0.6875rem;
        color: var(--gray-500, #6b7280);
        line-height: 1.4;
    }

    .about-page__grid--leadership {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: clamp(0.75rem, 1.5vw, 1rem);
    }

    .about-page__section--leader {
        display: flex;
        flex-direction: column;
    }

    .about-page__section--leader .about-page__section-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: clamp(0.65rem, 1.2vw, 0.85rem);
    }

    .about-page__section--leader .about-team-card {
        flex: 1;
        display: flex;
        flex-direction: column;
        border: none;
        background: transparent;
        box-shadow: none;
        border-radius: 0;
    }

    .about-page__section--leader .about-team-card:hover {
        border-color: transparent;
        box-shadow: none;
    }

    .about-page__grid--team {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 14rem), 1fr));
        gap: clamp(0.75rem, 1.5vw, 1rem);
    }

    .about-page .about-team-card {
        height: 100%;
        border-radius: 0.65rem;
        border: 1px solid var(--gray-200, #e5e7eb);
        background: #fff;
        box-shadow: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .about-page .about-team-card:hover {
        border-color: color-mix(in srgb, var(--green-primary, #457359) 28%, var(--gray-200, #e5e7eb));
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    }

    @media (max-width: 1100px) {
        .about-page__hero {
            grid-template-columns: minmax(0, 1fr) minmax(min-content, 17rem);
        }

        .about-page__logos img {
            height: clamp(4.75rem, 9vw, 8.5rem);
            max-width: min(6.25rem, 26vw);
        }
    }

    @media (max-width: 900px) {
        .about-page__hero {
            grid-template-columns: 1fr;
            align-items: start;
            gap: 1rem;
        }

        .about-page__logos {
            justify-content: flex-start;
            flex-wrap: wrap;
            max-width: 100%;
        }

        .about-page__logos img {
            height: clamp(4.25rem, 16vw, 7rem);
            max-width: min(5.75rem, 30vw);
        }

        .about-page__grid--leadership {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .about-page__logos {
            justify-content: center;
        }

        .about-page__logos img {
            height: clamp(3.75rem, 22vw, 5.5rem);
            max-width: min(5rem, 28vw);
        }
    }

    @media (max-width: 640px) {
        .about-page__grid--team {
            grid-template-columns: 1fr;
        }
    }
</style>

@php
    $aboutTeamImages = config('about_team.images', []);
    $resolveAboutImage = function (string $stem) use ($aboutTeamImages): ?string {
        $rel = $aboutTeamImages[$stem] ?? null;
        if ($rel === null || $rel === '' || ! is_file(public_path($rel))) {
            return null;
        }
        $rel = str_replace('\\', '/', $rel);
        $parts = explode('/', $rel);
        $file = array_pop($parts);
        $path = count($parts) ? implode('/', $parts).'/'.rawurlencode($file) : rawurlencode($file);

        return asset($path);
    };

    $mayor = ['stem' => 'mayor', 'name' => null, 'role' => 'Mayor', 'bio' => 'Leads the municipality’s vision for inclusive growth and tourism.'];
    $tourismHead = ['stem' => 'tourism-head', 'name' => null, 'role' => 'Municipal Tourism Officer (Impasugong)', 'bio' => 'Champions destinations, visitor experience, and local hospitality.'];
    $developmentTeam = [
        ['stem' => 'programmer-1', 'name' => null, 'role' => 'Lead Programmer', 'bio' => 'Builds and maintains ImpaStay’s platform, features, and integrations.'],
        ['stem' => 'programmer-2', 'name' => null, 'role' => 'Project Manager', 'bio' => 'Coordinates delivery, priorities, and stakeholder alignment.'],
        ['stem' => 'programmer-3', 'name' => null, 'role' => 'Programmer', 'bio' => 'Implements features and keeps the platform reliable.'],
        ['stem' => 'ux-ui-designer', 'name' => null, 'role' => 'UX/UI Designer', 'bio' => 'Designs interfaces and user experiences for guests and property owners.'],
        ['stem' => 'documentor', 'name' => null, 'role' => 'Documentor', 'bio' => 'Creates documentation, UX/UI notes, and user-facing guides.'],
    ];
@endphp

<main class="about-page" id="about-main">
    <header class="about-page__hero">
        <div class="about-page__hero-main">
            <p class="about-page__eyebrow">
                <i class="fas fa-circle-info" aria-hidden="true"></i>
                Official tourism portal
            </p>
            <h1 class="about-page__title">
                About <span>IMPASUGONG TOURISM</span>
            </h1>
            <p class="about-page__lede">
                IMPASUGONG TOURISM connects guests with trusted accommodations across Impasugong—supporting local tourism,
                property owners, and the community through one central platform.
            </p>
        </div>
        <div class="about-page__logos">
            <img src="{{ asset('images/love-impasugong-transparent.png') }}" alt="Love Impasugong" decoding="async">
            <img src="{{ asset('SYSTEMLOGO.png') }}" alt="IMPASUGONG TOURISM" decoding="async">
            <img src="{{ asset('Lgu Socmed Template-02 2.png') }}" alt="LGU Impasugong" decoding="async">
        </div>
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

        <section class="about-page__section" aria-labelledby="about-development">
            <div class="about-page__section-head">
                <h2 id="about-development">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    Development team
                </h2>
                <p>Three programmers, one UX/UI designer, and one documentor.</p>
            </div>
            <div class="about-page__section-body">
                <div class="about-page__grid about-page__grid--team">
                    @foreach ($developmentTeam as $member)
                        <div class="about-team-card-wrap">
                            @include('partials.about-team-member', [
                                'stem' => $member['stem'],
                                'name' => $member['name'],
                                'role' => $member['role'],
                                'bio' => $member['bio'],
                                'imageUrl' => $resolveAboutImage($member['stem']),
                                'cardClass' => 'about-team-card',
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</main>
