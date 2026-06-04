<script>
    (function () {
        function bindPortalNavBurger() {
            document.querySelectorAll('.portal-nav-minimal.portal-nav-minimal--burger').forEach((nav) => {
                const toggle = nav.querySelector('.portal-nav-minimal__toggle');
                if (!toggle || toggle.dataset.portalNavBound === '1') {
                    return;
                }

                toggle.dataset.portalNavBound = '1';

                nav.querySelectorAll('.portal-nav-minimal__mobile-links a').forEach((link) => {
                    link.addEventListener('click', () => {
                        nav.classList.remove('nav-open');
                        toggle.setAttribute('aria-expanded', 'false');
                    });
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', bindPortalNavBurger);
        } else {
            bindPortalNavBurger();
        }
    })();
</script>
