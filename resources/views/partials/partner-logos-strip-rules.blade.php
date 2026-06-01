        /* Shared 3-logo strip — About + Explore accommodations (central portal) */
        .partner-logos-strip {
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: flex-end;
            gap: clamp(0.5rem, 1.5vw, 1rem);
            flex-shrink: 0;
            max-width: min(100%, 20rem);
            padding-top: 0;
            line-height: 0;
        }

        .partner-logos-strip img {
            height: clamp(5.5rem, 11vw, 11rem);
            width: auto;
            max-width: min(7.25rem, 30vw);
            object-fit: contain;
            flex: 0 1 auto;
        }

        @media (max-width: 1100px) {
            .partner-logos-strip img {
                height: clamp(4.75rem, 9vw, 8.5rem);
                max-width: min(6.25rem, 26vw);
            }
        }

        @media (max-width: 900px) {
            .partner-logos-strip {
                justify-content: flex-start;
                flex-wrap: wrap;
                max-width: 100%;
            }

            .partner-logos-strip img {
                height: clamp(4.25rem, 16vw, 7rem);
                max-width: min(5.75rem, 30vw);
            }
        }

        @media (max-width: 480px) {
            .partner-logos-strip {
                justify-content: center;
            }

            .partner-logos-strip img {
                height: clamp(3.75rem, 22vw, 5.5rem);
                max-width: min(5rem, 28vw);
            }
        }
