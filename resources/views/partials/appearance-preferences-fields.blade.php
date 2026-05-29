@php
    $appearance = $appearance ?? auth()->user()?->normalizedAppearancePreferences() ?? ['theme' => 'impasugong', 'mode' => 'light'];
@endphp

<div class="appearance-prefs">
    <p class="appearance-label">Color theme</p>
    <div class="appearance-theme-grid">
        <label class="appearance-theme-option">
            <input type="radio" name="appearance_theme" value="impasugong" {{ old('appearance_theme', $appearance['theme']) === 'impasugong' ? 'checked' : '' }}>
            <div class="appearance-theme-card">
                <div class="appearance-swatch-row" aria-hidden="true">
                    <span class="appearance-swatch" style="background:#D37897;"></span>
                    <span class="appearance-swatch" style="background:#457359;"></span>
                    <span class="appearance-swatch" style="background:#F9DEE5;"></span>
                </div>
                <div class="appearance-theme-copy">
                    <span class="appearance-theme-name">Impasugong</span>
                    <span class="appearance-theme-desc">Pink accents with forest green actions</span>
                </div>
            </div>
        </label>
        <label class="appearance-theme-option">
            <input type="radio" name="appearance_theme" value="green" {{ old('appearance_theme', $appearance['theme']) === 'green' ? 'checked' : '' }}>
            <div class="appearance-theme-card">
                <div class="appearance-swatch-row" aria-hidden="true">
                    <span class="appearance-swatch" style="background:#457359;"></span>
                    <span class="appearance-swatch" style="background:#799F76;"></span>
                    <span class="appearance-swatch" style="background:#CBDFC6;"></span>
                </div>
                <div class="appearance-theme-copy">
                    <span class="appearance-theme-name">Green</span>
                    <span class="appearance-theme-desc">Classic green chrome</span>
                </div>
            </div>
        </label>
    </div>

    <p class="appearance-label" style="margin-top:1rem;">Display mode</p>
    <div class="appearance-mode-options">
        <label class="appearance-mode-option">
            <input type="radio" name="appearance_mode" value="light" {{ old('appearance_mode', $appearance['mode']) === 'light' ? 'checked' : '' }}>
            <span><i class="fa-solid fa-sun"></i> Light</span>
        </label>
        <label class="appearance-mode-option">
            <input type="radio" name="appearance_mode" value="dark" {{ old('appearance_mode', $appearance['mode']) === 'dark' ? 'checked' : '' }}>
            <span><i class="fa-solid fa-moon"></i> Dark</span>
        </label>
        <label class="appearance-mode-option">
            <input type="radio" name="appearance_mode" value="system" {{ old('appearance_mode', $appearance['mode']) === 'system' ? 'checked' : '' }}>
            <span><i class="fa-solid fa-desktop"></i> System</span>
        </label>
    </div>
    @error('appearance_theme') <div class="field-error">{{ $message }}</div> @enderror
    @error('appearance_mode') <div class="field-error">{{ $message }}</div> @enderror
</div>
