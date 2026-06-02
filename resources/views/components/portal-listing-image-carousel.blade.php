@props([
    'accommodation',
    'propertyUrl' => null,
])

@php
    $galleryImages = $accommodation->galleryImageUrls();
    if (count($galleryImages) === 0) {
        $galleryImages = [asset('COMMUNAL.jpg')];
    }
    $slideCount = count($galleryImages);
    $detailUrl = $propertyUrl ?? route('portal.accommodations.show', $accommodation);
    $altBase = 'Photo of '.$accommodation->name;
    $fallbackSrc = asset('COMMUNAL.jpg');
@endphp

@once('portal-listing-image-carousel-styles')
<style>
    .portal-card-carousel {
        position: relative;
        width: 100%;
        height: 100%;
        isolation: isolate;
    }
    .portal-card-carousel__viewport {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background: linear-gradient(145deg, rgba(200, 230, 201, 0.45), rgba(241, 248, 244, 0.9));
    }
    .portal-card-carousel__slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.75s ease-in-out;
        will-change: opacity;
        pointer-events: none;
        z-index: 0;
    }
    .portal-card-carousel__slide.is-active {
        opacity: 1;
        z-index: 1;
        pointer-events: auto;
    }
    @media (prefers-reduced-motion: reduce) {
        .portal-card-carousel__slide {
            transition: none;
        }
    }
    .portal-card-carousel__slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
    }
    .portal-card-carousel__hit {
        position: absolute;
        inset: 0;
        z-index: 2;
    }
    .portal-card-carousel__btn {
        position: absolute;
        top: 50%;
        z-index: 4;
        display: flex;
        height: 2rem;
        width: 2rem;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.92);
        color: #1b5e20;
        font-size: 0.85rem;
        line-height: 1;
        box-shadow: 0 2px 10px rgba(27, 94, 32, 0.18);
        transform: translateY(-50%);
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s ease, transform 0.2s ease, background 0.2s ease;
    }
    .portal-card-carousel:hover .portal-card-carousel__btn,
    .portal-card-carousel:focus-within .portal-card-carousel__btn {
        opacity: 1;
    }
    .portal-card-carousel__btn:hover {
        background: #2e7d32;
        color: #fff;
        transform: translateY(-50%) scale(1.06);
    }
    .portal-card-carousel__btn--prev { left: 0.4rem; }
    .portal-card-carousel__btn--next { right: 0.4rem; }
    @media (max-width: 639px) {
        .portal-card-carousel__btn {
            opacity: 0.9;
        }
    }
    .portal-card-carousel__footer {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 4;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 1.75rem 0.5rem 0.45rem;
        background: linear-gradient(to top, rgba(15, 45, 20, 0.42), transparent);
        pointer-events: none;
    }
    .portal-card-carousel__dots {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.3rem;
        pointer-events: auto;
    }
    .portal-card-carousel__dot {
        height: 0.4rem;
        width: 0.4rem;
        border: none;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.55);
        padding: 0;
        cursor: pointer;
        transition: width 0.25s ease, background 0.25s ease;
    }
    .portal-card-carousel__dot.is-active {
        width: 1.1rem;
        background: #fff;
    }
    .portal-card-carousel__dot:focus-visible {
        outline: 2px solid #fff;
        outline-offset: 2px;
    }
</style>
@endonce

<div
    class="portal-card-carousel"
    data-portal-card-carousel
    data-interval="5000"
    role="group"
    aria-roledescription="carousel"
    aria-label="{{ $altBase }} gallery"
>
    <div class="portal-card-carousel__viewport">
        @foreach($galleryImages as $index => $imageUrl)
            <div
                class="portal-card-carousel__slide{{ $index === 0 ? ' is-active' : '' }}"
                data-portal-card-slide
                aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
            >
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $altBase }} — image {{ $index + 1 }} of {{ $slideCount }}"
                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                    decoding="async"
                    onerror="this.onerror=null;this.src='{{ $fallbackSrc }}';"
                >
            </div>
        @endforeach
        <a
            href="{{ $detailUrl }}"
            class="portal-card-carousel__hit"
            aria-label="View {{ $accommodation->name }}"
            tabindex="-1"
        ></a>
    </div>

    @if($slideCount > 1)
        <button
            type="button"
            class="portal-card-carousel__btn portal-card-carousel__btn--prev"
            data-portal-card-prev
            aria-label="Previous photo for {{ $accommodation->name }}"
        >
            <i class="fas fa-chevron-left" aria-hidden="true"></i>
        </button>
        <button
            type="button"
            class="portal-card-carousel__btn portal-card-carousel__btn--next"
            data-portal-card-next
            aria-label="Next photo for {{ $accommodation->name }}"
        >
            <i class="fas fa-chevron-right" aria-hidden="true"></i>
        </button>
        <div class="portal-card-carousel__footer">
            <div class="portal-card-carousel__dots" role="tablist" aria-label="Photo thumbnails for {{ $accommodation->name }}">
                @foreach($galleryImages as $index => $imageUrl)
                    <button
                        type="button"
                        class="portal-card-carousel__dot{{ $index === 0 ? ' is-active' : '' }}"
                        data-portal-card-dot="{{ $index }}"
                        role="tab"
                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                        aria-label="Show photo {{ $index + 1 }} of {{ $slideCount }}"
                    ></button>
                @endforeach
            </div>
        </div>
    @endif
</div>

@once('portal-listing-image-carousel-script')
<script>
(function () {
    function initPortalCardCarousel(root) {
        if (!root || root.dataset.portalCardCarouselInit === '1') return;
        root.dataset.portalCardCarouselInit = '1';

        var slides = Array.prototype.slice.call(root.querySelectorAll('[data-portal-card-slide]'));
        if (!slides.length) return;

        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var intervalMs = parseInt(root.getAttribute('data-interval') || '5000', 10);
        var index = 0;
        var timer = null;
        var dots = Array.prototype.slice.call(root.querySelectorAll('[data-portal-card-dot]'));
        var prevBtn = root.querySelector('[data-portal-card-prev]');
        var nextBtn = root.querySelector('[data-portal-card-next]');

        function updateUi() {
            dots.forEach(function (dot, dotIndex) {
                var active = dotIndex === index;
                dot.classList.toggle('is-active', active);
                dot.setAttribute('aria-selected', active ? 'true' : 'false');
            });
        }

        function show(nextIndex) {
            var next = ((nextIndex % slides.length) + slides.length) % slides.length;
            if (next === index) return;
            slides[index].classList.remove('is-active');
            slides[index].setAttribute('aria-hidden', 'true');
            index = next;
            slides[index].classList.add('is-active');
            slides[index].setAttribute('aria-hidden', 'false');
            updateUi();
        }

        function stopAuto() {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        }

        function startAuto() {
            if (reducedMotion || slides.length < 2) return;
            stopAuto();
            timer = window.setInterval(function () {
                show(index + 1);
            }, intervalMs);
        }

        function userGo(nextIndex) {
            show(nextIndex);
            stopAuto();
            startAuto();
        }

        function blockBubble(e) {
            e.stopPropagation();
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function (e) {
                blockBubble(e);
                userGo(index - 1);
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function (e) {
                blockBubble(e);
                userGo(index + 1);
            });
        }
        dots.forEach(function (dot) {
            dot.addEventListener('click', function (e) {
                blockBubble(e);
                var target = parseInt(dot.getAttribute('data-portal-card-dot'), 10);
                if (!Number.isFinite(target)) return;
                userGo(target);
            });
        });

        root.addEventListener('mouseenter', stopAuto);
        root.addEventListener('mouseleave', startAuto);
        root.addEventListener('focusin', function (e) {
            if (root.contains(e.target) && e.target !== root.querySelector('.portal-card-carousel__hit')) {
                stopAuto();
            }
        });
        root.addEventListener('focusout', function (e) {
            if (!root.contains(e.relatedTarget)) {
                startAuto();
            }
        });

        updateUi();
        startAuto();
    }

    function initAll(scope) {
        var nodes = (scope || document).querySelectorAll('[data-portal-card-carousel]');
        Array.prototype.forEach.call(nodes, initPortalCardCarousel);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { initAll(); });
    } else {
        initAll();
    }

    window.PortalListingImageCarousel = { init: initAll };
})();
</script>
@endonce
