<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Messages - Impasugong Accommodations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @include('owner.partials.owner-page-fonts')
        @php
            $authUser = auth()->user();
            $isTenantAdmin = $authUser?->isAdmin() && \App\Models\Tenant::checkCurrent();
            $useOwnerNavbar = $authUser?->isOwner() || $isTenantAdmin;
            $useLegacyMessagesNav = ! $useOwnerNavbar && ! $authUser?->isClient() && ! $authUser?->isAdmin();
            $showComposeButton = $useOwnerNavbar || ($authUser?->isClient() && \App\Models\Tenant::checkCurrent());
        @endphp
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            @include('partials.tenant-theme-css-vars')
            --gray-500: #6B7280; --gray-600: #4B5563; --gray-700: #374151;
            --gray-800: #1F2937;
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
        .nav-btn.secondary { background: var(--green-soft); color: var(--green-dark); }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--green-primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; }
        @endif

        @if($useOwnerNavbar)
            @include('owner.partials.top-navbar-styles')
        @elseif($authUser?->isClient())
            @include('client.partials.top-navbar-styles')
            @include('client.partials.guest-messages-styles')
        @elseif($authUser?->isAdmin())
            @include('admin.partials.top-navbar-styles')
        @endif

        @media (max-width: 768px) {
            @if($useLegacyMessagesNav)
            .navbar { padding: 0 20px; height: 60px; }
            .nav-links { display: none; }
            @endif
        }

        @include('partials.messaging-ui-styles')

        /* Messages index — dark mode (owner split view) */
        html.dark body.owner-nav-page .messages-split .msg-scrollbar[class*="bg-slate-100"] {
            background: var(--app-surface-muted-bg) !important;
        }

        html.dark body.owner-nav-page .messages-split .text-slate-600,
        html.dark body.owner-nav-page .messages-split .text-slate-500 {
            color: var(--text-muted, var(--ink-600)) !important;
        }

        html.dark body.owner-nav-page .messages-split .text-slate-700 {
            color: var(--text-secondary, var(--ink-700)) !important;
        }

        html.dark body.owner-nav-page .messages-split .text-slate-900 {
            color: var(--ink-900) !important;
        }

        html.dark body.owner-nav-page .messages-split textarea::placeholder {
            color: var(--ink-400) !important;
            opacity: 1;
        }

        /* Messages index: full-width shell for owner/admin */
        body.owner-nav-page main.messages-index-main.main-content.with-owner-nav {
            max-width: none !important;
            width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding-left: clamp(8px, 1vw, 16px) !important;
            padding-right: clamp(8px, 1vw, 16px) !important;
            padding-bottom: clamp(6px, 1vw, 12px) !important;
        }
    </style>
</head>
<body class="{{ $useOwnerNavbar ? 'owner-nav-page' : ($authUser?->isClient() ? 'client-nav-page font-sans text-gray-800' : 'min-h-screen bg-gradient-to-br from-green-50 via-lime-50 to-white text-gray-800') }}">
    @if($useOwnerNavbar)
        @include('owner.partials.top-navbar', ['active' => 'messages'])
    @elseif($authUser?->isClient())
        @include('client.partials.top-navbar', ['active' => 'messages'])
    @elseif($authUser?->isAdmin())
        @include('admin.partials.top-navbar', ['active' => 'messages'])
    @else
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="nav-logo">
            <img src="/SYSTEMLOGO.png" alt="ImpaStay Logo">
            <span>Impasugong</span>
        </a>

        <ul class="nav-links">
            @auth
                @if(Auth::user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">Dashboard</a></li>
                @else
                    <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Browse</a></li>
                @endif
            @endauth
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') || request()->routeIs('accommodations.*') ? 'active' : '' }}">Browse</a></li>
            @if(! Auth::user()->isClient() || Auth::user()->tenantClientMayManageOwnStays())
                <li><a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.*') ? 'active' : '' }}">My Bookings</a></li>
            @endif
            @if(! Auth::user()->isClient() || Auth::user()->tenantClientMayUseMessaging())
                <li><a href="{{ route('messages.index', [], false) }}" class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">Messages</a></li>
            @endif
            @if(! Auth::user()->isClient() || Auth::user()->tenantClientMayEditOwnProfile())
                <li><a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Settings</a></li>
            @endif
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
        $isGuestClient = $authUser?->isClient() && ! $useOwnerNavbar;
    @endphp

    @if($isGuestClient)
    <main class="messages-index-main client-guest-main client-guest-main--full guest-messages-main">
        <header class="guest-messages-hero">
            <div class="guest-messages-hero__copy">
                <p class="guest-messages-hero__eyebrow">Inbox</p>
                <h1 class="guest-messages-hero__title">Messages</h1>
                <p class="guest-messages-hero__lede">Pick a thread or start a new conversation with the host or admin.</p>
            </div>
            @if($showComposeButton)
                <a href="{{ route('messages.create', [], false) }}" class="guest-messages-hero__cta">
                    <i class="fas fa-plus" aria-hidden="true"></i> New
                </a>
            @endif
        </header>

        @include('partials.flash-alerts')

        @if(isset($messages) && count($messages) > 0)
            <div class="guest-messages-workspace">
                <aside class="guest-messages-inbox" aria-label="Inbox">
                    <div class="guest-messages-inbox__head">
                        <h2 class="guest-messages-inbox__title">Threads</h2>
                        @if(($unreadCount ?? 0) > 0)
                            <form method="POST" action="{{ route('messages.mark-all-read', [], false) }}" class="m-0" data-loading-form>
                                @csrf
                                <button type="submit" data-loading-button class="guest-messages-mark-read">Mark all read</button>
                            </form>
                        @endif
                    </div>
                    <div class="guest-messages-thread-list msg-scrollbar">
                        @foreach($messages as $message)
                            @php
                                $otherParty = (int) $message->sender_id === (int) Auth::id()
                                    ? $message->receiver
                                    : $message->sender;
                                $otherId = (int) ($otherParty->id ?? 0);
                                $selectedOtherId = $selectedMessage
                                    ? ((int) $selectedMessage->sender_id === (int) Auth::id()
                                        ? (int) $selectedMessage->receiver_id
                                        : (int) $selectedMessage->sender_id)
                                    : null;
                                $isActiveThread = $selectedOtherId !== null && $otherId === $selectedOtherId;
                                $hasUnreadFromPartner = $otherId > 0 && ($unreadByPartner[$otherId] ?? false);
                            @endphp
                            <a
                                href="{{ url('/messages') }}?partner={{ $otherId }}{{ request()->get('page') ? '&page='.(int) request()->get('page') : '' }}"
                                class="guest-messages-thread {{ $isActiveThread ? 'is-active' : '' }} {{ $hasUnreadFromPartner ? 'is-unread' : '' }}"
                            >
                                <div class="guest-messages-thread__avatar">{{ strtoupper(substr($otherParty->name ?? 'U', 0, 2)) }}</div>
                                <div class="guest-messages-thread__body">
                                    <div class="guest-messages-thread__row">
                                        <span class="guest-messages-thread__name">{{ $otherParty->name ?? 'Unknown' }}</span>
                                        <span class="guest-messages-thread__time">{{ $message->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="guest-messages-thread__subject">{{ $message->subject ?? 'No subject' }}</div>
                                    <div class="guest-messages-thread__preview">{{ Str::limit($message->excerpt, 56) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </aside>

                @php
                    $currentUserId = Auth::id();
                    $chatPartner = $selectedMessage
                        ? ((int) $selectedMessage->sender_id === (int) $currentUserId ? $selectedMessage->receiver : $selectedMessage->sender)
                        : null;
                @endphp

                <section class="guest-messages-chat" aria-label="Conversation">
                    <div class="guest-messages-chat__head">
                        <div class="guest-messages-chat__head-inner">
                            <h2 class="guest-messages-chat__title">{{ $chatPartner->name ?? 'Conversation' }}</h2>
                            <p class="guest-messages-chat__subtitle">{{ $chatPartner->email ?? 'Select a thread from the inbox.' }}</p>
                        </div>
                        @if($canDeleteSelectedConversation)
                            <div class="guest-messages-chat__head-actions">
                                <form
                                    method="POST"
                                    action="{{ route('messages.destroy', $selectedMessage, false) }}"
                                    class="m-0"
                                    onsubmit="return confirm('Delete this entire conversation? This cannot be undone.');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="guest-messages-delete">
                                        <i class="fas fa-trash-alt" aria-hidden="true"></i> Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="guest-messages-chat__stream msg-scrollbar">
                        @forelse($conversationMessages as $chatMessage)
                            @php
                                $isMine = (int) $chatMessage->sender_id === (int) $currentUserId;
                                $senderName = $isMine ? 'You' : ($chatMessage->sender->name ?? 'User');
                            @endphp
                            <div class="guest-messages-bubble-row {{ $isMine ? 'guest-messages-bubble-row--mine' : '' }}">
                                @if(! $isMine)
                                    <div class="guest-messages-bubble-row__avatar">{{ strtoupper(substr($senderName, 0, 1)) }}</div>
                                @endif
                                <div class="guest-messages-bubble-wrap">
                                    @if(filled($chatMessage->content))
                                        <div class="guest-messages-bubble {{ $isMine ? 'guest-messages-bubble--mine' : 'guest-messages-bubble--theirs' }}">
                                            {{ $chatMessage->content }}
                                        </div>
                                    @endif
                                    @if($chatMessage->attachment_url)
                                        <a href="{{ $chatMessage->attachment_url }}" target="_blank" rel="noopener noreferrer" class="msg-bubble-attachment {{ filled($chatMessage->content) ? 'msg-bubble-attachment--stacked' : '' }} {{ $isMine ? 'ml-auto' : '' }}">
                                            <img src="{{ $chatMessage->attachment_url }}" alt="Attached photo" loading="lazy" decoding="async">
                                        </a>
                                    @endif
                                    <p class="guest-messages-bubble-meta">
                                        {{ $senderName }} · {{ $chatMessage->created_at->format('M d, h:i A') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="guest-messages-chat__placeholder">
                                <i class="fas fa-comments" aria-hidden="true"></i>
                                <h3>No conversation selected</h3>
                                <p>Choose a thread from the inbox to read and reply.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($selectedMessage && $replyAnchorMessage)
                        <div class="guest-messages-reply">
                            <form method="POST" action="{{ route('messages.reply', $replyAnchorMessage, false) }}" data-loading-form enctype="multipart/form-data">
                                @csrf
                                <textarea
                                    name="content"
                                    class="guest-messages-reply__textarea"
                                    placeholder="Write a reply…"
                                >{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="guest-messages-reply__error">{{ $message }}</p>
                                @enderror
                                <div class="guest-messages-reply__file msg-file-field">
                                    <label for="guest_inline_reply_attachment">Photo <span class="font-normal text-gray-400">(optional)</span></label>
                                    <input type="file" name="attachment" id="guest_inline_reply_attachment" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="msg-file-input" data-image-preview="guest-inline-reply-preview">
                                    @error('attachment')
                                        <p class="guest-messages-reply__error">{{ $message }}</p>
                                    @enderror
                                    <div class="msg-preview-thumb" id="guest-inline-reply-preview" aria-hidden="true"></div>
                                </div>
                                <div class="guest-messages-reply__actions">
                                    <button type="submit" data-loading-button class="guest-messages-reply__send">Send</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </section>
            </div>
        @else
            <section class="guest-messages-empty" aria-label="No messages">
                <div class="guest-messages-empty__card">
                    <i class="fas fa-comment-dots" aria-hidden="true"></i>
                    <h3>No messages yet</h3>
                    <p>
                        You do not have any conversations yet.
                        @if($showComposeButton)
                            Start one with the owner or an administrator.
                        @endif
                    </p>
                    @if($showComposeButton)
                        <a href="{{ route('messages.create', [], false) }}" class="guest-messages-empty__cta">
                            <i class="fas fa-plus" aria-hidden="true"></i> New conversation
                        </a>
                    @endif
                </div>
            </section>
        @endif
    </main>
    @else
    <main
        class="messages-index-main {{ $useOwnerNavbar ? 'main-content with-owner-nav owner-app-main flex w-full flex-col' : 'mx-auto flex min-h-screen w-full max-w-none flex-col px-3 pb-6 sm:px-4 lg:px-6' }}"
        @if(! $useOwnerNavbar && ! $authUser?->isClient()) style="padding-top: calc(var(--client-nav-offset) + 12px);" @endif
    >
        <header class="{{ $useOwnerNavbar ? 'owner-page-top' : 'page-header mb-4 flex flex-shrink-0 flex-col gap-3 sm:mb-5 sm:flex-row sm:items-start sm:justify-between' }}">
            @if($useOwnerNavbar)
                <div class="owner-page-hero owner-page-hero--flush">
                    <p class="owner-page-hero__eyebrow">Inbox</p>
                    <h1 class="owner-page-hero__title">Messages</h1>
                    <p class="owner-page-hero__lede">Inbox and replies in one place—pick a thread or start a new conversation.</p>
                </div>
            @else
            <div class="min-w-0 flex-1">
                <h1>
                    <span class="page-title-icon"><i class="fa-solid fa-comment-dots"></i></span>
                    <span>Messages</span>
                </h1>
                <p class="text-slate-600">Inbox and replies in one place—pick a thread or start a new conversation.</p>
            </div>
            @endif
            @if($showComposeButton)
                <a
                    href="{{ route('messages.create', [], false) }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-teal-900/20 transition hover:brightness-105 focus:outline-none focus:ring-2 focus:ring-teal-500/40"
                >
                    <i class="fas fa-plus text-xs"></i>
                    New conversation
                </a>
            @endif
        </header>

        @include('partials.flash-alerts')

        @if(isset($messages) && count($messages) > 0)
            <div
                class="messages-split grid min-h-0 flex-1 grid-cols-1 gap-4 sm:gap-5 lg:grid-cols-12 lg:grid-rows-[minmax(0,1fr)] xl:gap-5"
            >
                <aside class="flex min-h-[240px] flex-col overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm shadow-slate-900/[0.06] ring-1 ring-slate-900/[0.03] lg:col-span-4 lg:h-full lg:min-h-0 xl:col-span-4">
                    <div class="flex flex-shrink-0 flex-wrap items-center justify-between gap-2 border-b border-slate-100 bg-white px-4 py-3.5 sm:px-5">
                        <h2 class="text-base font-bold tracking-tight text-slate-900">Inbox</h2>
                        @if(($unreadCount ?? 0) > 0)
                            <form method="POST" action="{{ route('messages.mark-all-read', [], false) }}" class="m-0" data-loading-form>
                                @csrf
                                <button
                                    type="submit"
                                    data-loading-button
                                    class="rounded-lg border border-teal-600/30 bg-teal-50 px-3 py-1.5 text-xs font-semibold text-teal-900 transition hover:bg-teal-100/80"
                                >
                                    Mark all read
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="msg-scrollbar min-h-0 flex-1 overflow-y-auto overscroll-contain">
                        @foreach($messages as $message)
                            @php
                                $otherParty = (int) $message->sender_id === (int) Auth::id()
                                    ? $message->receiver
                                    : $message->sender;
                                $otherId = (int) ($otherParty->id ?? 0);
                                $selectedOtherId = $selectedMessage
                                    ? ((int) $selectedMessage->sender_id === (int) Auth::id()
                                        ? (int) $selectedMessage->receiver_id
                                        : (int) $selectedMessage->sender_id)
                                    : null;
                                $isActiveThread = $selectedOtherId !== null && $otherId === $selectedOtherId;
                                $hasUnreadFromPartner = $otherId > 0 && ($unreadByPartner[$otherId] ?? false);
                            @endphp
                            <a
                                href="{{ url('/messages') }}?partner={{ $otherId }}{{ request()->get('page') ? '&page='.(int) request()->get('page') : '' }}"
                                class="flex gap-3 border-b border-slate-50 px-4 py-3.5 transition sm:gap-3 sm:px-5 sm:py-4 {{ $isActiveThread ? 'bg-teal-50/40 ring-1 ring-inset ring-teal-500/15' : 'hover:bg-slate-50/90' }} {{ $hasUnreadFromPartner ? 'border-l-[3px] border-l-teal-500 bg-teal-50/35 pl-[calc(1rem-3px)] sm:pl-[calc(1.25rem-3px)]' : '' }}"
                            >
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-teal-600 to-emerald-600 text-sm font-bold text-white shadow-sm shadow-teal-900/20 sm:h-12 sm:w-12">
                                    {{ strtoupper(substr($otherParty->name ?? 'U', 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="mb-0.5 flex items-center justify-between gap-2">
                                        <span class="truncate text-sm font-bold text-slate-900">{{ $otherParty->name ?? 'Unknown' }}</span>
                                        <span class="shrink-0 text-xs font-medium text-slate-500">{{ $message->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="truncate text-sm font-medium text-slate-700">{{ $message->subject ?? 'No subject' }}</div>
                                    <div class="truncate text-xs text-slate-500">{{ Str::limit($message->excerpt, 56) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </aside>

                @php
                    $currentUserId = Auth::id();
                    $chatPartner = $selectedMessage
                        ? ((int) $selectedMessage->sender_id === (int) $currentUserId ? $selectedMessage->receiver : $selectedMessage->sender)
                        : null;
                @endphp

                <section class="flex min-h-[280px] flex-col overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm shadow-slate-900/[0.06] ring-1 ring-slate-900/[0.03] lg:col-span-8 lg:h-full lg:min-h-0 xl:col-span-8">
                    <div class="flex flex-shrink-0 flex-wrap items-start justify-between gap-3 border-b border-slate-100 bg-slate-50/95 px-4 py-3.5 sm:gap-3 sm:px-5 sm:py-4 lg:px-6">
                        <div class="min-w-0 flex-1">
                            <h2 class="text-lg font-bold tracking-tight text-slate-900">{{ $chatPartner->name ?? 'Conversation' }}</h2>
                            <p class="mt-0.5 text-xs text-slate-600 sm:text-sm">{{ $chatPartner->email ?? 'Select a conversation from the inbox.' }}</p>
                        </div>
                        @if($canDeleteSelectedConversation)
                            <form
                                method="POST"
                                action="{{ route('messages.destroy', $selectedMessage, false) }}"
                                class="m-0 shrink-0"
                                onsubmit="return confirm('Delete this entire conversation? This cannot be undone.');"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-semibold text-red-800 shadow-sm transition hover:bg-red-50"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="msg-scrollbar min-h-0 flex-1 overflow-y-auto overscroll-contain bg-slate-100/90 px-4 py-4 sm:px-5 sm:py-5 lg:px-6">
                        @forelse($conversationMessages as $chatMessage)
                            @php
                                $isMine = (int) $chatMessage->sender_id === (int) $currentUserId;
                                $senderName = $isMine ? 'You' : ($chatMessage->sender->name ?? 'User');
                            @endphp
                            <div class="mb-3 flex items-end gap-2 sm:mb-4 {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                @if(! $isMine)
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-800 text-xs font-bold text-white shadow-sm">
                                        {{ strtoupper(substr($senderName, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="max-w-[85%] sm:max-w-[74%]">
                                    @if(filled($chatMessage->content))
                                        <div
                                            class="rounded-2xl px-4 py-3 text-sm leading-relaxed shadow-sm sm:text-[0.9375rem] {{ $isMine ? 'rounded-tr-md bg-gradient-to-br from-teal-600 to-emerald-600 text-white shadow-md shadow-teal-900/20' : 'rounded-tl-md border border-slate-200/90 bg-white text-slate-900' }}"
                                        >
                                            {{ $chatMessage->content }}
                                        </div>
                                    @endif
                                    @if($chatMessage->attachment_url)
                                        <a href="{{ $chatMessage->attachment_url }}" target="_blank" rel="noopener noreferrer" class="msg-bubble-attachment {{ filled($chatMessage->content) ? 'msg-bubble-attachment--stacked' : '' }} {{ $isMine ? 'ml-auto' : '' }}">
                                            <img src="{{ $chatMessage->attachment_url }}" alt="Attached photo" loading="lazy" decoding="async">
                                        </a>
                                    @endif
                                    <p class="mt-1.5 text-[0.7rem] font-medium text-slate-500 {{ $isMine ? 'text-right' : '' }}">
                                        {{ $senderName }} · {{ $chatMessage->created_at->format('M d, h:i A') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="flex min-h-[220px] flex-col items-center justify-center rounded-xl border border-dashed border-slate-200 bg-white/90 px-4 py-12 text-center shadow-inner">
                                <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                    <i class="fas fa-comments text-xl"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-800">No conversation selected</h3>
                                <p class="mt-2 max-w-sm text-sm text-slate-500">Choose a thread from the inbox to read and reply.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($selectedMessage && $replyAnchorMessage)
                        <div class="flex-shrink-0 border-t border-slate-100 bg-white px-4 py-3.5 sm:px-5 sm:py-4 lg:px-6">
                            <form method="POST" action="{{ route('messages.reply', $replyAnchorMessage, false) }}" class="flex flex-col gap-3 sm:gap-3" data-loading-form enctype="multipart/form-data">
                                @csrf
                                <textarea
                                    name="content"
                                    class="msg-scrollbar min-h-[88px] w-full resize-y rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none ring-teal-500/0 transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 sm:min-h-[96px] sm:text-[0.9375rem]"
                                    placeholder="Write a reply…"
                                >{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-sm text-red-700">{{ $message }}</p>
                                @enderror
                                <div class="msg-file-field">
                                    <label for="inline_reply_attachment" class="text-xs font-semibold text-slate-600">Photo <span class="font-normal text-slate-400">(optional)</span></label>
                                    <input type="file" name="attachment" id="inline_reply_attachment" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="msg-file-input" data-image-preview="inline-reply-preview">
                                    @error('attachment')
                                        <p class="text-sm text-red-700">{{ $message }}</p>
                                    @enderror
                                    <div class="msg-preview-thumb" id="inline-reply-preview" aria-hidden="true"></div>
                                </div>
                                <div class="flex justify-end">
                                    <button
                                        type="submit"
                                        data-loading-button
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-teal-900/20 transition hover:brightness-105 sm:w-auto"
                                    >
                                        Send
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </section>
            </div>
        @else
            <div class="flex flex-1 flex-col items-center justify-center rounded-2xl border border-slate-200/90 bg-white px-6 py-20 text-center shadow-sm shadow-slate-900/[0.05] ring-1 ring-slate-900/[0.03] sm:py-28">
                <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white shadow-lg shadow-teal-900/25" aria-hidden="true">
                    <i class="fas fa-comment-dots text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold tracking-tight text-slate-900">No messages yet</h3>
                <p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-slate-600 sm:text-base">
                    You do not have any conversations yet.
                    @if($showComposeButton)
                        Start one with
                        @if($authUser?->isClient())
                            the owner or an administrator.
                        @else
                            a guest, a team member, or ImpaStay central support.
                        @endif
                    @endif
                </p>
                @if($showComposeButton)
                    <a
                        href="{{ route('messages.create', [], false) }}"
                        class="mt-8 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-teal-900/20 transition hover:brightness-105"
                    >
                        <i class="fas fa-plus text-xs"></i>
                        New conversation
                    </a>
                @endif
            </div>
        @endif
    </main>
    @endif
    @include('partials.message-attachment-preview-script')
    <script>
        document.querySelectorAll('form[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const button = form.querySelector('[data-loading-button]');
                if (!button) return;
                button.disabled = true;
                if (button.textContent.trim().toLowerCase().includes('mark all')) {
                    button.textContent = 'Updating...';
                } else {
                    button.textContent = 'Sending...';
                }
            });
        });
    </script>
</body>
</html>
