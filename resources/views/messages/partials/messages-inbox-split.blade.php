{{--
    Split inbox + conversation panel.
    $surfacePrefix — e.g. guest-messages, owner-messages (CSS class namespace)
    $emptyStateHint — optional extra line for empty state
--}}
@php
    $pfx = $surfacePrefix ?? 'guest-messages';
    $emptyHint = $emptyStateHint ?? 'Start a new conversation when you are ready.';
    $attachmentInputId = $pfx.'-inline-reply-attachment';
    $attachmentPreviewId = $pfx.'-inline-reply-preview';
@endphp

@if(isset($messages) && count($messages) > 0)
    <div class="{{ $pfx }}-workspace">
        <aside class="{{ $pfx }}-inbox" aria-label="Inbox">
            <div class="{{ $pfx }}-inbox__head">
                <h2 class="{{ $pfx }}-inbox__title">Threads</h2>
                @if(($unreadCount ?? 0) > 0)
                    <form method="POST" action="{{ route('messages.mark-all-read', [], false) }}" class="m-0" data-loading-form>
                        @csrf
                        <button type="submit" data-loading-button class="{{ $pfx }}-mark-read">Mark all read</button>
                    </form>
                @endif
            </div>
            <div class="{{ $pfx }}-thread-list msg-scrollbar">
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
                        class="{{ $pfx }}-thread {{ $isActiveThread ? 'is-active' : '' }} {{ $hasUnreadFromPartner ? 'is-unread' : '' }}"
                    >
                        <div class="{{ $pfx }}-thread__avatar">{{ strtoupper(substr($otherParty->name ?? 'U', 0, 2)) }}</div>
                        <div class="{{ $pfx }}-thread__body">
                            <div class="{{ $pfx }}-thread__row">
                                <span class="{{ $pfx }}-thread__name">{{ $otherParty->name ?? 'Unknown' }}</span>
                                <span class="{{ $pfx }}-thread__time">{{ $message->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="{{ $pfx }}-thread__subject">{{ $message->subject ?? 'No subject' }}</div>
                            <div class="{{ $pfx }}-thread__preview">{{ Str::limit($message->excerpt, 56) }}</div>
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

        <section class="{{ $pfx }}-chat" aria-label="Conversation">
            <div class="{{ $pfx }}-chat__head">
                <div class="{{ $pfx }}-chat__head-inner">
                    <h2 class="{{ $pfx }}-chat__title">{{ $chatPartner->name ?? 'Conversation' }}</h2>
                    <p class="{{ $pfx }}-chat__subtitle">{{ $chatPartner->email ?? 'Select a thread from the inbox.' }}</p>
                </div>
                @if($canDeleteSelectedConversation)
                    <div class="{{ $pfx }}-chat__head-actions">
                        <form
                            method="POST"
                            action="{{ route('messages.destroy', $selectedMessage, false) }}"
                            class="m-0"
                            onsubmit="return confirm('Delete this entire conversation? This cannot be undone.');"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="{{ $pfx }}-delete">
                                <i class="fas fa-trash-alt" aria-hidden="true"></i> Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="{{ $pfx }}-chat__stream msg-scrollbar">
                @forelse($conversationMessages as $chatMessage)
                    @php
                        $isMine = (int) $chatMessage->sender_id === (int) $currentUserId;
                        $senderName = $isMine ? 'You' : ($chatMessage->sender->name ?? 'User');
                    @endphp
                    <div class="{{ $pfx }}-bubble-row {{ $isMine ? $pfx.'-bubble-row--mine' : '' }}">
                        @if(! $isMine)
                            <div class="{{ $pfx }}-bubble-row__avatar">{{ strtoupper(substr($senderName, 0, 1)) }}</div>
                        @endif
                        <div class="{{ $pfx }}-bubble-wrap">
                            @if(filled($chatMessage->content))
                                <div class="{{ $pfx }}-bubble {{ $isMine ? $pfx.'-bubble--mine' : $pfx.'-bubble--theirs' }}">
                                    {{ $chatMessage->content }}
                                </div>
                            @endif
                            @if($chatMessage->attachment_url)
                                <a href="{{ $chatMessage->attachment_url }}" target="_blank" rel="noopener noreferrer" class="msg-bubble-attachment {{ filled($chatMessage->content) ? 'msg-bubble-attachment--stacked' : '' }} {{ $isMine ? 'is-mine' : '' }}">
                                    <img src="{{ $chatMessage->attachment_url }}" alt="Attached photo" loading="lazy" decoding="async">
                                </a>
                            @endif
                            <p class="{{ $pfx }}-bubble-meta">
                                {{ $senderName }} · {{ $chatMessage->created_at->format('M d, h:i A') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="{{ $pfx }}-chat__placeholder">
                        <i class="fas fa-comments" aria-hidden="true"></i>
                        <h3>No conversation selected</h3>
                        <p>Choose a thread from the inbox to read and reply.</p>
                    </div>
                @endforelse
            </div>

            @if($selectedMessage && $replyAnchorMessage)
                <div class="{{ $pfx }}-reply">
                    <form method="POST" action="{{ route('messages.reply', $replyAnchorMessage, false) }}" data-loading-form enctype="multipart/form-data">
                        @csrf
                        <textarea
                            name="content"
                            class="{{ $pfx }}-reply__textarea"
                            placeholder="Write a reply…"
                        >{{ old('content') }}</textarea>
                        @error('content')
                            <p class="{{ $pfx }}-reply__error">{{ $message }}</p>
                        @enderror
                        <div class="{{ $pfx }}-reply__file msg-file-field">
                            <label for="{{ $attachmentInputId }}">Photo <span class="{{ $pfx }}-label-optional">(optional)</span></label>
                            <input type="file" name="attachment" id="{{ $attachmentInputId }}" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="msg-file-input" data-image-preview="{{ $attachmentPreviewId }}">
                            @error('attachment')
                                <p class="{{ $pfx }}-reply__error">{{ $message }}</p>
                            @enderror
                            <div class="msg-preview-thumb" id="{{ $attachmentPreviewId }}" aria-hidden="true"></div>
                        </div>
                        <div class="{{ $pfx }}-reply__actions">
                            <button type="submit" data-loading-button class="{{ $pfx }}-reply__send">Send</button>
                        </div>
                    </form>
                </div>
            @endif
        </section>
    </div>
@else
    <section class="{{ $pfx }}-empty" aria-label="No messages">
        <div class="{{ $pfx }}-empty__card">
            <i class="fas fa-comment-dots" aria-hidden="true"></i>
            <h3>No messages yet</h3>
            <p>
                You do not have any conversations yet.
                {{ $emptyHint }}
            </p>
            @if($showComposeButton)
                <a href="{{ route('messages.create', [], false) }}" class="{{ $pfx }}-empty__cta">
                    <i class="fas fa-plus" aria-hidden="true"></i> New conversation
                </a>
            @endif
        </div>
    </section>
@endif
