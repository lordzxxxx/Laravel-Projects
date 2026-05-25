.navbar {
    background: rgba(255, 255, 255, 0.86);
    padding: 0 18px;
    height: var(--app-topbar-height, 76px);
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 12px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    border-bottom: 1px solid rgba(229, 231, 235, 0.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    position: fixed !important;
    width: 100%;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    backface-visibility: hidden;
}

.nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; flex-shrink: 0; min-width: 0; }
.nav-logo img { width: 45px; height: 45px; border-radius: 0; border: none; object-fit: contain; flex-shrink: 0; }

.nav-brand-text { display: flex; flex-direction: column; align-items: flex-start; line-height: 1; }
.nav-brand-title { font-size: 0.78rem; font-weight: 800; color: var(--green-dark); line-height: 1.05; letter-spacing: 0.02em; }
.nav-brand-subtitle { margin-top: 1px; font-size: 0.48rem; font-weight: 600; color: var(--green-medium); line-height: 1; letter-spacing: 0.08em; text-transform: uppercase; }

.nav-logo span { font-size: 1.1rem; font-weight: 700; color: var(--green-dark); line-height: 1.05; }
.nav-logo-text { display: flex; flex-direction: column; align-items: flex-start; justify-content: center; gap: 1px; min-width: 0; max-width: min(260px, 36vw); }
.nav-logo-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--green-dark);
    line-height: 1.15;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
}
.nav-logo-subtitle {
    font-size: 0.65rem;
    font-weight: 600;
    color: var(--green-medium);
    line-height: 1.2;
    letter-spacing: 0.02em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
}

.nav-links { display: flex; gap: 4px; list-style: none; flex: 1; min-width: 0; justify-content: center; }
.nav-links li { display: flex; align-items: center; }
.nav-links a {
    text-decoration: none;
    color: var(--gray-600, #4B5563);
    font-weight: 600;
    font-size: 0.75rem;
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid rgba(15, 23, 42, 0.1);
    background: rgba(255, 255, 255, 0.55);
    min-height: 42px;
    box-sizing: border-box;
    transition: background-color 0.28s cubic-bezier(0.4, 0, 0.2, 1),
        color 0.28s cubic-bezier(0.4, 0, 0.2, 1),
        border-color 0.28s cubic-bezier(0.4, 0, 0.2, 1),
        box-shadow 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}
.nav-links a:hover, .nav-links a.active {
    background: var(--green-primary);
    color: var(--white);
    border-color: rgba(27, 94, 32, 0.45);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
}
.nav-links a:focus-visible {
    outline: 2px solid var(--green-primary);
    outline-offset: 2px;
}

@media (prefers-reduced-motion: reduce) {
    .nav-links a,
    .nav-btn {
        transition-duration: 0.01ms !important;
    }
}

.nav-actions { display: flex; gap: 8px; align-items: center; justify-self: end; flex-shrink: 0; }

/* Keep right-side controls from causing center-nav shifts */
.nav-actions > * {
    flex-shrink: 0;
}
.user-display {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    background: #f0fdf4;
    border-radius: 10px;
    border: 1px solid #d1fae5;
    max-width: 280px;
    min-width: 0;
}
.user-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--green-primary);
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
}
.user-info { text-align: left; }
.user-name {
    font-weight: 700;
    color: var(--green-dark);
    font-size: 0.75rem;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}
.user-role {
    font-size: 0.62rem;
    color: var(--green-medium);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.nav-btn {
    padding: 8px 12px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.72rem;
    text-decoration: none;
    transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
    border: none;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}
.nav-btn.primary {
    background: var(--green-dark);
    color: var(--white);
    border: 1px solid rgba(27, 94, 32, 0.4);
}
.nav-btn.primary:hover {
    background: var(--green-primary);
    box-shadow: 0 4px 14px rgba(46, 125, 50, 0.25);
}

.nav-toggle {
    display: none;
    background: transparent;
    border: 1px solid var(--green-soft);
    color: var(--green-dark);
    width: 40px;
    height: 40px;
    border-radius: 10px;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
}
.nav-toggle:focus-visible { outline: 2px solid var(--green-primary); outline-offset: 2px; }

@media (max-width: 960px) {
    .navbar {
        grid-template-columns: minmax(0, 1fr) auto;
        grid-auto-rows: auto;
        height: auto;
        min-height: var(--app-topbar-height-mobile, 64px);
        padding: 10px 14px;
        align-content: center;
    }
    .navbar.nav-open {
        align-content: start;
        max-height: 100dvh;
        overflow-y: auto;
        overscroll-behavior: contain;
    }
    .nav-logo {
        max-width: 100%;
    }
    .nav-logo-text,
    .nav-brand-text,
    .nav-logo > span {
        min-width: 0;
    }
    .nav-toggle { display: inline-flex; order: 2; justify-self: end; }
    .nav-links {
        display: none;
        grid-column: 1 / -1;
        position: static;
        background: var(--white);
        flex-direction: column;
        align-items: stretch;
        padding: 12px 0 0;
        gap: 6px;
        border-top: 1px solid var(--green-soft);
        box-shadow: none;
        max-height: none;
        overflow: visible;
        width: 100%;
    }
    .nav-links a {
        width: 100%;
        justify-content: flex-start;
        white-space: normal;
    }
    #appNavbar.nav-open .nav-links { display: flex; }
    .nav-actions { display: none; }
    #appNavbar.nav-open .nav-actions {
        display: flex;
        grid-column: 1 / -1;
        justify-self: stretch;
        position: static;
        padding: 10px 0 2px;
        background: var(--white);
        flex-wrap: wrap;
        gap: 10px;
        box-shadow: none;
        transform: none;
        width: 100%;
    }
    #appNavbar.nav-open .nav-actions > * {
        min-width: 0;
    }
    #appNavbar.nav-open .nav-actions form,
    #appNavbar.nav-open .nav-actions .nav-btn {
        width: 100%;
    }
    #appNavbar.nav-open .nav-actions .nav-btn {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .navbar { padding: 10px 12px; }
    .user-display { max-width: 170px; }
    .nav-brand-title { font-size: 0.7rem; }
    .nav-brand-subtitle { font-size: 0.44rem; }
}

@media (max-width: 420px) {
    .nav-logo img {
        width: 40px;
        height: 40px;
    }
    .nav-logo span,
    .nav-logo-title {
        font-size: 0.95rem;
    }
    .nav-logo-subtitle,
    .nav-brand-subtitle {
        display: none;
    }
    .user-display {
        width: 100%;
        max-width: 100%;
    }
    .user-name {
        max-width: min(220px, calc(100vw - 110px));
    }
}
