{{-- Mobile menu for standalone pages that still use the legacy .navbar markup --}}
<style>
    @media (max-width: 960px) {
        .navbar.legacy-navbar-responsive {
            flex-wrap: wrap;
            height: auto;
            min-height: 70px;
            padding: 0 clamp(0.75rem, 3vw, 1.25rem);
        }
        .navbar.legacy-navbar-responsive .legacy-nav-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            min-height: 44px;
            margin-left: auto;
            border: 1px solid rgba(69, 115, 89, 0.25);
            border-radius: 10px;
            background: var(--green-soft, #edf4ea);
            color: var(--green-dark, #34543f);
            cursor: pointer;
        }
        .navbar.legacy-navbar-responsive .nav-links,
        .navbar.legacy-navbar-responsive .nav-actions {
            display: none;
            width: 100%;
            flex-direction: column;
            align-items: stretch;
            gap: 0.35rem;
            padding: 0.5rem 0 0.75rem;
        }
        .navbar.legacy-navbar-responsive.nav-open .nav-links,
        .navbar.legacy-navbar-responsive.nav-open .nav-actions {
            display: flex;
        }
        .navbar.legacy-navbar-responsive .nav-links a,
        .navbar.legacy-navbar-responsive .nav-btn {
            width: 100%;
            justify-content: center;
            min-height: 44px;
        }
    }
    @media (min-width: 961px) {
        .navbar.legacy-navbar-responsive .legacy-nav-toggle {
            display: none;
        }
    }
</style>
<script>
    document.querySelectorAll('.navbar.legacy-navbar-responsive .legacy-nav-toggle').forEach((btn) => {
        btn.addEventListener('click', () => {
            const nav = btn.closest('.navbar');
            if (nav) {
                nav.classList.toggle('nav-open');
                btn.setAttribute('aria-expanded', nav.classList.contains('nav-open') ? 'true' : 'false');
            }
        });
    });
</script>
