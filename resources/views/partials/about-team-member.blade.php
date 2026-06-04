@php
    $displayName = isset($name) && $name !== '' && $name !== null ? $name : '—';
    $cardClass = ! empty($cardClass ?? null)
        ? $cardClass
        : 'overflow-hidden rounded-xl border border-brand-soft bg-white/90 shadow-md backdrop-blur-sm';
@endphp
<article class="{{ $cardClass }}">
    @if (!empty($imageUrl))
        <div class="flex justify-center px-2 pt-3 sm:px-3">
            <div class="aspect-square w-full max-w-40 overflow-hidden rounded-lg bg-neutral-100 sm:max-w-44">
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $displayName !== '—' ? $displayName : $role }}"
                    class="h-full w-full object-contain object-center"
                    loading="lazy"
                >
            </div>
        </div>
    @else
        @php
            $expectedRel = isset($stem) ? (config('about_team.images', [])[$stem] ?? null) : null;
        @endphp
        <div class="flex justify-center px-2 pt-3 sm:px-3">
            <div class="flex aspect-square w-full max-w-40 flex-col items-center justify-center overflow-hidden rounded-lg bg-gradient-to-b from-brand-soft/90 to-white px-3 text-brand-medium sm:max-w-44">
                <i class="fa-solid fa-image mb-1 text-2xl opacity-40"></i>
                <span class="text-center text-[0.7rem] font-semibold">Photo coming soon</span>
                @if ($expectedRel)
                    <span class="mt-1 hidden text-center text-[0.6rem] opacity-80 sm:block">public/{{ $expectedRel }}</span>
                @endif
            </div>
        </div>
    @endif
    <div class="p-3 sm:p-4">
        <p class="text-base font-bold leading-tight text-brand-dark">{{ $displayName }}</p>
        <p class="mt-0.5 text-[0.65rem] font-semibold uppercase tracking-wide text-brand-primary">{{ $role }}</p>
        <p class="mt-2 text-xs leading-snug text-brand-medium">{{ $bio }}</p>
    </div>
</article>
