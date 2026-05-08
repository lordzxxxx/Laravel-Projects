<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.tenant-favicon')
    <title>Message Detail - Impasugong Accommodations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @php
            $authUser = auth()->user();
            $isTenantAdmin = $authUser?->isAdmin() && \App\Models\Tenant::checkCurrent();
            $useOwnerNavbar = $authUser?->isOwner() || $isTenantAdmin;
            $useLegacyMessagesNav = ! $useOwnerNavbar && ! $authUser?->isClient() && ! $authUser?->isAdmin();
        @endphp
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            @include('partials.tenant-theme-css-vars')
            --gray-200: #E5E7EB; --gray-500: #6B7280; --gray-600: #4B5563; --gray-700: #374151; --gray-800: #1F2937;
        }

        @if($useLegacyMessagesNav)
        .navbar { background: var(--white); padding: 0 40px; height: 70px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 20px rgba(27, 94, 32, 0.1); position: fixed; width: 100%; top: 0; left: 0; right: 0; z-index: 1000; }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo img { width: 45px; height: 45px; border-radius: 0; border: none; object-fit: contain; }
        .nav-logo span { font-size: 1.2rem; font-weight: 700; color: var(--green-dark); }
        .nav-links { display: flex; gap: 25px; list-style: none; }
        .nav-links a { text-decoration: none; color: var(--gray-600); font-weight: 500; padding: 8px 12px; border-radius: 8px; transition: all 0.3s; }
        .nav-links a:hover, .nav-links a.active { background: var(--green-soft); color: var(--green-dark); }
        .nav-actions { display: flex; gap: 15px; align-items: center; }
        .nav-btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.3s; cursor: pointer; border: none; }
        .nav-btn.primary { background: var(--green-primary); color: var(--white); }
        @endif

        @if($useOwnerNavbar)
            @include('owner.partials.top-navbar-styles')
        @elseif($authUser?->isClient())
            @include('client.partials.top-navbar-styles')
        @elseif($authUser?->isAdmin())
            @include('admin.partials.top-navbar-styles')
        @endif

        body {
            font-family: var(--client-nav-font, 'Segoe UI', system-ui, sans-serif);
            background: linear-gradient(145deg, #ecfdf5 0%, #f8fafc 45%, #f1f5f9 100%);
            min-height: 100vh;
            color: var(--gray-800);
        }

        .main-content {
            max-width: min(920px, 100%);
            margin: 0 auto;
            padding-top: var(--client-nav-offset, 100px);
            padding-left: clamp(16px, 3vw, 24px);
            padding-right: clamp(16px, 3vw, 24px);
            padding-bottom: 48px;
        }

        body.owner-nav-page .main-content.with-owner-nav {
            padding-top: 100px;
        }

        .top-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; gap: 12px; flex-wrap: wrap; }

        @include('partials.messaging-ui-styles')

        @media (max-width: 768px) {
            @if($useLegacyMessagesNav)
            .navbar { padding: 0 20px; height: 60px; }
            .nav-links { display: none; }
            @endif
            .main-content {
                padding-top: calc(var(--client-nav-offset, 100px) - 14px);
                padding-bottom: 32px;
            }
        }
    </style>
</head>
<body class="{{ $useOwnerNavbar ? 'owner-nav-page' : '' }}">
    @if($useOwnerNavbar)
        @include('owner.partials.top-navbar', ['active' => 'messages'])
    @elseif($authUser?->isClient())
        @include('client.partials.top-navbar', ['active' => 'messages'])
    @elseif($authUser?->isAdmin())
        @include('admin.partials.top-navbar', ['active' => 'messages'])
    @else
    @php
        $adminDashboardHref = \App\Models\Tenant::checkCurrent() ? '/owner/dashboard' : '/admin/dashboard';
    @endphp
    <nav class="navbar">
        <a href="{{ $adminDashboardHref }}" class="nav-logo">
            <img src="/SYSTEMLOGO.png" alt="ImpaStay Logo">
            <span>ImpaStay</span>
        </a>
        <ul class="nav-links">
            <li><a href="{{ $adminDashboardHref }}">Dashboard</a></li>
            <li><a href="/messages" class="active">Messages</a></li>
        </ul>
        <div class="nav-actions">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="nav-btn primary">Logout</button>
            </form>
        </div>
    </nav>
    @endif

    @php
        $currentUserId = Auth::id();
        $conversation = collect($thread ?? [])->push($message)->sortBy('created_at')->values();
        $chatPartner = (int) $message->sender_id === (int) $currentUserId ? $message->receiver : $message->sender;
    @endphp

    <main class="main-content {{ $useOwnerNavbar ? 'with-owner-nav' : '' }}">
        <div class="top-actions">
            <a href="{{ route('messages.index', [], false) }}" class="msg-back-link"><i class="fas fa-arrow-left"></i> Back to Messages</a>
            @if(!empty($canDeleteConversation))
                <form method="POST" action="{{ route('messages.destroy', $message, false) }}" class="delete-conversation-form m-0" data-loading-form
                      onsubmit="return confirm('Delete this entire conversation? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" data-loading-button class="msg-btn-danger"><i class="fas fa-trash-alt"></i> Delete conversation</button>
                </form>
            @endif
        </div>
        @include('partials.flash-alerts')

        <section class="msg-chat-shell">
            <div class="msg-chat-head">
                <h1 style="font-size:1.25rem;">{{ $chatPartner->name ?? 'Conversation' }}</h1>
                <p>{{ $chatPartner->email ?? 'Chat thread' }}</p>
            </div>

            <div class="msg-chat-scroll msg-scrollbar">
                @foreach($conversation as $item)
                    @php
                        $isMine = (int) $item->sender_id === (int) $currentUserId;
                        $senderName = $isMine ? 'You' : ($item->sender->name ?? 'User');
                    @endphp
                    <div class="msg-bubble-row {{ $isMine ? 'msg-bubble-row--out' : 'msg-bubble-row--in' }}">
                        @if($isMine)
                            <div>
                                <div class="msg-bubble msg-bubble--out">{{ $item->content }}</div>
                                <div class="msg-bubble-meta">{{ $senderName }} · {{ $item->created_at->format('M d, h:i A') }}</div>
                            </div>
                        @else
                            <div style="display:flex;align-items:flex-end;gap:10px;">
                                <div class="msg-thread-avatar" style="width:36px;height:36px;border-radius:10px;font-size:0.68rem;flex-shrink:0;">
                                    {{ strtoupper(mb_substr($senderName, 0, 1)) }}
                                </div>
                                <div style="min-width:0;">
                                    <div class="msg-bubble msg-bubble--in">{{ $item->content }}</div>
                                    <div class="msg-bubble-meta">{{ $senderName }} · {{ $item->created_at->format('M d, h:i A') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="msg-composer">
                <form method="POST" action="{{ route('messages.reply', $message, false) }}" data-loading-form>
                    @csrf
                    <textarea name="content" required placeholder="Write a reply…"></textarea>
                    <button type="submit" data-loading-button class="msg-btn-primary"><i class="fas fa-paper-plane"></i> Send</button>
                </form>
            </div>
        </section>
    </main>
    <script>
        document.querySelectorAll('form[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const button = form.querySelector('[data-loading-button]');
                if (!button) return;
                button.disabled = true;
                const label = button.textContent.trim().toLowerCase();
                button.innerHTML = label.includes('delete')
                    ? '<i class="fas fa-circle-notch fa-spin"></i> Deleting…'
                    : '<i class="fas fa-circle-notch fa-spin"></i> Sending…';
            });
        });
    </script>
</body>
</html>
