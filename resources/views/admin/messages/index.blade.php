<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('admin.partials.favicon')
    <title>Messaging — Central Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <style>
        @include('partials.ui-foundation-styles')
        @include('admin.partials.admin-shell-styles')
        @include('partials.messaging-ui-styles')
    </style>
</head>
<body class="admin-central-portal msg-admin-page">
    @include('admin.partials.top-navbar', ['active' => 'messages'])

    <div class="dashboard-layout">
        <main class="main-content msg-admin-main">
            <div class="page-header">
                <h1>
                    <span class="page-title-icon"><i class="fa-solid fa-comment-dots"></i></span>
                    <span>Messaging</span>
                </h1>
                <p>One row per contact per tulogan. Compose on the left, open threads from the inbox.</p>
            </div>

            @include('partials.flash-alerts')

            <div class="msg-admin-workspace">
                <div class="msg-admin-layout">
                    <div class="msg-compose-sticky msg-compose-panel">
                        <section class="msg-card">
                            <div class="msg-card-header">
                                <h2><i class="fas fa-paper-plane"></i> New message</h2>
                            </div>
                            <div class="msg-card-body">
                                <form method="POST" action="{{ route('admin.messages.contact', [], false) }}" class="msg-field-stack" data-loading-form enctype="multipart/form-data">
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
                                        <label class="msg-field-label" for="subject">Subject <span class="msg-label-optional">(optional)</span></label>
                                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" maxlength="255" class="msg-input" placeholder="Support request…">
                                    </div>
                                    <div>
                                        <label class="msg-field-label" for="content">Message</label>
                                        <textarea name="content" id="content" class="msg-textarea" placeholder="Write your message…">{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="msg-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="msg-file-field">
                                        <label class="msg-field-label" for="attachment">Photo <span class="msg-label-optional">(optional, JPG/PNG/WEBP up to 5MB)</span></label>
                                        <input type="file" name="attachment" id="attachment" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="msg-file-input" data-image-preview="compose-preview">
                                        @error('attachment')
                                            <div class="msg-error">{{ $message }}</div>
                                        @enderror
                                        <div class="msg-preview-thumb" id="compose-preview" aria-hidden="true"></div>
                                        <p class="msg-field-hint">Add a message, a photo, or both. Recipient is chosen from the tulogan’s active contacts.</p>
                                    </div>
                                    <button type="submit" data-loading-button class="msg-btn-primary" style="width:100%;">
                                        <i class="fas fa-paper-plane"></i> Send
                                    </button>
                                </form>
                            </div>
                        </section>
                    </div>

                    <section class="msg-card msg-inbox-panel">
                        <div class="msg-card-header msg-card-header-row">
                            <h2><i class="fas fa-inbox"></i> Inbox</h2>
                            @if(!$paginator->isEmpty())
                                <span class="msg-inbox-count">{{ $paginator->total() }} {{ Str::plural('conversation', $paginator->total()) }}</span>
                            @endif
                        </div>

                        <div class="msg-inbox-body">
                            @if ($paginator->isEmpty())
                                <div class="msg-empty">
                                    <div class="msg-empty-icon" aria-hidden="true"><i class="fas fa-comments"></i></div>
                                    <p><strong class="text-slate-700">No threads yet</strong></p>
                                    <p class="mt-1 text-sm">When a tulogan messages ImpaStay (Central Admin), it appears here.</p>
                                </div>
                            @else
                                <div class="msg-thread-list msg-thread-list--fill msg-scrollbar">
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
                                            <div class="msg-thread-body">
                                                <div class="msg-thread-tenant">{{ $row->tenant_name }}</div>
                                                <div class="msg-thread-top">
                                                    <span class="msg-thread-title">
                                                        @if ($row->is_unread)
                                                            <span class="msg-dot-unread" title="Unread" style="display:inline-block;vertical-align:middle;margin-right:6px;"></span>
                                                        @endif
                                                        {{ $row->counterpart_name }}
                                                    </span>
                                                    <span class="msg-thread-meta">{{ $row->created_at->format('M j · g:i A') }}</span>
                                                </div>
                                                @if ($row->subject)
                                                    <div class="msg-thread-subject">{{ $row->subject }}</div>
                                                @endif
                                                <div class="msg-thread-preview">{{ $row->preview }}</div>
                                            </div>
                                            <i class="fas fa-chevron-right msg-thread-chevron" aria-hidden="true"></i>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="msg-pagination">
                                    {{ $paginator->links() }}
                                </div>
                            @endif
                        </div>
                    </section>
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
                button.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Sending…';
            });
        });
    </script>
</body>
</html>
