.appearance-prefs .appearance-label {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--ink-700, #334155);
    margin: 0 0 0.65rem;
}
.appearance-prefs .appearance-label + .appearance-theme-grid {
    margin-bottom: 1rem;
}
.appearance-theme-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}
@media (min-width: 520px) {
    .appearance-theme-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
.appearance-theme-option {
    position: relative;
    display: block;
    min-width: 0;
    cursor: pointer;
}
.appearance-theme-option input {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
.appearance-theme-option .appearance-theme-card {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    min-width: 0;
    height: 100%;
    border: 2px solid var(--app-surface-border, #e5e7eb);
    border-radius: 12px;
    padding: 0.85rem;
    background: var(--app-surface-bg, #fff);
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.appearance-theme-option input:checked + .appearance-theme-card {
    border-color: var(--chrome-focus-ring, var(--green-primary, #457359));
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--chrome-focus-ring, #457359) 18%, transparent);
}
.appearance-theme-option input:focus-visible + .appearance-theme-card {
    outline: 2px solid var(--chrome-focus-ring, #457359);
    outline-offset: 2px;
}
.appearance-swatch-row {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}
.appearance-swatch {
    height: 22px;
    flex: 1 1 0;
    min-width: 0;
    border-radius: 6px;
    border: 1px solid rgba(15, 23, 42, 0.08);
}
.appearance-theme-copy {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}
.appearance-theme-name {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--ink-800, #1f2937);
}
.appearance-theme-desc {
    font-size: 0.75rem;
    line-height: 1.4;
    color: var(--ink-500, #64748b);
}
.appearance-mode-options {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.appearance-mode-option {
    position: relative;
    display: inline-block;
    cursor: pointer;
}
.appearance-mode-option input {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
.appearance-mode-option > span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.5rem 0.85rem;
    border-radius: 999px;
    border: 1px solid var(--app-surface-border, #e5e7eb);
    background: var(--app-surface-bg, #fff);
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--ink-700, #334155);
    transition: border-color 0.15s ease, background-color 0.15s ease, color 0.15s ease;
}
.appearance-mode-option input:checked + span {
    border-color: var(--chrome-focus-ring, var(--green-primary, #457359));
    background: var(--chrome-surface-bg, #f0fdf4);
    color: var(--chrome-icon-color, var(--green-primary, #457359));
}
