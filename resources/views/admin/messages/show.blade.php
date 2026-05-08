<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.partials.favicon')
    <title>Thread — {{ $tenant->name }} — Central Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(145deg, #ecfdf5 0%, #f0fdf4 35%, #f8fafc 100%);
            min-height: 100vh;
            color: #0f172a;
        }
        .dashboard-layout { padding-top: 82px; }
        .msg-thread-main {
            max-width: min(920px, 100%);
            margin: 0 auto;
            padding-left: clamp(16px, 3vw, 36px);
            padding-right: clamp(16px, 3vw, 36px);
            padding-bottom: 2rem;
        }
        .flash {
            background: #ECFDF5;
            border: 1px solid #86EFAC;
            color: #166534;
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-weight: 600;
        }
        @include('partials.messaging-ui-styles')
        @include('partials.ui-foundation-styles')
        @include('admin.partials.admin-shell-styles')
    </style>
</head>
<body>
    @include('admin.partials.top-navbar', ['active' => 'messages'])

    <div class="dashboard-layout">
        <main class="main-content msg-thread-main">
            <a href="{{ route('admin.messages', [], false) }}" class="msg-back-link"><i class="fas fa-arrow-left"></i> Back to inbox</a>

            @include('partials.flash-alerts')

            <div class="msg-chat-shell">
                <div class="msg-chat-head">
                    <h1>{{ $message->subject ?: 'Message' }}</h1>
                    <p>
                        <strong style="color:#334155;">{{ $tenant->name }}</strong>
                        <span style="color:#cbd5e1;">·</span>
                        Replying as <strong style="color:#115e59;">ImpaStay (Central Admin)</strong>
                    </p>
                </div>
                <div class="msg-chat-scroll msg-scrollbar">
                    @foreach ($timeline as $m)
                        @php
                            $fromCentral = (int) $m->sender_id === (int) $proxy->id;
                        @endphp
                        <div class="msg-bubble-row {{ $fromCentral ? 'msg-bubble-row--out' : 'msg-bubble-row--in' }}">
                            <div>
                                <div class="msg-bubble {{ $fromCentral ? 'msg-bubble--out' : 'msg-bubble--in' }}">{{ $m->content }}</div>
                                <div class="msg-bubble-meta">
                                    {{ $fromCentral ? 'ImpaStay (Central Admin)' : ($m->sender->name ?? 'User') }}
                                    · {{ $m->created_at->format('M j, g:i A') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="msg-composer">
                    <form method="POST" action="{{ route('admin.messages.support-reply', ['tenant' => $tenant->getKey(), 'message' => $message->getKey()], false) }}" data-loading-form>
                        @csrf
                        <textarea name="content" required placeholder="Write a reply…"></textarea>
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
