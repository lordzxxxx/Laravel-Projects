{{-- Hospitality portal registration pages: theme + accessibility helpers (paired with login-public). --}}
<script>
    (function () {
        try {
            var t = localStorage.getItem('impa_auth_theme_hospitality');
            document.documentElement.classList.toggle(
                'dark',
                t === 'dark' || (!t && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)
            );
        } catch (e) {}
    })();
</script>
<style>
    .reg-skip:focus {
        clip: auto; height: auto; width: auto; overflow: visible; outline: none;
        padding: .75rem 1rem; top: .75rem; left: .75rem; z-index: 100; margin: 0;
        border-radius: .5rem;
    }
    .reg-skip {
        position: absolute; left: .75rem; top: -100px;
        clip: rect(0 0 0 0); height: 1px; width: 1px; overflow: hidden;
        white-space: nowrap; border: 0;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15);
        background: rgb(27 94 32); color: rgb(255 255 255);
        font-size: 0.875rem; font-weight: 600;
    }
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
    @media (prefers-contrast: more) {
        .reg-field { border-width: 2px !important; border-color: CanvasText !important; }
        .reg-card { outline: 2px solid CanvasText !important; outline-offset: 2px; }
    }
</style>
