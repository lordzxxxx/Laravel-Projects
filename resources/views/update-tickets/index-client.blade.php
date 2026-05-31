<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Support - ImpaStay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            @include('partials.tenant-theme-css-vars')
            --gray-200: #E5E7EB;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
        }
        @include('client.partials.top-navbar-styles')
        @include('client.partials.guest-support-styles')
    </style>
</head>
<body class="client-nav-page font-sans text-gray-800">
    @include('client.partials.top-navbar', ['active' => 'update-tickets'])

    <main class="client-guest-main client-guest-main--wide guest-support-main">
        <header class="guest-support-hero">
            <p class="guest-support-hero__eyebrow">Help & feedback</p>
            <h1 class="guest-support-hero__title">Support</h1>
            <p class="guest-support-hero__lede">Submit issues about system updates or downloads. Central admin will review your ticket.</p>
        </header>

        @include('partials.flash-alerts')

        <div class="guest-support-workspace">
            <section class="guest-support-panel" aria-labelledby="guest-support-new-heading">
                <div class="guest-support-panel__head">
                    <h2 id="guest-support-new-heading" class="guest-support-panel__title">New ticket</h2>
                </div>
                <div class="guest-support-panel__body">
                    <form method="POST" action="/update-tickets" enctype="multipart/form-data" class="guest-support-form" data-loading-form>
                        @csrf

                        <div class="guest-support-field">
                            <label for="subject">Subject</label>
                            <input
                                id="subject"
                                name="subject"
                                type="text"
                                value="{{ old('subject') }}"
                                required
                                maxlength="255"
                                class="guest-support-input"
                            >
                            @error('subject')
                                <p class="guest-support-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="guest-support-field">
                            <label for="body">Details</label>
                            <textarea id="body" name="body" rows="6" required maxlength="10000" class="guest-support-textarea">{{ old('body') }}</textarea>
                            @error('body')
                                <p class="guest-support-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="guest-support-field">
                            <label for="attachment">Photo <span class="optional">(optional, JPG/PNG/WEBP up to 5MB)</span></label>
                            <input
                                id="attachment"
                                name="attachment"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                class="guest-support-file"
                            >
                            @error('attachment')
                                <p class="guest-support-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" data-loading-button class="guest-support-submit">
                            <i class="fas fa-paper-plane" aria-hidden="true"></i> Submit
                        </button>
                    </form>
                </div>
            </section>

            <section class="guest-support-panel guest-support-panel--tickets" aria-labelledby="guest-support-tickets-heading">
                <div class="guest-support-panel__head">
                    <h2 id="guest-support-tickets-heading" class="guest-support-panel__title">Your tickets</h2>
                </div>
                <div class="guest-support-panel__body guest-support-panel__body--scroll">
                    @if($tickets->count() > 0)
                        <div class="guest-support-ticket-list">
                            @foreach($tickets as $ticket)
                                <a href="/update-tickets/{{ $ticket->id }}" class="guest-support-ticket">
                                    <h3 class="guest-support-ticket__subject">{{ \Illuminate\Support\Str::limit($ticket->subject, 72) }}</h3>
                                    <time class="guest-support-ticket__date" datetime="{{ $ticket->created_at?->toIso8601String() }}">
                                        {{ $ticket->created_at?->format('M j, Y') }}
                                    </time>
                                    <div class="guest-support-ticket__footer">
                                        @if($ticket->status === \App\Models\UpdateTicket::STATUS_RESOLVED)
                                            <span class="guest-support-badge guest-support-badge--resolved">
                                                <i class="fas fa-check" aria-hidden="true"></i> Resolved
                                            </span>
                                        @else
                                            <span class="guest-support-badge guest-support-badge--open">
                                                <i class="fas fa-inbox" aria-hidden="true"></i> Open
                                            </span>
                                        @endif
                                        <span class="guest-support-ticket__link">View details →</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        @if($tickets->hasPages())
                            <nav class="guest-support-pagination" aria-label="Ticket pages">
                                {{ $tickets->links() }}
                            </nav>
                        @endif
                    @else
                        <div class="guest-support-empty">
                            <i class="fas fa-life-ring" aria-hidden="true"></i>
                            <p>No tickets yet. Use the form to submit your first request.</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </main>
    <script>
        document.querySelectorAll('form[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const button = form.querySelector('[data-loading-button]');
                if (!button) return;
                button.disabled = true;
                button.textContent = 'Submitting...';
            });
        });
    </script>
</body>
</html>
