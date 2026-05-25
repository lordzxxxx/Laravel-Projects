@php
    $guestUrl = $guestUrl ?? route('register.guest');
    $hostUrl = $hostUrl ?? route('register.owner');
    $buttonClass = $buttonClass ?? 'inline-flex items-center gap-2 rounded-lg bg-gradient-to-br from-brand-dark to-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-[0_3px_12px_rgba(46,125,50,0.25)] transition-all hover:opacity-95 hover:shadow-[0_4px_14px_rgba(46,125,50,0.3)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2';
    $menuClass = $menuClass ?? 'absolute right-0 z-30 mt-2 w-72';
@endphp

<details class="register-choice relative">
    <summary class="{{ $buttonClass }} cursor-pointer select-none" aria-haspopup="menu">
        <i class="fas fa-user-plus text-sm" aria-hidden="true"></i>
        <span>Register</span>
        <i class="register-choice-chevron fas fa-chevron-down text-[0.65rem] opacity-80 transition-transform" aria-hidden="true"></i>
    </summary>

    <div class="{{ $menuClass }}">
        <div class="overflow-hidden rounded-2xl border border-emerald-100 bg-white/95 p-2 text-left shadow-[0_18px_45px_-18px_rgba(27,94,32,0.38)] ring-1 ring-emerald-900/[0.04] backdrop-blur-md" role="menu" aria-label="Choose registration type">
            <a href="{{ $guestUrl }}" role="menuitem" class="group flex items-start gap-3 rounded-xl px-3 py-3.5 text-brand-dark transition hover:bg-emerald-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-brand-primary transition group-hover:bg-white" aria-hidden="true">
                    <i class="fas fa-user text-sm"></i>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-extrabold">Guest</span>
                    <span class="mt-0.5 block text-xs font-medium leading-snug text-slate-600">Browse, save, and book verified stays.</span>
                </span>
            </a>
            <a href="{{ $hostUrl }}" role="menuitem" class="group mt-1 flex items-start gap-3 rounded-xl px-3 py-3.5 text-brand-dark transition hover:bg-emerald-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-primary text-white shadow-sm transition group-hover:bg-brand-dark" aria-hidden="true">
                    <i class="fas fa-home text-sm"></i>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-extrabold">Host</span>
                    <span class="mt-0.5 block text-xs font-medium leading-snug text-slate-600">Submit an accredited accommodation application.</span>
                </span>
            </a>
        </div>
    </div>
</details>
