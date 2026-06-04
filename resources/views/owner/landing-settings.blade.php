<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    @include('partials.app-vite-head')
    <title>Landing &amp; Logo — {{ $tenant->name }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @include('owner.partials.owner-page-fonts')
        * { box-sizing: border-box; }

        :root {
            @include('partials.tenant-theme-css-vars', ['themeTenant' => $tenant])
        }


        .landing-settings-form {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            min-height: 0;
        }

        .settings-grid {
            flex: 1;
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
            gap: 1.25rem;
            align-items: start;
        }

        .settings-stack {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            min-height: 0;
        }

        .settings-panel {
            background: var(--app-surface-bg, #fff);
            border: 1px solid var(--app-surface-border, #e5e7eb);
            border-radius: 14px;
            box-shadow: var(--shadow-sm, 0 1px 2px rgba(15, 23, 42, 0.05));
            overflow: hidden;
        }

        .settings-panel__head {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--app-surface-border, #e5e7eb);
            background: var(--app-surface-muted-bg, #f8fafc);
        }

        .settings-panel__head h2 {
            margin: 0;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-600, #475569);
        }

        .settings-panel__head p {
            margin: 0.35rem 0 0;
            font-size: 0.8125rem;
            color: var(--ink-500, #64748b);
            line-height: 1.45;
        }

        .settings-panel__body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .field label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--ink-700, #334155);
            margin-bottom: 0.4rem;
        }

        .field input[type="text"],
        .field input[type="url"] {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1px solid var(--app-surface-border, #d1d5db);
            border-radius: 10px;
            font-size: 0.9375rem;
            background: var(--app-surface-bg, #fff);
            color: var(--ink-900, #0f172a);
        }

        .field input:focus {
            outline: none;
            border-color: var(--chrome-focus-ring, var(--green-primary, #457359));
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--chrome-focus-ring, #457359) 18%, transparent);
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .color-row {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 0.75rem;
            align-items: center;
        }

        .color-row input[type="color"] {
            width: 3rem;
            height: 2.5rem;
            padding: 0.2rem;
            border: 1px solid var(--app-surface-border, #d1d5db);
            border-radius: 10px;
            background: var(--app-surface-bg, #fff);
            cursor: pointer;
        }

        .color-row input[type="text"] {
            font-family: ui-monospace, monospace;
            font-size: 0.875rem;
        }

        .upload-preview {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.85rem;
            border: 1px dashed var(--app-surface-border, #d1d5db);
            border-radius: 12px;
            background: var(--app-surface-muted-bg, #f8fafc);
        }

        .logo-checkerboard {
            background-color: #f8fafc;
            background-image:
                linear-gradient(45deg, #e2e8f0 25%, transparent 25%),
                linear-gradient(-45deg, #e2e8f0 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, #e2e8f0 75%),
                linear-gradient(-45deg, transparent 75%, #e2e8f0 75%);
            background-size: 12px 12px;
            background-position: 0 0, 0 6px, 6px -6px, -6px 0;
        }

        .upload-preview img {
            width: 4.5rem;
            height: 4.5rem;
            object-fit: contain;
            border-radius: 10px;
            border: 1px solid var(--app-surface-border, #e5e7eb);
            padding: 0.35rem;
            flex-shrink: 0;
        }

        .upload-preview img.logo-checkerboard {
            background-color: #f8fafc;
        }

        .upload-preview__meta {
            min-width: 0;
            flex: 1;
        }

        .upload-preview__meta p {
            margin: 0;
            font-size: 0.8125rem;
            color: var(--ink-500, #64748b);
            line-height: 1.45;
        }

        .file-input {
            width: 100%;
            font-size: 0.8125rem;
            color: var(--ink-700, #334155);
        }

        .check-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--ink-600, #475569);
        }

        .check-row input {
            width: auto;
            accent-color: var(--chrome-active-bg, #457359);
        }

        .logo-preview-new {
            display: none;
            margin-top: 0.5rem;
        }

        .logo-preview-new.is-visible {
            display: block;
        }

        .logo-preview-new img {
            max-width: 5rem;
            max-height: 5rem;
            object-fit: contain;
            border-radius: 10px;
            border: 1px solid var(--app-surface-border, #e5e7eb);
            padding: 0.35rem;
        }

        .settings-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: var(--app-surface-bg, #fff);
            border: 1px solid var(--app-surface-border, #e5e7eb);
            border-radius: 14px;
            box-shadow: var(--shadow-sm, 0 1px 2px rgba(15, 23, 42, 0.05));
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.35rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            background: var(--chrome-active-bg, var(--green-primary, #457359));
            color: #fff;
        }

        .btn-save:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-preview {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.7rem 1.1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            border: 1px solid var(--app-surface-border, #d1d5db);
            background: var(--app-surface-bg, #fff);
            color: var(--ink-700, #334155);
        }

        .btn-preview:hover {
            background: var(--app-surface-muted-bg, #f8fafc);
        }

        .field-error {
            color: #b91c1c;
            font-size: 0.8125rem;
            margin-top: 0.35rem;
        }

        @media (max-width: 1024px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .field-row {
                grid-template-columns: 1fr;
            }
        }

        @include('partials.appearance-preferences-styles')

        @include('owner.partials.top-navbar-styles')
    </style>
</head>
<body class="owner-nav-page">
    @include('owner.partials.top-navbar', ['active' => ''])

    <main class="landing-settings-main with-owner-nav owner-app-main">
        <header class="owner-page-hero">
            <p class="owner-page-hero__eyebrow">Branding</p>
            <h1 class="owner-page-hero__title">Landing &amp; logo</h1>
            <p class="owner-page-hero__lede">Brand colors, portal theme, logo, and GCash QR for your subdomain.</p>
        </header>

        @include('partials.flash-alerts')

        <form method="POST" action="{{ route('owner.landing.update', [], false) }}" enctype="multipart/form-data" class="landing-settings-form" data-loading-form>
            @csrf
            @method('PUT')

            <div class="settings-grid">
                <div class="settings-stack">
                    <section class="settings-panel">
                        <div class="settings-panel__head">
                            <h2>Theme</h2>
                            <p>Colors applied to your landing page and tenant chrome.</p>
                        </div>
                        <div class="settings-panel__body">
                            <div class="field">
                                <label for="primary_color">Primary</label>
                                <div class="color-row">
                                    <input type="color" id="primary_color_picker" value="{{ old('primary_color', $settings['primary_color']) }}" aria-label="Pick primary color">
                                    <input type="text" id="primary_color" name="primary_color" value="{{ old('primary_color', $settings['primary_color']) }}" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
                                </div>
                                @error('primary_color') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="field">
                                <label for="accent_color">Accent</label>
                                <div class="color-row">
                                    <input type="color" id="accent_color_picker" value="{{ old('accent_color', $settings['accent_color']) }}" aria-label="Pick accent color">
                                    <input type="text" id="accent_color" name="accent_color" value="{{ old('accent_color', $settings['accent_color']) }}" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
                                </div>
                                @error('accent_color') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </section>

                    <section class="settings-panel">
                        <div class="settings-panel__head">
                            <h2>Portal appearance</h2>
                            <p>Color theme and light/dark mode for your owner portal.</p>
                        </div>
                        <div class="settings-panel__body">
                            @include('partials.appearance-preferences-fields', ['appearance' => $appearance])
                        </div>
                    </section>
                </div>

                <div class="settings-stack">
                    <section class="settings-panel">
                        <div class="settings-panel__head">
                            <h2>Brand logo</h2>
                            <p>Optional. Love Impasugong is used until you upload your own. Logos are saved as PNG with a transparent background (white areas are removed automatically).</p>
                        </div>
                        <div class="settings-panel__body">
                            <div class="upload-preview">
                                <img src="{{ $tenant->brandLogoUrl() }}" alt="Current logo" class="{{ $tenant->logo_path ? 'logo-checkerboard' : '' }}">
                                <div class="upload-preview__meta">
                                    <p>Shown on your landing page, login, and navigation.</p>
                                </div>
                            </div>
                            <div class="field">
                                <label for="logo">Upload logo</label>
                                <input type="file" id="logo" name="logo" class="file-input" accept=".png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp">
                                @error('logo') <div class="field-error">{{ $message }}</div> @enderror
                                <div class="logo-preview-new" id="logo-preview" aria-hidden="true">
                                    <img src="" alt="Selected logo preview" class="logo-checkerboard">
                                </div>
                            </div>
                            @if($tenant->logo_path)
                                <label class="check-row">
                                    <input type="checkbox" name="remove_logo" value="1">
                                    Remove custom logo
                                </label>
                            @endif
                        </div>
                    </section>

                    <section class="settings-panel">
                        <div class="settings-panel__head">
                            <h2>GCash QR</h2>
                            <p>Optional QR for guest payment instructions.</p>
                        </div>
                        <div class="settings-panel__body">
                            @if($tenant->getGcashQrUrl())
                                <div class="upload-preview">
                                    <img src="{{ $tenant->getGcashQrUrl() }}" alt="GCash QR">
                                    <div class="upload-preview__meta">
                                        <p>Current QR on file.</p>
                                    </div>
                                </div>
                            @endif
                            <div class="field">
                                <label for="gcash_qr">Upload QR image</label>
                                <input type="file" id="gcash_qr" name="gcash_qr" class="file-input" accept=".png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp">
                                @error('gcash_qr') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                            @if($tenant->gcash_qr_path)
                                <label class="check-row">
                                    <input type="checkbox" name="remove_gcash_qr" value="1">
                                    Remove GCash QR
                                </label>
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            <div class="settings-actions">
                <button type="submit" class="btn-save" data-loading-button>
                    <i class="fas fa-check"></i> Save changes
                </button>
                <a href="{{ $tenant->publicUrl() }}" target="_blank" rel="noopener" class="btn-preview">
                    <i class="fas fa-external-link-alt"></i> Preview landing
                </a>
            </div>
        </form>
    </main>

    <script>
        (function () {
            function bindColorPicker(pickerId, textId) {
                var picker = document.getElementById(pickerId);
                var text = document.getElementById(textId);
                if (!picker || !text) return;
                picker.addEventListener('input', function () { text.value = picker.value; });
                text.addEventListener('input', function () {
                    if (/^#[0-9A-Fa-f]{6}$/.test(text.value)) picker.value = text.value;
                });
            }
            bindColorPicker('primary_color_picker', 'primary_color');
            bindColorPicker('accent_color_picker', 'accent_color');

            var logoInput = document.getElementById('logo');
            var logoPreview = document.getElementById('logo-preview');
            if (logoInput && logoPreview) {
                var img = logoPreview.querySelector('img');
                logoInput.addEventListener('change', function () {
                    var file = logoInput.files && logoInput.files[0];
                    if (!file || !file.type.startsWith('image/')) {
                        logoPreview.classList.remove('is-visible');
                        return;
                    }
                    img.src = URL.createObjectURL(file);
                    logoPreview.classList.add('is-visible');
                });
            }
        })();

        if (window.ImpaAppearance && typeof window.ImpaAppearance.initProfilePreview === 'function') {
            window.ImpaAppearance.initProfilePreview();
        }
    </script>
    <script>
        document.querySelectorAll('form[data-loading-form]').forEach(function (form) {
            form.addEventListener('submit', function () {
                var button = form.querySelector('[data-loading-button]');
                if (!button) return;
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Saving…';
            });
        });
    </script>
</body>
</html>
