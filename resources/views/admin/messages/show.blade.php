<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('admin.partials.favicon')
    <title>Thread — {{ $tenant->name }} — Central Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <style>
        @include('partials.ui-foundation-styles')
        @include('admin.partials.admin-shell-styles')
        @include('partials.messaging-ui-styles')
    </style>
</head>
<body class="admin-central-portal msg-thread-page">
    @include('admin.partials.top-navbar', ['active' => 'messages'])

    <div class="dashboard-layout">
        <main class="main-content msg-thread-main">
            <a href="{{ route('admin.messages', [], false) }}" class="msg-back-link"><i class="fas fa-arrow-left"></i> Inbox</a>

            @include('partials.flash-alerts')

            <div class="msg-chat-shell">
                <div class="msg-chat-head">
                    <h1>{{ $message->subject ?: 'Message' }}</h1>
                    <p>
                        <strong>{{ $tenant->name }}</strong>
                        <span aria-hidden="true">·</span>
                        Replying as <strong>ImpaStay (Central Admin)</strong>
                    </p>
                </div>
                <div class="msg-chat-scroll msg-scrollbar">
                    @foreach ($timeline as $m)
                        @php
                            $fromCentral = (int) $m->sender_id === (int) $proxy->id;
                        @endphp
                        <div class="msg-bubble-row {{ $fromCentral ? 'msg-bubble-row--out' : 'msg-bubble-row--in' }}">
                            <div>
                                @include('partials.message-bubble-body', ['message' => $m, 'bubble' => $fromCentral ? 'out' : 'in'])
                                <div class="msg-bubble-meta">
                                    {{ $fromCentral ? 'ImpaStay (Central Admin)' : ($m->sender->name ?? 'User') }}
                                    · {{ $m->created_at->format('M j, g:i A') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="msg-composer">
                    <form method="POST" action="{{ route('admin.messages.support-reply', ['tenant' => $tenant->getKey(), 'message' => $message->getKey()], false) }}" data-loading-form enctype="multipart/form-data">
                        @csrf
                        <textarea name="content" placeholder="Write a reply…">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="msg-error">{{ $message }}</div>
                        @enderror
                        <div class="msg-file-field" style="margin-bottom:0.75rem;">
                            <label class="msg-field-label" for="reply_attachment">Photo <span class="msg-label-optional">(optional)</span></label>
                            <input type="file" name="attachment" id="reply_attachment" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="msg-file-input" data-image-preview="reply-preview">
                            @error('attachment')
                                <div class="msg-error">{{ $message }}</div>
                            @enderror
                            <div class="msg-preview-thumb" id="reply-preview" aria-hidden="true"></div>
                        </div>
                        <button type="submit" data-loading-button class="msg-btn-primary">
                            <i class="fas fa-reply"></i> Send reply
                        </button>
                    </form>
                    <div class="msg-thread-actions">
                        <form method="POST" action="{{ route('admin.messages.destroy', ['tenant' => $tenant->getKey(), 'message' => $message->getKey()], false) }}"
                              onsubmit="return confirm('Delete this entire support thread? This cannot be undone.');" data-loading-form>
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-loading-button class="msg-btn-danger"><i class="fas fa-trash-alt"></i> Delete thread</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @include('partials.message-attachment-preview-script')
    <script>
        document.querySelectorAll('form[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const button = form.querySelector('[data-loading-button]');
                if (!button) return;
                button.disabled = true;
                const label = button.textContent.trim().toLowerCase();
                if (label.includes('delete')) {
                    button.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Deleting…';
                } else {
                    button.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Sending…';
                }
            });
        });
    </script>
</body>
</html>
