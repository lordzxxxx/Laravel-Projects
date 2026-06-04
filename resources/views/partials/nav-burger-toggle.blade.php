@php
    $targetId = $targetId ?? 'appNavbar';
@endphp
<button
    type="button"
    class="nav-toggle"
    aria-label="Toggle navigation"
    aria-expanded="false"
    aria-controls="{{ $targetId }}"
    onclick="var n=document.getElementById('{{ $targetId }}');if(!n)return;var o=n.classList.toggle('nav-open');this.setAttribute('aria-expanded',o?'true':'false');"
>
    <i class="fas fa-bars" aria-hidden="true"></i>
</button>
