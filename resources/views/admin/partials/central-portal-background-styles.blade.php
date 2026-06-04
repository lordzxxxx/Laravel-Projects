/* Central admin portal page scrim — watermark lives on .main-content via main-content-watermark-styles */
body.admin-central-portal {
    margin: 0;
    background: var(--app-page-bg, #f4f8f5);
    min-height: 100vh;
}
body.admin-central-portal::after {
    content: '';
    position: fixed;
    inset: 0;
    z-index: -1;
    /* Soft pink↔green ambience tying the page to the brand palette */
    background:
        radial-gradient(1100px 620px at 12% 8%, rgba(211, 120, 151, 0.10) 0%, rgba(211, 120, 151, 0) 60%),
        radial-gradient(1000px 640px at 88% 92%, rgba(121, 159, 118, 0.10) 0%, rgba(121, 159, 118, 0) 60%),
        rgba(255, 255, 255, 0.6);
    pointer-events: none;
}

html.dark body.admin-central-portal::after {
    background:
        radial-gradient(1100px 620px at 12% 8%, rgba(211, 120, 151, 0.08) 0%, rgba(211, 120, 151, 0) 60%),
        radial-gradient(1000px 640px at 88% 92%, rgba(121, 159, 118, 0.08) 0%, rgba(121, 159, 118, 0) 60%),
        rgba(15, 23, 42, 0.88);
}
