<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.partials.favicon')
    <title>Messaging — Central Admin</title>
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
        .page-header { margin-bottom: 1.25rem; }
        .page-header p { max-width: 62ch; color: #64748b; font-size: 0.9375rem; line-height: 1.55; }
        .flash {
            background: #ECFDF5;
            border: 1px solid #86EFAC;
            color: #166534;
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-weight: 600;
        }
        .msg-field-stack > * + * {
            margin-top: 1rem;
        }
        .msg-error {
            color: #b91c1c;
            font-size: 0.8125rem;
            margin-top: 0.35rem;
        }
        @include('partials.messaging-ui-styles')
        @include('partials.ui-foundation-styles')
        @include('admin.partials.admin-shell-styles')
    </style>
</head>
<body>
    @include('admin.partials.top-navbar', ['active' => 'messages'])

    <div class="dashboard-layout">
        <main class="main-content msg-admin-main">
            <div class="page-header">
                <h1>
                    <span class="page-title-icon"><i class="fa-solid fa-comment-dots"></i></span>
                    <span>Tulogan messaging</span>
                </h1>
                <p>
                    Conversations where a tulogan messaged <strong class="text-slate-800">ImpaStay (Central Admin)</strong>, or you started a thread from here.
                    The inbox shows <strong class="text-slate-800">one row per person</strong> per tulogan (latest message in that thread).
                </p>
            </div>

            @include('partials.flash-alerts')

            <div class="msg-admin-layout">
                <div class="msg-compose-sticky">
                    <section class="msg-card">
                        <div class="msg-card-header">
                            <h2><i class="fas fa-paper-plane"></i> New message</h2>
                        </div>
                        <div class="msg-card-body">
                            <form method="POST" action="{{ route('admin.messages.contact', [], false) }}" class="msg-field-stack" data-loading-form>
                                @csrf
                                <div>
                                    <label class="msg-field-label" for="tenant_id">Tulogan</label>
                                    <select name="tenant_id" id="tenant_id" required class="msg-select">
                                        <option value="">Choose a tulogan…</option>
                                        @foreach ($tenants as $t)
                                            <option value="{{ $t->id }}" @selected(old('tenant_id') == $t->id)>{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('tenant_id')
                                        <div class="msg-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="msg-field-label" for="subject">Subject <span style="font-weight:500;color:#94a3b8;">(optional)</span></label>
                                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" maxlength="255" class="msg-input" placeholder="Support request…">
                                </div>
                                <div>
                                    <label class="msg-field-label" for="content">Message</label>
                                    <textarea name="content" id="content" required class="msg-textarea" placeholder="Write your message…">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="msg-error">{{ $message }}</div>
                                    @enderror
                                    <p class="msg-field-hint" style="margin-top:0.65rem;">
                                        Recipient is chosen automatically from the tulogan’s active contacts (owner/admin first)—no email field required.
                                    </p>
                                </div>
                                <button type="submit" data-loading-button class="msg-btn-primary" style="width:100%;">
                                    <i class="fas fa-paper-plane"></i> Send
                                </button>
                            </form>
                        </div>
                    </section>
                </div>

                <section class="msg-card" style="min-height: 280px;">
                    <div class="msg-card-header" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:0.75rem;">
                        <h2 style="margin:0;"><i class="fas fa-inbox"></i> Inbox</h2>
                        @if(!$paginator->isEmpty())
                            <span class="msg-thread-meta" style="font-weight:650;color:#475569;">
                                {{ $paginator->total() }} conversation{{ $paginator->total() === 1 ? '' : 's' }}
                            </span>
                        @endif
                    </div>

                    @if ($paginator->isEmpty())
                        <div class="msg-empty">
                            <div class="msg-empty-icon" aria-hidden="true"><i class="fas fa-comments"></i></div>
                            <p><strong class="text-slate-700">No threads yet.</strong><br>
                                When a tulogan writes to ImpaStay (Central Admin), it will appear here.</p>
                        </div>
                    @else
                        <div class="msg-thread-list msg-scrollbar" style="max-height: min(68vh, 720px); overflow-y: auto;">
                            @foreach ($paginator as $row)
                                <a
                                    href="{{ route('admin.messages.thread', ['tenant' => $row->tenant_id, 'message' => $row->message_id], false) }}"
                                    class="msg-thread-item {{ $row->is_unread ? 'msg-thread-item-unread' : '' }}"
                                >
                                    @php
                                        $name = trim((string) $row->counterpart_name);
                                        $parts = array_values(array_filter(preg_split('/\s+/u', $name) ?: []));
                                        $initials = '';
                                        foreach (array_slice($parts, 0, 2) as $w) {
                                            $initials .= mb_strtoupper(mb_substr($w, 0, 1));
                                        }
                                        $initials = $initials !== '' ? $initials : 'U';
                                    @endphp
                                    <div class="msg-thread-avatar" aria-hidden="true">{{ $initials }}</div>
                                    <div class="msg-thread-body" style="min-width:0;">
                                        <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:0.5rem;margin-bottom:0.2rem;">
                                            @if ($row->is_unread)
                                                <span class="msg-dot-unread" title="Unread"></span>
                                            @endif
                                            <span class="msg-thread-meta">{{ $row->created_at->format('M j, Y · g:i A') }}</span>
                                        </div>
                                        <div class="msg-thread-tenant">{{ $row->tenant_name }}</div>
                                        <div class="msg-thread-title">{{ $row->counterpart_name }}</div>
                                        @if ($row->subject)
                                            <div style="font-size:0.8125rem;font-weight:650;color:#334155;margin-bottom:0.2rem;">{{ $row->subject }}</div>
                                        @endif
                                        <div class="msg-thread-preview">{{ $row->preview }}</div>
                                    </div>
                                    <span class="msg-thread-open">
                                        Open <i class="fas fa-chevron-right" style="font-size:0.65rem;opacity:0.65;"></i>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                        <div class="msg-pagination">
                            {{ $paginator->links() }}
                        </div>
                    @endif
                </section>
            </div>
        </main>
    </div>
    <script>
        document.querySelectorAll('form[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const button = form.querySelector('[data-loading-button]');
                if (!button) return;
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Sending…';
            });
        });
    </script>
</body>
</html>
