@php
    use App\Support\AppearancePreferences;

    $appearanceTheme = AppearancePreferences::THEME_IMPASUGONG;
    $appearanceMode = AppearancePreferences::MODE_LIGHT;

    if (auth()->check()) {
        $appearanceTheme = auth()->user()->appearanceTheme();
        $appearanceMode = auth()->user()->appearanceMode();
    }
@endphp
<script>
    (function () {
        var isAuthenticated = @json(auth()->check());
        var serverTheme = @json($appearanceTheme);
        var serverMode = @json($appearanceMode);

        function normalizeTheme(theme) {
            return theme === 'green' ? 'green' : 'impasugong';
        }

        function normalizeMode(mode) {
            if (mode === 'dark' || mode === 'system') {
                return mode;
            }
            return 'light';
        }

        function resolveDark(mode) {
            if (mode === 'dark') {
                return true;
            }
            if (mode === 'system') {
                try {
                    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                } catch (e) {
                    return false;
                }
            }
            return false;
        }

        function readGuestStorage() {
            try {
                var stored = localStorage.getItem('impa_appearance');
                if (!stored) {
                    return null;
                }
                return JSON.parse(stored);
            } catch (e) {
                return null;
            }
        }

        function persistGuest(theme, mode) {
            try {
                localStorage.setItem('impa_appearance', JSON.stringify({
                    theme: normalizeTheme(theme),
                    mode: normalizeMode(mode),
                }));
            } catch (e) {}
        }

        function apply(theme, mode, options) {
            options = options || {};
            var normalizedTheme = normalizeTheme(theme);
            var normalizedMode = normalizeMode(mode);
            var root = document.documentElement;

            root.setAttribute('data-theme', normalizedTheme);
            root.classList.toggle('dark', resolveDark(normalizedMode));

            var shouldPersist = options.persist !== false && !isAuthenticated;
            if (shouldPersist) {
                persistGuest(normalizedTheme, normalizedMode);
            }

            return {
                theme: normalizedTheme,
                mode: normalizedMode,
            };
        }

        var systemMediaQuery = null;
        var systemListener = null;

        function clearSystemListener() {
            if (systemMediaQuery && systemListener) {
                try {
                    if (systemMediaQuery.removeEventListener) {
                        systemMediaQuery.removeEventListener('change', systemListener);
                    } else if (systemMediaQuery.removeListener) {
                        systemMediaQuery.removeListener(systemListener);
                    }
                } catch (e) {}
            }
            systemMediaQuery = null;
            systemListener = null;
        }

        function bindSystemListener(mode) {
            clearSystemListener();
            if (mode !== 'system' || !window.matchMedia) {
                return;
            }

            systemMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            systemListener = function () {
                var themeInput = document.querySelector('input[name="appearance_theme"]:checked');
                var modeInput = document.querySelector('input[name="appearance_mode"]:checked');
                var theme = themeInput ? themeInput.value : document.documentElement.getAttribute('data-theme');
                apply(theme || 'impasugong', 'system', { persist: false });
            };

            try {
                if (systemMediaQuery.addEventListener) {
                    systemMediaQuery.addEventListener('change', systemListener);
                } else if (systemMediaQuery.addListener) {
                    systemMediaQuery.addListener(systemListener);
                }
            } catch (e) {}
        }

        function readCheckedAppearance() {
            var themeInput = document.querySelector('input[name="appearance_theme"]:checked');
            var modeInput = document.querySelector('input[name="appearance_mode"]:checked');
            return {
                theme: themeInput ? themeInput.value : document.documentElement.getAttribute('data-theme') || 'impasugong',
                mode: modeInput ? modeInput.value : 'light',
            };
        }

        function initProfilePreview() {
            var themeInputs = document.querySelectorAll('input[name="appearance_theme"]');
            var modeInputs = document.querySelectorAll('input[name="appearance_mode"]');

            if (!themeInputs.length && !modeInputs.length) {
                return;
            }

            function syncFromForm() {
                var values = readCheckedAppearance();
                apply(values.theme, values.mode, { persist: false });
                bindSystemListener(values.mode);
            }

            function bindPreviewInput(input) {
                input.addEventListener('change', syncFromForm);
                input.addEventListener('input', syncFromForm);
            }

            themeInputs.forEach(bindPreviewInput);
            modeInputs.forEach(bindPreviewInput);

            syncFromForm();

            window.addEventListener('pagehide', clearSystemListener);
        }

        window.ImpaAppearance = {
            apply: apply,
            resolveDark: resolveDark,
            initProfilePreview: initProfilePreview,
            isAuthenticated: isAuthenticated,
        };

        var bootTheme = serverTheme;
        var bootMode = serverMode;

        if (!isAuthenticated) {
            var stored = readGuestStorage();
            if (stored && typeof stored.theme === 'string') {
                bootTheme = stored.theme;
            }
            if (stored && typeof stored.mode === 'string') {
                bootMode = stored.mode;
            }
        }

        apply(bootTheme, bootMode, { persist: !isAuthenticated });
    })();
</script>
