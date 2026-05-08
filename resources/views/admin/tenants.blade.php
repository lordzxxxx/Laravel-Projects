<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.partials.favicon')
    <title>Tenant Management - Admin Dashboard</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @include('admin.partials.admin-shell-styles')

        .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--green-soft);
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }

        @media (min-width: 768px) {
            .card-header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .card-header h3 {
            font-size: 1.1rem;
            color: var(--green-dark);
        }

        .pagination {
            padding: 14px 20px;
        }

        .flash-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .tenant-filters {
            padding: 16px 20px;
            border-bottom: 1px solid #E5E7EB;
            background: rgba(248, 250, 252, 0.82);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        .tenant-filters-grid {
            display: grid;
            grid-template-columns: 1.5fr repeat(4, minmax(120px, 1fr));
            gap: 10px;
            align-items: end;
        }

        @media (max-width: 1024px) {
            .tenant-filters-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 640px) {
            .tenant-filters-grid {
                grid-template-columns: 1fr;
            }
        }

        .tenant-filter-label {
            display: block;
            margin-bottom: 6px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .03em;
            text-transform: uppercase;
            color: #6B7280;
        }

        .tenant-filter-input {
            width: 100%;
            border: 1px solid #D1D5DB;
            border-radius: 10px;
            padding: 9px 11px;
            font-size: 13px;
            color: #111827;
            background: #fff;
        }

        .tenant-filter-input:focus {
            border-color: #10B981;
            outline: none;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }

        .tenant-filter-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .tenant-filter-meta {
            font-size: 13px;
            color: #6B7280;
        }

        .tenant-summary-row {
            align-items: center;
        }

        .tenant-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
        }

        .tenant-sub {
            margin-top: 2px;
            font-size: 11px;
            color: #6b7280;
        }

        .admin-actions-shell {
            border-top: 1px solid #e5e7eb;
            background:
                radial-gradient(900px 200px at 0% 0%, rgba(16, 185, 129, 0.04), transparent 60%),
                linear-gradient(180deg, rgba(248, 250, 252, 0.96) 0%, rgba(241, 245, 249, 0.92) 100%);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .admin-actions-grid {
            gap: 12px;
        }

        .tenants-table-head {
            background: linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%);
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body>
    @include('admin.partials.top-navbar', ['active' => 'tenants'])

    <div class="dashboard-layout">
        <main class="main-content">
            @if(session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif
            @if($errors->has('onboarding'))
                <div class="flash-error">{{ $errors->first('onboarding') }}</div>
            @endif
            @if($errors->has('confirm_slug'))
                <div class="flash-error">{{ $errors->first('confirm_slug') }}</div>
            @endif
            @if($errors->has('delete'))
                <div class="flash-error">{{ $errors->first('delete') }}</div>
            @endif

            @php
                // Lightweight stats from the current paginated set (always available).
                $statsActive = $tenants->getCollection()->where('subscription_status', 'active')->count();
                $statsTrialing = $tenants->getCollection()->where('subscription_status', 'trialing')->count();
                $statsPending = $tenants->getCollection()->where('onboarding_status', 'pending_approval')->count();
                $statsDisabled = $tenants->getCollection()->filter(fn ($t) => ! ($t->domain_enabled ?? true))->count();
            @endphp

            <div class="page-header !mb-6 flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1>
                        <span class="page-title-icon"><i class="fa-solid fa-building"></i></span>
                        <span>Tenant Management</span>
                    </h1>
                    <p>Plans, domains, subscriptions, and bandwidth across every tenant in the central app.</p>
                </div>
                <a href="{{ route('admin.tenants.lifecycle-logs', [], false) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-[12px] font-semibold text-emerald-700 border border-emerald-200 shadow-sm transition hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300">
                    <i class="fa-solid fa-clock-rotate-left text-[11px]"></i>
                    Lifecycle logs
                </a>
            </div>

            {{-- Stat overview tiles --}}
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-emerald-200">
                    <div class="flex items-start gap-3.5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <i class="fa-solid fa-database text-[15px]"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Total tenants</p>
                            <p class="mt-1 text-2xl font-bold leading-none text-gray-900">{{ $tenants->total() }}</p>
                            <p class="mt-1 text-[10px] text-gray-400">across all pages</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-emerald-200">
                    <div class="flex items-start gap-3.5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <i class="fa-solid fa-circle-check text-[15px]"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Active</p>
                            <p class="mt-1 text-2xl font-bold leading-none text-gray-900">{{ $statsActive }}</p>
                            <p class="mt-1 text-[10px] text-gray-400">on this page</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-amber-200">
                    <div class="flex items-start gap-3.5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700 border border-amber-100">
                            <i class="fa-solid fa-hourglass-half text-[15px]"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Pending review</p>
                            <p class="mt-1 text-2xl font-bold leading-none text-gray-900">{{ $statsPending }}</p>
                            <p class="mt-1 text-[10px] text-gray-400">on this page</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-sky-200">
                    <div class="flex items-start gap-3.5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-700 border border-sky-100">
                            <i class="fa-solid fa-clock text-[15px]"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Trialing</p>
                            <p class="mt-1 text-2xl font-bold leading-none text-gray-900">{{ $statsTrialing }}</p>
                            <p class="mt-1 text-[10px] text-gray-400">on this page</p>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.partials.tenants-onboarding-gcash')

            @php
                $in = 'w-full rounded-md border border-gray-200 bg-white px-2.5 py-1.5 text-[12px] text-gray-900 shadow-sm placeholder:text-gray-400 transition focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500/30';
                $sel = $in;
                $lbl = 'mb-1 block text-[10px] font-bold uppercase tracking-wide text-gray-500';
                $cardTitle = 'mb-3 flex items-center gap-2.5 text-[13px] font-semibold leading-tight text-gray-900';
                $cardIcon = 'flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-[13px] text-emerald-700 ring-1 ring-emerald-100';
                // Equal-width (grid cell), equal-height cards. Footer anchored to bottom via mt-auto.
                $actionCard = 'flex h-full w-full flex-col rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:border-emerald-200 hover:shadow-md';
                $formCol = 'flex h-full flex-1 flex-col';
                $formFields = 'flex flex-col gap-2.5';
                $formFooter = 'mt-auto shrink-0 border-t border-gray-100 pt-3';
                $btnP = 'inline-flex w-full cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-2 text-[12px] font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1';
                $btnD = 'inline-flex w-full cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-red-600 px-3 py-2 text-[12px] font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1';
                $btnN = 'inline-flex w-full cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-[12px] font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1';
                $btnB = 'inline-flex w-full cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-sky-600 px-3 py-2 text-[12px] font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-1';
                $badgeBase = 'inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-1 text-[11px] font-bold leading-none';
                $dangerCard = 'flex h-full w-full flex-col rounded-xl border border-red-200 bg-gradient-to-br from-red-50/90 via-white to-white p-4 shadow-sm transition hover:border-red-300 hover:shadow-md';
            @endphp

            <div class="card ui-surface overflow-hidden">
                <div class="card-header">
                    <h3 class="flex items-center gap-2">
                        <i class="fa-solid fa-list-ul text-[13px] text-emerald-600/80"></i>
                        All tenants
                    </h3>
                    <p class="max-w-2xl text-xs leading-relaxed text-gray-500">
                        Bandwidth shows estimated HTTP transfer per tenant host (static assets skipped). Use <strong class="font-semibold text-gray-700">Manage</strong> on a row to edit profile, plan, domain, billing, and bandwidth.
                    </p>
                </div>

                <div class="tenant-filters">
                    <form method="GET" action="/admin/tenants">
                        <div class="tenant-filters-grid">
                            <div>
                                <label class="tenant-filter-label" for="tenant-q">Search</label>
                                <input
                                    id="tenant-q"
                                    class="tenant-filter-input"
                                    type="text"
                                    name="q"
                                    value="{{ $tenantFilters['q'] ?? '' }}"
                                    placeholder="Tenant, owner, email, domain, or slug"
                                >
                            </div>
                            <div>
                                <label class="tenant-filter-label" for="tenant-plan">Plan</label>
                                <select id="tenant-plan" class="tenant-filter-input" name="plan">
                                    <option value="">All plans</option>
                                    <option value="basic" @selected(($tenantFilters['plan'] ?? '') === 'basic')>Basic</option>
                                    <option value="plus" @selected(($tenantFilters['plan'] ?? '') === 'plus')>Standard</option>
                                    <option value="pro" @selected(($tenantFilters['plan'] ?? '') === 'pro')>Premium</option>
                                    <option value="promo" @selected(($tenantFilters['plan'] ?? '') === 'promo')>Promo</option>
                                </select>
                            </div>
                            <div>
                                <label class="tenant-filter-label" for="tenant-subscription">Billing status</label>
                                <select id="tenant-subscription" class="tenant-filter-input" name="subscription_status">
                                    <option value="">All billing</option>
                                    <option value="trialing" @selected(($tenantFilters['subscription_status'] ?? '') === 'trialing')>Trialing</option>
                                    <option value="active" @selected(($tenantFilters['subscription_status'] ?? '') === 'active')>Active</option>
                                    <option value="past_due" @selected(($tenantFilters['subscription_status'] ?? '') === 'past_due')>Past Due</option>
                                    <option value="cancelled" @selected(($tenantFilters['subscription_status'] ?? '') === 'cancelled')>Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label class="tenant-filter-label" for="tenant-onboarding">Onboarding</label>
                                <select id="tenant-onboarding" class="tenant-filter-input" name="onboarding_status">
                                    <option value="">All onboarding</option>
                                    <option value="awaiting_payment" @selected(($tenantFilters['onboarding_status'] ?? '') === 'awaiting_payment')>Awaiting Payment</option>
                                    <option value="pending_approval" @selected(($tenantFilters['onboarding_status'] ?? '') === 'pending_approval')>Pending Approval</option>
                                    <option value="approved" @selected(($tenantFilters['onboarding_status'] ?? '') === 'approved')>Approved</option>
                                    <option value="rejected" @selected(($tenantFilters['onboarding_status'] ?? '') === 'rejected')>Rejected</option>
                                </select>
                            </div>
                            <div>
                                <label class="tenant-filter-label" for="tenant-per-page">Rows</label>
                                <select id="tenant-per-page" class="tenant-filter-input" name="per_page">
                                    @foreach([10, 15, 25, 50] as $size)
                                        <option value="{{ $size }}" @selected((int)($tenantFilters['per_page'] ?? 15) === $size)>{{ $size }}/page</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="tenant-filter-actions">
                            <div class="tenant-filter-meta">
                                Showing {{ $tenants->firstItem() ?? 0 }}-{{ $tenants->lastItem() ?? 0 }} of {{ $tenants->total() }} tenants
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="/admin/tenants" class="{{ $btnN }}" style="width:auto; padding-inline: 12px;">Reset</a>
                                <button type="submit" class="{{ $btnP }}" style="width:auto; padding-inline: 14px;">
                                    <i class="fa-solid fa-magnifying-glass"></i> Apply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @php
                    $tenantRowScroll = 'overflow-x-auto overscroll-x-contain';
                    // Fixed column widths + gap-3 gaps ≈ 1280px — keeps one horizontal table row
                    $tenantRowFlex = 'flex min-w-[1280px] shrink-0 items-center gap-3 px-4';
                @endphp
                {{-- Column guide: same flex row as data (scroll horizontally on narrow viewports) --}}
                <div class="{{ $tenantRowScroll }} tenants-table-head border-b border-gray-200">
                    <div class="{{ $tenantRowFlex }} py-3 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <div class="w-40 shrink-0">Tenant</div>
                        <div class="w-44 shrink-0">Owner</div>
                        <div class="w-24 shrink-0">Plan</div>
                        <div class="w-52 shrink-0">Domain</div>
                            <div class="w-32 shrink-0">Billing</div>
                            <div class="w-36 shrink-0">Onboarding</div>
                        <div class="w-16 shrink-0 text-right">DB</div>
                        <div class="w-36 shrink-0">Bandwidth</div>
                        <div class="w-24 shrink-0">Period</div>
                        <div class="ml-auto w-[100px] shrink-0 text-right"></div>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 bg-white">
                    @forelse($tenants as $tenant)
                        @php
                            $domainEnabled = (bool) ($tenant->domain_enabled ?? true);
                            $statusValue = (string) ($tenant->subscription_status ?? 'unknown');
                            $latestLifecycle = $latestLifecycleByTenant[$tenant->id] ?? null;
                            $centralPort = (int) env('CENTRAL_PORT', 8000);
                            $statusBadgeClass = match ($statusValue) {
                                'active' => 'bg-emerald-100 text-emerald-800 ring-1 ring-inset ring-emerald-600/15',
                                'trialing' => 'bg-amber-100 text-amber-900 ring-1 ring-inset ring-amber-600/15',
                                'past_due' => 'bg-red-100 text-red-800 ring-1 ring-inset ring-red-600/15',
                                'cancelled' => 'bg-gray-100 text-gray-700 ring-1 ring-inset ring-gray-500/12',
                                default => 'bg-gray-100 text-gray-700 ring-1 ring-inset ring-gray-500/12',
                            };

                            $onboardingStatus = (string) ($tenant->onboarding_status ?? 'approved');
                            $onboardingBadgeClass = match ($onboardingStatus) {
                                'awaiting_payment' => 'bg-indigo-100 text-indigo-800 ring-1 ring-inset ring-indigo-600/15',
                                'pending_approval' => 'bg-amber-100 text-amber-900 ring-1 ring-inset ring-amber-600/15',
                                'approved' => 'bg-emerald-100 text-emerald-800 ring-1 ring-inset ring-emerald-600/15',
                                'rejected' => 'bg-red-100 text-red-800 ring-1 ring-inset ring-red-600/15',
                                default => 'bg-gray-100 text-gray-700 ring-1 ring-inset ring-gray-500/12',
                            };
                            $subscriptionStatusLabel = match ($statusValue) {
                                'trialing' => 'Trialing',
                                'past_due' => 'Past due',
                                default => ucfirst(str_replace('_', ' ', $statusValue)),
                            };
                            $onboardingStatusLabel = match ($onboardingStatus) {
                                'pending_approval' => 'Pending',
                                'awaiting_payment' => 'Awaiting',
                                default => ucfirst(str_replace('_', ' ', $onboardingStatus)),
                            };

                            $domainLabel = $tenant->domain
                                ? ($tenant->domain . ':' . $centralPort)
                                : ('127.0.0.1:' . $centralPort);
                            $periodEnds = $tenant->current_period_ends_at ?? $tenant->trial_ends_at;
                            $dbUsed = $tenant->database ? ($databaseUsageMbByDatabase[$tenant->database] ?? null) : null;

                            $adminPlanValues = ['basic', 'plus', 'pro', 'promo'];
                            $tenantPlanValue = (string) $tenant->plan;
                            $needsAdminPlanChoice = ! in_array($tenantPlanValue, $adminPlanValues, true);

                            $bwUsed = (int) ($tenant->bandwidth_usage_bytes ?? 0);
                            $bwQuota = $tenant->bandwidth_quota_bytes;
                            $bwPct = $tenant->bandwidthUsagePercent();
                        @endphp

                        <div class="group" x-data="{ open: false }">
                            {{-- Summary: single horizontal flex row (scroll if viewport is narrow) --}}
                            <div class="{{ $tenantRowScroll }} transition-colors hover:bg-gray-50/90">
                                <div class="{{ $tenantRowFlex }} tenant-summary-row py-4">
                                    <div class="w-40 shrink-0">
                                        <p class="tenant-name leading-tight">{{ $tenant->name }}</p>
                                        <p class="tenant-sub truncate font-mono">{{ $tenant->slug }}</p>
                                    </div>
                                    <div class="w-44 shrink-0">
                                        <p class="truncate text-sm font-semibold text-gray-900">{{ $tenant->owner?->name ?? 'Unassigned' }}</p>
                                        <p class="tenant-sub truncate" title="{{ $tenant->owner?->email }}">{{ $tenant->owner?->email ?? '—' }}</p>
                                    </div>
                                    <div class="flex w-24 shrink-0 items-center">
                                        <span class="{{ $badgeBase }} bg-gray-100 text-gray-800 ring-1 ring-gray-200">{{ \App\Models\Tenant::planLabel($tenant->plan) }}</span>
                                    </div>
                                    <div class="w-52 min-w-0 shrink-0">
                                        @if($domainEnabled)
                                            <a href="{{ $tenant->publicUrl() }}" class="block truncate text-sm font-semibold text-emerald-700 underline-offset-2 hover:text-emerald-800 hover:underline" target="_blank" rel="noopener noreferrer">{{ $domainLabel }}</a>
                                        @else
                                            <span class="block truncate text-sm font-semibold text-red-700">{{ $domainLabel }}</span>
                                        @endif
                                        <span class="mt-0.5 block text-[10px] font-medium uppercase tracking-wide text-gray-400">{{ $domainEnabled ? 'Enabled' : 'Disabled' }}</span>
                                    </div>
                                    <div class="flex w-32 shrink-0 items-center">
                                        <span class="{{ $badgeBase }} {{ $statusBadgeClass }}">{{ $subscriptionStatusLabel }}</span>
                                    </div>
                                    <div class="flex w-36 shrink-0 items-center">
                                        <span class="{{ $badgeBase }} {{ $onboardingBadgeClass }}">{{ $onboardingStatusLabel }}</span>
                                    </div>
                                    <div class="flex w-16 shrink-0 items-center justify-end">
                                        <span class="tabular-nums text-sm font-medium text-gray-800">{{ is_null($dbUsed) ? '—' : number_format((float) $dbUsed, 2) }}</span>
                                    </div>
                                    <div class="w-36 shrink-0">
                                        <p class="text-sm font-semibold text-gray-900">@fileSize($bwUsed)</p>
                                        <p class="text-[10px] text-gray-500">
                                            @if($bwQuota)
                                                of @fileSize((int) $bwQuota)
                                            @else
                                                no cap
                                            @endif
                                        </p>
                                        @if($bwPct !== null)
                                            <div class="mt-1.5 h-1 w-full max-w-[7rem] overflow-hidden rounded-full bg-gray-200">
                                                <div class="h-full rounded-full {{ $bwPct >= 90 ? 'bg-red-600' : ($bwPct >= 70 ? 'bg-amber-500' : 'bg-emerald-600') }}" style="width: {{ min(100, $bwPct) }}%;"></div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex w-24 shrink-0 items-center">
                                        <span class="text-sm text-gray-700">{{ $periodEnds ? $periodEnds->format('M j, Y') : '—' }}</span>
                                    </div>
                                    <div class="ml-auto flex w-[100px] shrink-0 justify-end">
                                        <button type="button" @click="open = !open" :aria-expanded="open"
                                                :class="open ? 'border-emerald-300 bg-emerald-50 text-emerald-800' : 'border-gray-200 bg-white text-gray-700 hover:border-emerald-300 hover:bg-emerald-50/60 hover:text-emerald-800'"
                                                class="inline-flex w-[88px] items-center justify-center gap-2 rounded-lg border px-3 py-2 text-xs font-semibold shadow-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                                            <span x-text="open ? 'Hide' : 'Manage'"></span>
                                            <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="open && 'rotate-180'"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Expandable actions --}}
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-1"
                                 class="admin-actions-shell px-4 py-5 sm:px-6">

                                <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 text-xs font-medium text-gray-600">
                                        <i class="fa-solid fa-circle-info text-emerald-600/80"></i>
                                        <span>Admin actions for <strong class="font-semibold text-gray-800">{{ $tenant->name }}</strong> &mdash; reason required (min 5 chars).</span>
                                    </div>
                                </div>

                                {{-- Onboarding review banner: only when pending; sits at the top with amber accent --}}
                                @if($onboardingStatus === 'pending_approval')
                                    <div class="mb-4 rounded-xl border border-amber-200 bg-gradient-to-br from-amber-50 via-amber-50/40 to-white p-4 shadow-sm">
                                        <div class="flex flex-wrap items-start justify-between gap-3">
                                            <div class="flex min-w-0 items-center gap-3">
                                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-800 ring-1 ring-amber-200">
                                                    <i class="fa-solid fa-user-check"></i>
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="text-[13px] font-semibold leading-tight text-amber-900">Onboarding pending approval</p>
                                                    <p class="mt-0.5 text-[11px] leading-snug text-amber-800/80">
                                                        Review the payment proof and decide whether to approve or reject this tenant's onboarding.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-2 text-[11px] text-amber-900">
                                                @if($tenant->onboarding_payment_channel)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-white/80 px-2 py-0.5 font-semibold uppercase ring-1 ring-amber-200">
                                                        <i class="fa-solid fa-money-bill-wave text-[10px]"></i>
                                                        {{ $tenant->onboarding_payment_channel }}
                                                    </span>
                                                @endif
                                                @if($tenant->payment_reference)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-white/80 px-2 py-0.5 font-mono ring-1 ring-amber-200">
                                                        <i class="fa-solid fa-hashtag text-[10px]"></i>
                                                        {{ $tenant->payment_reference }}
                                                    </span>
                                                @endif
                                                @if($tenant->onboarding_payment_channel === 'gcash' && $tenant->onboardingGcashProofUrl)
                                                    <a href="{{ $tenant->onboardingGcashProofUrl }}" target="_blank" rel="noopener"
                                                       class="inline-flex items-center gap-1 rounded-full bg-emerald-600 px-2.5 py-1 font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                                        <i class="fa-solid fa-image text-[10px]"></i>
                                                        View GCash proof
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
                                            <form action="{{ route('admin.tenants.approve-onboarding', $tenant, false) }}" method="POST"
                                                  class="flex flex-col gap-2 rounded-lg border border-emerald-200 bg-white p-3 shadow-sm">
                                                @csrf
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-appr">Approval reason</label>
                                                    <input id="t{{ $tenant->id }}-appr" type="text" name="reason" required minlength="5" placeholder="Why approve?" class="{{ $in }}">
                                                </div>
                                                <button type="submit" class="{{ $btnP }}"><i class="fa-solid fa-thumbs-up"></i> Approve onboarding</button>
                                            </form>
                                            <form action="{{ route('admin.tenants.reject-onboarding', $tenant, false) }}" method="POST"
                                                  class="flex flex-col gap-2 rounded-lg border border-red-200 bg-white p-3 shadow-sm">
                                                @csrf
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-rej">Rejection reason</label>
                                                    <input id="t{{ $tenant->id }}-rej" type="text" name="reason" required minlength="5" placeholder="Why reject?" class="{{ $in }}">
                                                </div>
                                                <button type="submit" class="{{ $btnD }}"><i class="fa-solid fa-thumbs-down"></i> Reject onboarding</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                                {{-- Main grid: 6 cards in a responsive grid (1 → 2 → 3 columns).
                                     items-stretch is default in CSS grid, so all cards in a row match the tallest height.
                                     mt-auto on the form footer keeps every Save button perfectly aligned at the bottom. --}}
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                    {{-- Profile --}}
                                    <div class="{{ $actionCard }}">
                                        <div class="{{ $cardTitle }} shrink-0">
                                            <span class="{{ $cardIcon }}"><i class="fa-solid fa-building"></i></span>
                                            Tenant profile
                                        </div>
                                        <form class="{{ $formCol }}" action="{{ route('admin.tenants.update-profile', $tenant, false) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="{{ $formFields }}">
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-name">Display name</label>
                                                    <input id="t{{ $tenant->id }}-name" type="text" name="name" value="{{ $tenant->name }}" class="{{ $in }}">
                                                </div>
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-title">App title</label>
                                                    <input id="t{{ $tenant->id }}-title" type="text" name="app_title" value="{{ $tenant->app_title }}" class="{{ $in }}">
                                                </div>
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-locale">Locale</label>
                                                    <select id="t{{ $tenant->id }}-locale" name="locale" class="{{ $sel }}">
                                                        <option value="en" @selected(($tenant->locale ?? 'en') === 'en')>English</option>
                                                        <option value="es" @selected(($tenant->locale ?? 'en') === 'es')>Spanish</option>
                                                        <option value="fr" @selected(($tenant->locale ?? 'en') === 'fr')>French</option>
                                                        <option value="de" @selected(($tenant->locale ?? 'en') === 'de')>German</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-reason-p">Reason</label>
                                                    <input id="t{{ $tenant->id }}-reason-p" type="text" name="reason" required minlength="5" placeholder="Why this change?" class="{{ $in }}">
                                                </div>
                                            </div>
                                            <div class="{{ $formFooter }}">
                                                <button type="submit" class="{{ $btnP }}"><i class="fa-solid fa-floppy-disk"></i> Save profile</button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Owner email --}}
                                    <div class="{{ $actionCard }}">
                                        <div class="{{ $cardTitle }} shrink-0">
                                            <span class="{{ $cardIcon }}"><i class="fa-solid fa-envelope"></i></span>
                                            Owner credentials
                                        </div>
                                        <form class="{{ $formCol }}" action="{{ route('admin.tenants.resend-onboarding-email', $tenant, false) }}" method="POST">
                                            @csrf
                                            <div class="{{ $formFields }}">
                                                <p class="rounded-md bg-gray-50 px-2.5 py-2 text-[11px] leading-snug text-gray-600 ring-1 ring-gray-200/80">
                                                    A new random password is generated and emailed to the owner.
                                                </p>
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-reason-mail">Reason</label>
                                                    <input id="t{{ $tenant->id }}-reason-mail" type="text" name="reason" required minlength="5" placeholder="Why resend?" class="{{ $in }}">
                                                </div>
                                            </div>
                                            <div class="{{ $formFooter }}">
                                                <button type="submit" class="{{ $btnN }}"><i class="fa-solid fa-paper-plane"></i> Email admin login</button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Plan --}}
                                    <div class="{{ $actionCard }}">
                                        <div class="{{ $cardTitle }} shrink-0">
                                            <span class="{{ $cardIcon }}"><i class="fa-solid fa-layer-group"></i></span>
                                            Subscription plan
                                        </div>
                                        <form class="{{ $formCol }}" action="{{ route('admin.tenants.update-plan', $tenant, false) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="{{ $formFields }}">
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-plan">Plan</label>
                                                    <select id="t{{ $tenant->id }}-plan" name="plan" class="{{ $sel }}" required>
                                                        @if($needsAdminPlanChoice)
                                                            <option value="" selected disabled>Select plan…</option>
                                                        @endif
                                                        <option value="basic" @selected($tenant->plan === 'basic')>Basic</option>
                                                        <option value="plus" @selected($tenant->plan === 'plus')>Standard</option>
                                                        <option value="pro" @selected($tenant->plan === 'pro')>Premium</option>
                                                        <option value="promo" @selected($tenant->plan === 'promo')>{{ \App\Models\Tenant::planLabel('promo') }}</option>
                                                    </select>
                                                </div>
                                                @if($needsAdminPlanChoice)
                                                    <p class="rounded-md bg-amber-50 px-2 py-1.5 text-[11px] leading-snug text-amber-900 ring-1 ring-amber-200/80">Unknown plan in database — choose a catalog plan and save.</p>
                                                @endif
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-reason-plan">Reason</label>
                                                    <input id="t{{ $tenant->id }}-reason-plan" type="text" name="reason" required minlength="5" placeholder="Why change plan?" class="{{ $in }}">
                                                </div>
                                            </div>
                                            <div class="{{ $formFooter }}">
                                                <button type="submit" class="{{ $btnP }}"><i class="fa-solid fa-check"></i> Save plan</button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Domain --}}
                                    <div class="{{ $actionCard }}">
                                        <div class="{{ $cardTitle }} shrink-0">
                                            <span class="{{ $cardIcon }}"><i class="fa-solid fa-globe"></i></span>
                                            Domain access
                                        </div>
                                        <form class="{{ $formCol }}" action="{{ route('admin.tenants.toggle-domain', $tenant, false) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="domain_enabled" value="{{ $domainEnabled ? 0 : 1 }}">
                                            <div class="{{ $formFields }}">
                                                <p class="rounded-md px-2.5 py-2 text-[11px] leading-snug ring-1 ring-inset
                                                    {{ $domainEnabled ? 'bg-emerald-50 text-emerald-800 ring-emerald-200/80' : 'bg-gray-50 text-gray-700 ring-gray-200/80' }}">
                                                    Currently <strong>{{ $domainEnabled ? 'on' : 'off' }}</strong> for visitors.
                                                </p>
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-reason-dom">Reason</label>
                                                    <input id="t{{ $tenant->id }}-reason-dom" type="text" name="reason" required minlength="5" placeholder="Why toggle domain?" class="{{ $in }}">
                                                </div>
                                            </div>
                                            <div class="{{ $formFooter }}">
                                                <button type="submit" class="{{ $domainEnabled ? $btnD : $btnB }}">
                                                    <i class="fa-solid {{ $domainEnabled ? 'fa-ban' : 'fa-circle-check' }}"></i>
                                                    {{ $domainEnabled ? 'Disable domain' : 'Enable domain' }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Subscription --}}
                                    <div class="{{ $actionCard }}">
                                        <div class="{{ $cardTitle }} shrink-0">
                                            <span class="{{ $cardIcon }}"><i class="fa-solid fa-credit-card"></i></span>
                                            Billing status
                                        </div>
                                        <form class="{{ $formCol }}" action="{{ route('admin.tenants.update-subscription', $tenant, false) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="{{ $formFields }}">
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-sub">Status</label>
                                                    <select id="t{{ $tenant->id }}-sub" name="subscription_status" class="{{ $sel }}">
                                                        <option value="trialing" @selected($statusValue === 'trialing')>Trialing</option>
                                                        <option value="active" @selected($statusValue === 'active')>Active</option>
                                                        <option value="past_due" @selected($statusValue === 'past_due')>Past due</option>
                                                        <option value="cancelled" @selected($statusValue === 'cancelled')>Cancelled</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-reason-sub">Reason</label>
                                                    <input id="t{{ $tenant->id }}-reason-sub" type="text" name="reason" required minlength="5" placeholder="Why update status?" class="{{ $in }}">
                                                </div>
                                                @if($latestLifecycle)
                                                    <div class="text-[9px] leading-snug text-gray-500">
                                                        <span class="font-semibold text-gray-600">Last:</span>
                                                        {{ str_replace('.', ' ', ucfirst($latestLifecycle->action)) }}
                                                        <span class="mt-0.5 block text-gray-400">{{ $latestLifecycle->created_at?->format('M j, g:i A') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="{{ $formFooter }}">
                                                <button type="submit" class="{{ $btnP }}"><i class="fa-solid fa-rotate"></i> Update status</button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Bandwidth --}}
                                    <div class="{{ $actionCard }}">
                                        <div class="{{ $cardTitle }} shrink-0">
                                            <span class="{{ $cardIcon }}"><i class="fa-solid fa-chart-simple"></i></span>
                                            Bandwidth quota
                                        </div>
                                        <form class="{{ $formCol }}" action="{{ route('admin.tenants.update-bandwidth-quota', $tenant, false) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="{{ $formFields }}">
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-quota">Quota (MiB / month)</label>
                                                    <input id="t{{ $tenant->id }}-quota" type="number" name="bandwidth_quota_mb" min="0" step="1" placeholder="Empty = unlimited" value="{{ $bwQuota ? (int) round($bwQuota / 1024 / 1024) : '' }}" class="{{ $in }}">
                                                </div>
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-reason-bw">Reason</label>
                                                    <input id="t{{ $tenant->id }}-reason-bw" type="text" name="reason" required minlength="5" placeholder="Why update quota?" class="{{ $in }}">
                                                </div>
                                                @if($tenant->bandwidth_last_recorded_at)
                                                    <p class="text-[10px] text-gray-400">Recorded {{ $tenant->bandwidth_last_recorded_at->diffForHumans() }}</p>
                                                @endif
                                            </div>
                                            <div class="{{ $formFooter }}">
                                                <button type="submit" class="{{ $btnP }}"><i class="fa-solid fa-gauge-high"></i> Save quota</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>

                                {{-- Danger zone: Delete tenant — visually separated at the bottom --}}
                                <div class="mt-5">
                                    <div class="mb-2 flex items-center gap-2">
                                        <span class="h-px flex-1 bg-red-200/70"></span>
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider text-red-700 ring-1 ring-red-200">
                                            <i class="fa-solid fa-triangle-exclamation text-[9px]"></i>
                                            Danger zone
                                        </span>
                                        <span class="h-px flex-1 bg-red-200/70"></span>
                                    </div>

                                    <div class="{{ $dangerCard }}">
                                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                            <div class="flex items-start gap-3 lg:max-w-md">
                                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-700 ring-1 ring-red-200">
                                                    <i class="fa-solid fa-trash"></i>
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="text-[13px] font-semibold leading-tight text-red-900">Delete tenant</p>
                                                    <p class="mt-1 text-[11px] leading-snug text-red-800/85">
                                                        Permanently deletes <strong class="font-mono font-semibold">{{ $tenant->slug }}</strong>.
                                                        The tenant database will be dropped if it exists. <strong>This action cannot be undone.</strong>
                                                    </p>
                                                </div>
                                            </div>

                                            <form action="{{ route('admin.tenants.destroy', $tenant, false) }}" method="POST"
                                                  onsubmit="return confirm('Permanently delete this tenant? Database will be dropped if it exists. Cannot be undone.');"
                                                  class="grid w-full grid-cols-1 gap-2 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] sm:items-end lg:max-w-2xl">
                                                @csrf
                                                @method('DELETE')
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-slug">Type slug to confirm</label>
                                                    <input id="t{{ $tenant->id }}-slug" type="text" name="confirm_slug" value="{{ old('confirm_slug') }}" placeholder="{{ $tenant->slug }}" required autocomplete="off" class="{{ $in }} font-mono">
                                                </div>
                                                <div>
                                                    <label class="{{ $lbl }}" for="t{{ $tenant->id }}-reason-del">Reason</label>
                                                    <input id="t{{ $tenant->id }}-reason-del" type="text" name="reason" required minlength="5" placeholder="Why delete?" value="{{ old('reason') }}" class="{{ $in }}">
                                                </div>
                                                <button type="submit" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-red-600 px-4 text-[12px] font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                                    <i class="fa-solid fa-trash"></i> Delete tenant
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-16 text-center text-sm text-gray-500">No tenants found.</div>
                    @endforelse
                </div>

                @if($tenants->hasPages())
                    <div class="pagination border-t border-gray-100">
                        {{ $tenants->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
