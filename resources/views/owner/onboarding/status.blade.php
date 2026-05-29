<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Registration status — {{ $tenant->name }}</title>
    <style>
        @include('partials.app-typography-styles')
        :root {
            @include('partials.tenant-theme-css-vars', ['themeTenant' => $tenant])
            --muted: #6b7280;
            --line: #e5e7eb;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: #f8fafc;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            max-width: 520px;
            width: 100%;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.06);
        }
        h1 { font-size: 1.4rem; margin: 0 0 8px; color: var(--primary); }
        p { color: var(--muted); line-height: 1.6; margin: 0 0 14px; }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 6px 12px;
            margin-bottom: 14px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .01em;
            white-space: nowrap;
        }
        .status-pill.pending {
            color: #92400e;
            background: #fef3c7;
            border: 1px solid #fcd34d;
        }
        .status-pill.rejected {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }
        .info-list {
            display: grid;
            gap: 10px;
            margin: 0 0 14px;
        }
        .info-item {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #f8fafc;
            padding: 10px 12px;
        }
        .info-label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #6b7280;
            margin-bottom: 4px;
            font-weight: 700;
        }
        .info-value {
            font-size: 14px;
            color: #111827;
            font-weight: 600;
            word-break: break-word;
        }
        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            padding-top: 2px;
        }
        .link-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 8px 12px;
            background: #fff;
            color: #0f766e;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }
        .flash {
            background: #ecfdf5;
            border: 1px solid #86efac;
            color: #166534;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-weight: 600;
        }
        .rejected { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        a { color: var(--primary); font-weight: 700; }
        @media (max-width: 640px) {
            body { padding: 14px; }
            .card { padding: 20px 16px; }
            .actions { flex-direction: column; align-items: stretch; }
            .link-btn { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="card">
        @if(session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif

        @if($state === 'pending')
            <div class="status-pill pending">Waiting for approval</div>
            <h1>Under review</h1>
            <p>Your payment was received and is now in the admin verification queue. You will get tenant admin credentials by email once approved.</p>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Payment channel</span>
                    <span class="info-value">{{ strtoupper((string) ($tenant->onboarding_payment_channel ?? 'payment')) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Submitted at</span>
                    <span class="info-value">{{ $tenant->payment_submitted_at?->format('M j, Y g:i A') ?? '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Reference</span>
                    <span class="info-value">{{ $tenant->payment_reference ?? '—' }}</span>
                </div>
            </div>
            <div class="actions">
                @if($tenant->onboarding_payment_channel === 'gcash' && $tenant->onboardingGcashProofUrl)
                    <a class="link-btn" href="{{ $tenant->onboardingGcashProofUrl }}" target="_blank" rel="noopener">View uploaded GCash proof</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="background:none;border:none;padding:0;color:var(--primary);font-weight:700;cursor:pointer;text-decoration:underline;">
                        Sign out
                    </button>
                </form>
            </div>
        @elseif($state === 'rejected')
            <div class="status-pill rejected">Registration not approved</div>
            <h1>Not approved</h1>
            <p class="flash rejected">Your registration was not approved. Please contact support if you believe this is an error.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;padding:0;color:var(--primary);font-weight:700;cursor:pointer;text-decoration:underline;">
                    Sign out
                </button>
            </form>
        @endif
    </div>
</body>
</html>
