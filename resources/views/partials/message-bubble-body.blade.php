@php
    $bubble = $bubble ?? 'in';
    $hasText = filled($message->content ?? null);
    $attachmentUrl = $message->attachment_url ?? null;
@endphp

@if($hasText)
    <div class="msg-bubble msg-bubble--{{ $bubble }}">{{ $message->content }}</div>
@endif

@if($attachmentUrl)
    <a
        href="{{ $attachmentUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="msg-bubble-attachment {{ $hasText ? 'msg-bubble-attachment--stacked' : '' }}"
    >
        <img src="{{ $attachmentUrl }}" alt="Attached photo" loading="lazy" decoding="async">
    </a>
@endif
