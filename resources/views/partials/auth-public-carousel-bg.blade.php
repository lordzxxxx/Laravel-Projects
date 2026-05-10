@php
    $authCarouselSlides = [
        asset('adminbg.jpg'),
        asset('adminbg2.jpeg'),
        asset('bg22.jpg'),
        asset('COMMUNAL.jpg'),
    ];
@endphp
@once('auth-public-carousel-styles')
<style>
    .auth-public-carousel-root {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
        isolation: isolate;
    }
    .auth-public-carousel-root__slides {
        position: absolute;
        inset: 0;
        z-index: 0;
    }
    .auth-public-carousel-root__slide {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transform: scale(1.045);
        filter: blur(5px);
        transition: opacity 1.05s ease-in-out;
        will-change: opacity;
    }
    .auth-public-carousel-root__slide.is-active {
        opacity: 1;
        z-index: 1;
    }
    .auth-public-carousel-root__scrim {
        position: absolute;
        inset: 0;
        z-index: 2;
        background: rgba(255, 255, 255, 0.6);
    }
</style>
@endonce

<div class="auth-public-carousel-root" aria-hidden="true">
    <div class="auth-public-carousel-root__slides">
        @foreach ($authCarouselSlides as $i => $src)
            <div
                class="auth-public-carousel-root__slide{{ $i === 0 ? ' is-active' : '' }}"
                data-auth-carousel-slide
                role="presentation"
                style="background-image: url('{{ e($src) }}');"
            ></div>
        @endforeach
    </div>
    <div class="auth-public-carousel-root__scrim" role="presentation"></div>
</div>

@once('auth-public-carousel-script')
<script>
(function () {
    var slides = document.querySelectorAll('[data-auth-carousel-slide]');
    if (!slides.length) return;
    var i = 0;
    var period = 3500;
    var reduce = typeof window.matchMedia === 'function' && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    function show(next) {
        slides[i].classList.remove('is-active');
        i = next;
        slides[i].classList.add('is-active');
    }
    if (!reduce) {
        setInterval(function () {
            show((i + 1) % slides.length);
        }, period);
    }
})();
</script>
@endonce
