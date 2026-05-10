<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('admin.partials.favicon')
    <title>Plan management</title>
    @vite(['resources/css/app.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>@include('admin.partials.admin-shell-styles')</style>
    <style>
        /* Unified state chip used in the Visible and Featured columns so both
           share the same shape, size, and brand-aligned colour palette. */
        .plan-state-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            /* Fixed width keeps the column from shifting between "Visible" / "Hidden". */
            width: 108px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1;
            border: 1px solid transparent;
            background: transparent;
            cursor: default;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.15s ease;
            justify-content: center;
            text-decoration: none;
            white-space: nowrap;
        }
        .plan-state-chip[data-busy="true"] {
            opacity: 0.7;
            pointer-events: none;
        }
        .plan-state-chip i { font-size: 11px; }

        /* Active / positive (emerald) — visible or featured. */
        .plan-state-chip--on {
            background: #ECFDF5;
            border-color: #A7F3D0;
            color: #065F46;
        }
        /* Neutral / off — hidden or not featured. */
        .plan-state-chip--off {
            background: #F8FAFC;
            border-color: #E2E8F0;
            color: #475569;
        }

        /* When the chip is also a form button, give it a subtle hover. */
        button.plan-state-chip { cursor: pointer; }
        button.plan-state-chip--on:hover {
            background: #D1FAE5;
            border-color: #6EE7B7;
        }
        button.plan-state-chip--off:hover {
            background: #EEF2F7;
            border-color: #CBD5E1;
            color: #1E293B;
        }
        button.plan-state-chip:focus-visible {
            outline: 2px solid #10B981;
            outline-offset: 2px;
        }
    </style>
</head>
<body class="admin-central-portal">
    @include('admin.partials.top-navbar', ['active' => 'landing-plans'])

    <div class="dashboard-layout">
        <main class="main-content">
            @if(session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif

            <div class="page-header-row">
                <div class="page-header">
                    <h1>
                        <span class="page-title-icon"><i class="fa-solid fa-tags"></i></span>
                        <span>Plan management</span>
                    </h1>
                    <p>Marketing cards on the central landing page. Edit each plan to set titles, prices, and features; leave optional fields empty to use the standard defaults for that tier.</p>
                </div>
                <a href="{{ route('admin.landing-plans.create', [], false) }}" class="btn-admin-primary"><i class="fas fa-plus"></i> Add plan</a>
            </div>

            <div class="card">
                <div class="overflow-x-auto border-t border-emerald-100/90 bg-white">
                    <table class="min-w-[720px] w-full border-collapse text-left text-sm text-gray-800">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gradient-to-r from-emerald-50 via-green-50/90 to-emerald-50/70">
                                <th class="whitespace-nowrap px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600">Order</th>
                                <th class="whitespace-nowrap px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600">Tier key</th>
                                <th class="whitespace-nowrap px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600">Resolved (landing)</th>
                                <th class="whitespace-nowrap px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600">Feature mode</th>
                                <th class="whitespace-nowrap px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600">Visible</th>
                                <th class="whitespace-nowrap px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600">Featured</th>
                                <th class="whitespace-nowrap px-4 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($plans as $plan)
                                <tr class="align-top transition-colors hover:bg-gray-50/80">
                                    <td class="px-4 py-4">{{ $plan->sort_order }}</td>
                                    <td class="px-4 py-4"><code class="rounded bg-gray-100 px-1.5 py-0.5 text-xs">{{ $plan->tenant_plan_key }}</code></td>
                                    <td class="px-4 py-4">
                                        <p class="font-semibold text-gray-900">{{ $plan->effectiveTitle() }}</p>
                                        @php $ep = $plan->effectivePrice(); @endphp
                                        <p class="mt-0.5 text-xs text-gray-500">{{ $plan->effectiveCurrency() }}{{ number_format($ep, floor($ep) == $ep ? 0 : 2) }}</p>
                                        <ul class="mt-2 list-none space-y-1 text-xs text-gray-700">
                                            @foreach(array_slice($plan->effectiveFeatures(), 0, 4) as $line)
                                                <li class="flex items-start gap-2">
                                                    <span class="mt-0.5 inline-flex h-3.5 w-3.5 shrink-0 items-center justify-center rounded-full border border-emerald-200 bg-white text-[8px] text-emerald-700">
                                                        <i class="fa-solid fa-check"></i>
                                                    </span>
                                                    <span>{{ \Illuminate\Support\Str::limit($line, 56) }}</span>
                                                </li>
                                            @endforeach
                                            @if(count($plan->effectiveFeatures()) > 4)
                                                <li class="pl-5 text-gray-500">…</li>
                                            @endif
                                        </ul>
                                    </td>
                                    <td class="px-4 py-4 text-xs text-gray-500">
                                        @if($plan->featureSelectionMode() === 'custom_pick')
                                            Features: chosen bullets
                                        @elseif($plan->featureSelectionMode() === 'full_catalog')
                                            Features: full checklist
                                        @else
                                            Features: tier catalog
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <button type="button"
                                                class="plan-state-chip {{ $plan->is_visible ? 'plan-state-chip--on' : 'plan-state-chip--off' }}"
                                                data-visibility-toggle
                                                data-toggle-url="{{ route('admin.landing-plans.toggle-visibility', ['central_landing_plan' => $plan->getKey()], false) }}"
                                                data-state="{{ $plan->is_visible ? 'on' : 'off' }}"
                                                title="{{ $plan->is_visible ? 'Click to hide this card from the public CA landing' : 'Click to show this card on the public CA landing' }}">
                                            <i class="fa-solid {{ $plan->is_visible ? 'fa-eye' : 'fa-eye-slash' }}" data-chip-icon></i>
                                            <span data-chip-label>{{ $plan->is_visible ? 'Visible' : 'Hidden' }}</span>
                                        </button>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($plan->is_featured)
                                            <span class="plan-state-chip plan-state-chip--on">
                                                <i class="fa-solid fa-star"></i>
                                                <span>Featured</span>
                                            </span>
                                        @else
                                            <span class="plan-state-chip plan-state-chip--off">
                                                <i class="fa-regular fa-star"></i>
                                                <span>Standard</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <a href="{{ route('admin.landing-plans.edit', ['central_landing_plan' => $plan->getKey()], false) }}" class="btn-admin-sm btn-admin-sm-outline" style="margin-right:6px;">Edit</a>
                                        <form action="{{ route('admin.landing-plans.destroy', ['central_landing_plan' => $plan->getKey()], false) }}" method="POST" class="inline" onsubmit="return confirm('Delete this landing plan row?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-admin-sm btn-admin-sm-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-gray-500">No plans yet. Run migrations or add a plan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    {{-- Lightweight toast for in-place visibility toggles. --}}
    <div id="lp-toast"
         role="status"
         aria-live="polite"
         style="position:fixed;right:24px;bottom:24px;z-index:9999;display:none;
                padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;
                color:#065F46;background:#ECFDF5;border:1px solid #A7F3D0;
                box-shadow:0 6px 18px rgba(15,23,42,0.10);min-width:200px;text-align:center;">
    </div>

    <script>
    (function () {
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
        const toast = document.getElementById('lp-toast');

        function showToast(message, isError) {
            if (!toast) return;
            toast.textContent = message;
            toast.style.color = isError ? '#7F1D1D' : '#065F46';
            toast.style.background = isError ? '#FEF2F2' : '#ECFDF5';
            toast.style.borderColor = isError ? '#FECACA' : '#A7F3D0';
            toast.style.display = 'block';
            clearTimeout(toast._hideTimer);
            toast._hideTimer = setTimeout(function () { toast.style.display = 'none'; }, 2200);
        }

        function applyState(button, isVisible) {
            const icon = button.querySelector('[data-chip-icon]');
            const label = button.querySelector('[data-chip-label]');
            if (isVisible) {
                button.classList.remove('plan-state-chip--off');
                button.classList.add('plan-state-chip--on');
                button.dataset.state = 'on';
                button.title = 'Click to hide this card from the public CA landing';
                if (icon) { icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }
                if (label) { label.textContent = 'Visible'; }
            } else {
                button.classList.remove('plan-state-chip--on');
                button.classList.add('plan-state-chip--off');
                button.dataset.state = 'off';
                button.title = 'Click to show this card on the public CA landing';
                if (icon) { icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); }
                if (label) { label.textContent = 'Hidden'; }
            }
        }

        document.querySelectorAll('[data-visibility-toggle]').forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                if (button.dataset.busy === 'true') return;

                const url = button.dataset.toggleUrl;
                if (!url) return;

                button.dataset.busy = 'true';
                const wasVisible = button.dataset.state === 'on';
                applyState(button, !wasVisible);

                const formData = new FormData();
                formData.append('_method', 'PATCH');
                formData.append('_token', csrfToken);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'text/html, application/json',
                    },
                    credentials: 'same-origin',
                    body: formData,
                })
                .then(function (response) {
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    showToast(!wasVisible ? 'Plan is now visible.' : 'Plan is now hidden.', false);
                })
                .catch(function () {
                    applyState(button, wasVisible);
                    showToast('Could not update visibility. Try again.', true);
                })
                .finally(function () {
                    button.dataset.busy = 'false';
                });
            });
        });
    })();
    </script>
</body>
</html>
