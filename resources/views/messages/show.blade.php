<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Conversation — {{ $message->subject ?: 'Messages' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @include('owner.partials.owner-page-fonts')
        @php
            $authUser = auth()->user();
            $isTenantAdmin = $authUser?->isAdmin() && \App\Models\Tenant::checkCurrent();
            $useOwnerNavbar = $authUser?->isOwner() || $isTenantAdmin;
            $useClientNavbar = $authUser?->isClient();
            $useLegacyMessagesNav = ! $useOwnerNavbar && ! $useClientNavbar && ! $authUser?->isAdmin();
        @endphp

        @if($useOwnerNavbar)
            @include('owner.partials.top-navbar-styles')
        @elseif($useClientNavbar)
            @include('client.partials.top-navbar-styles')
        @elseif($authUser?->isAdmin())
            @include('admin.partials.top-navbar-styles')
        @endif

        @if($useLegacyMessagesNav)
        :root {
            @include('partials.tenant-theme-css-vars')
        }
        .navbar {
            background: var(--app-surface-bg, #fff);
            padding: 0 40px;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm, 0 2px 20px rgba(27, 94, 32, 0.1));
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo img { width: 45px; height: 45px; object-fit: contain; }
        .nav-links { display: flex; gap: 25px; list-style: none; }
        .nav-links a {
            text-decoration: none;
            color: var(--ink-600, #4b5563);
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 8px;
        }
        .nav-links a:hover,
        .nav-links a.active {
            background: var(--brand-50, #ecfdf5);
            color: var(--brand-800, #14532d);
        }
        .nav-actions { display: flex; gap: 15px; align-items: center; }
        .nav-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            background: var(--brand-700, #15803d);
            color: #fff;
        }
        @media (max-width: 768px) {
            .navbar { padding: 0 20px; height: 60px; }
            .nav-links { display: none; }
        }
        @endif

        @include('partials.messaging-ui-styles')
    </style>
</head>
@php
    $bodyClasses = collect([
        'msg-thread-page',
        $useOwnerNavbar ? 'owner-nav-page' : '',
        $useClientNavbar ? 'client-nav-page font-sans text-gray-800' : '',
    ])->filter()->implode(' ');

    $mainClasses = collect([
        'messages-show-main',
        'main-content',
        $useOwnerNavbar ? 'with-owner-nav' : '',
    ])->filter()->implode(' ');

    $currentUserId = Auth::id();
    $conversation = collect($thread ?? [])->push($message)->sortBy('created_at')->values();
    $chatPartner = (int) $message->sender_id === (int) $currentUserId ? $message->receiver : $message->sender;
    $partnerName = $chatPartner->name ?? 'Conversation';
    $partnerInitial = strtoupper(mb_substr($partnerName, 0, 1));
    $messageCount = $conversation->count();
@endphp
<body class="{{ $bodyClasses }}">
    @if($useOwnerNavbar)
        @include('owner.partials.top-navbar', ['active' => 'messages'])
    @elseif($useClientNavbar)
        @include('client.partials.top-navbar', ['active' => 'messages'])
    @elseif($authUser?->isAdmin())
        @include('admin.partials.top-navbar', ['active' => 'messages'])
    @else
        @php
            $adminDashboardHref = \App\Models\Tenant::checkCurrent() ? '/owner/dashboard' : '/admin/dashboard';
        @endphp
        <nav class="navbar">
            <a href="{{ $adminDashboardHref }}" class="nav-logo">
                <img src="/SYSTEMLOGO.png" alt="IMPASUGONG TOURISM">
                <span>IMPASUGONG TOURISM</span>
            </a>
            <ul class="nav-links">
                <li><a href="{{ $adminDashboardHref }}">Dashboard</a></li>
                <li><a href="/messages" class="active">Messages</a></li>
            </ul>
            <div class="nav-actions">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
            </div>
        </nav>
    @endif

    <main class="{{ $mainClasses }}">
        <div class="msg-thread-toolbar">
            <header class="page-header">
                <h1>
                    <span class="page-title-icon"><i class="fa-solid fa-comments"></i></span>
                    <span>Conversation</span>
                </h1>
                <p>
                    @if($message->subject)
                        {{ $message->subject }}
                    @else
                        Direct message with {{ $partnerName }}
                    @endif
                </p>
            </header>
            <div class="msg-thread-toolbar__actions">
                <a href="{{ route('messages.index', [], false) }}" class="msg-back-link">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to inbox
                </a>
                @if(!empty($canDeleteConversation))
                    <form
                        method="POST"
                        action="{{ route('messages.destroy', $message, false) }}"
                        class="delete-conversation-form m-0"
                        data-loading-form
                        onsubmit="return confirm('Delete this entire conversation? This cannot be undone.');"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" data-loading-button class="msg-btn-danger">
                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @include('partials.flash-alerts')

        <section class="msg-chat-shell" aria-label="Message thread">
            <div class="msg-chat-head">
                <div class="msg-chat-head__row">
                    <div class="msg-thread-avatar msg-thread-avatar--lg" aria-hidden="true">{{ $partnerInitial }}</div>
                    <div class="msg-chat-head__meta">
                        <h2>{{ $partnerName }}</h2>
                        <p>
                            @if($chatPartner->email ?? null)
                                {{ $chatPartner->email }}
                            @else
                                Chat participant
                            @endif
                            <span aria-hidden="true"> · </span>
                            {{ $messageCount }} {{ $messageCount === 1 ? 'message' : 'messages' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="msg-chat-scroll msg-scrollbar" id="msg-thread-scroll">
                @forelse($conversation as $item)
                    @php
                        $isMine = (int) $item->sender_id === (int) $currentUserId;
                        $senderName = $isMine ? 'You' : ($item->sender->name ?? 'User');
                    @endphp
                    <div class="msg-bubble-row {{ $isMine ? 'msg-bubble-row--out' : 'msg-bubble-row--in' }}">
                        @if($isMine)
                            <div class="msg-bubble-row__out">
                                @include('partials.message-bubble-body', ['message' => $item, 'bubble' => 'out'])
                                <div class="msg-bubble-meta">{{ $senderName }} · {{ $item->created_at->format('M j, g:i A') }}</div>
                            </div>
                        @else
                            <div class="msg-bubble-row__in">
                                <div class="msg-thread-avatar" aria-hidden="true">
                                    {{ strtoupper(mb_substr($senderName, 0, 1)) }}
                                </div>
                                <div style="min-width:0;">
                                    @include('partials.message-bubble-body', ['message' => $item, 'bubble' => 'in'])
                                    <div class="msg-bubble-meta">{{ $senderName }} · {{ $item->created_at->format('M j, g:i A') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-sm text-slate-500 py-8">No messages in this thread yet.</p>
                @endforelse
            </div>

            <div class="msg-composer">
                <form method="POST" action="{{ route('messages.reply', $message, false) }}" data-loading-form enctype="multipart/form-data">
                    @csrf
                    <label class="msg-field-label sr-only" for="reply_content">Reply</label>
                    <textarea id="reply_content" name="content" rows="3" placeholder="Write a reply…">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="msg-error" style="margin-bottom:0.5rem;">{{ $errors->first('content') }}</div>
                    @enderror
                    <div class="msg-file-field">
                        <label class="msg-field-label" for="reply_attachment">
                            Photo <span class="msg-label-optional">(optional, JPG/PNG/WEBP up to 5MB)</span>
                        </label>
                        <input
                            type="file"
                            name="attachment"
                            id="reply_attachment"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="msg-file-input"
                            data-image-preview="reply-preview"
                        >
                        @error('attachment')
                            <div class="msg-error">{{ $errors->first('attachment') }}</div>
                        @enderror
                        <div class="msg-preview-thumb" id="reply-preview" aria-hidden="true"></div>
                    </div>
                    <div class="msg-composer__footer">
                        <p class="text-xs text-slate-500 m-0">Replies are sent instantly to this conversation.</p>
                        <button type="submit" data-loading-button class="msg-btn-primary">
                            <i class="fas fa-paper-plane" aria-hidden="true"></i>
                            Send reply
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    @include('partials.message-attachment-preview-script')
    <script>
        (function () {
            var scroll = document.getElementById('msg-thread-scroll');
            if (scroll) {
                scroll.scrollTop = scroll.scrollHeight;
            }

            document.querySelectorAll('form[data-loading-form]').forEach(function (form) {
                form.addEventListener('submit', function () {
                    var button = form.querySelector('[data-loading-button]');
                    if (!button) return;
                    button.disabled = true;
                    var label = button.textContent.trim().toLowerCase();
                    button.innerHTML = label.includes('delete')
                        ? '<i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i> Deleting…'
                        : '<i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i> Sending…';
                });
            });
        })();
    </script>
</body>
</html>
