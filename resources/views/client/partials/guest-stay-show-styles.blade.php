{{-- Accommodation detail (explore stay show) — shared responsive shell; keep in sync with show.blade.php layout tokens. --}}
.explore-stay-show {
    width: 100%;
    max-width: none;
    margin: 0;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: clamp(1rem, 2vw, 1.5rem);
    flex: 1;
    --stay-max: 78rem;
}

.explore-stay-show__crumb,
.explore-stay-hero,
.explore-stay-show__content,
.explore-stay-show__calendar {
    width: 100%;
    max-width: var(--stay-max);
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 900px) {
    .explore-stay-hero {
        grid-template-columns: 1fr;
    }

    .explore-stay-show__book-wrap {
        position: static;
        max-width: none;
    }
}
