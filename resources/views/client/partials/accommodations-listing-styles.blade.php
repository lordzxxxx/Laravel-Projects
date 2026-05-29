.client-header-logos {
    display: flex;
    flex-shrink: 0;
    flex-wrap: nowrap;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
}

.client-header-logos img {
    display: block;
    height: 7rem;
    width: auto;
    max-width: 5.5rem;
    min-width: 4.5rem;
    object-fit: contain;
    flex: 0 0 auto;
}

@media (min-width: 640px) {
    .client-header-logos img {
        height: 8rem;
        max-width: 6.5rem;
    }
}

@media (min-width: 1024px) {
    .client-header-logos img {
        height: 11rem;
        max-width: 8rem;
    }
}
