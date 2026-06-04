<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('admin.partials.favicon')
    <title>Tenant Lifecycle Logs - Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <style>
        @include('admin.partials.admin-shell-styles')
        @include('partials.ui-foundation-styles')

        [x-cloak] { display: none !important; }

        .log-card {
            background: var(--app-surface-bg, #FFFFFF);
            border: 1px solid var(--app-surface-border, #E5E7EB);
            border-radius: 14px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.05s ease;
        }
        .log-card:hover {
            border-color: rgba(16, 185, 129, 0.35);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        }

        .action-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.01em;
            white-space: nowrap;
            border: 1px solid transparent;
        }
        .action-chip i { font-size: 10px; }
        .action-chip.emerald { background: #ECFDF5; color: #065F46; border-color: #A7F3D0; }
        .action-chip.amber   { background: #FFFBEB; color: #92400E; border-color: #FDE68A; }
        .action-chip.red     { background: #FEF2F2; color: #991B1B; border-color: #FECACA; }
        .action-chip.sky     { background: #F0F9FF; color: #075985; border-color: #BAE6FD; }
        .action-chip.indigo  { background: #EEF2FF; color: #3730A3; border-color: #C7D2FE; }
        .action-chip.slate   { background: #F1F5F9; color: #334155; border-color: #CBD5E1; }

        .action-rail {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 14px;
            border: 1px solid transparent;
        }
        .action-rail.emerald { background: #ECFDF5; color: #047857; border-color: #A7F3D0; }
        .action-rail.amber   { background: #FFFBEB; color: #B45309; border-color: #FDE68A; }
        .action-rail.red     { background: #FEF2F2; color: #B91C1C; border-color: #FECACA; }
        .action-rail.sky     { background: #F0F9FF; color: #0369A1; border-color: #BAE6FD; }
        .action-rail.indigo  { background: #EEF2FF; color: #4338CA; border-color: #C7D2FE; }
        .action-rail.slate   { background: #F1F5F9; color: #475569; border-color: #CBD5E1; }

        .meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
            color: #475569;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
        }
        .meta-pill i { color: #94A3B8; font-size: 10px; }

        .diff-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12px;
        }
        .diff-table th, .diff-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #E5E7EB;
            text-align: left;
            vertical-align: top;
            line-height: 1.45;
        }
        .diff-table thead th {
            background: var(--app-surface-muted-bg, #F8FAFC);
            color: var(--ink-600, #475569);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .diff-table tr:last-child td { border-bottom: none; }
        .diff-table .diff-key {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 11px;
            color: var(--ink-800, #1F2937);
            font-weight: 600;
            white-space: nowrap;
        }
        .diff-cell {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 11px;
            color: var(--ink-700, #1F2937);
            word-break: break-word;
            white-space: pre-wrap;
        }
        .diff-cell.before { background: #FEF2F2; color: #991B1B; }
        .diff-cell.after  { background: #ECFDF5; color: #065F46; }
        .diff-cell.empty  { color: #94A3B8; font-style: italic; }

        .filter-input {
            width: 100%;
            border: 1px solid var(--app-surface-border, #D1D5DB);
            border-radius: 10px;
            padding: 9px 12px 9px 34px;
            font-size: 13px;
            color: var(--ink-800, #111827);
            background: var(--app-surface-bg, #FFFFFF);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .filter-input:focus {
            border-color: #10B981;
            outline: none;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18);
        }
        .filter-wrap { position: relative; }
        .filter-wrap .filter-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 12px;
            pointer-events: none;
        }

        .btn-apply {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 10px;
            background: #059669;
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }
        .btn-apply:hover { background: #047857; }
        .btn-reset {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 14px;
            border-radius: 10px;
            background: #FFFFFF;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #E2E8F0;
            text-decoration: none;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }
        .btn-reset:hover { background: #F8FAFC; border-color: #CBD5E1; }

        .toggle-diff {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            color: #047857;
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            cursor: pointer;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }
        .toggle-diff:hover { background: #D1FAE5; }
    </style>
</head>
<body class="admin-central-portal">
    @include('admin.partials.top-navbar', ['active' => 'tenants'])

    @php
        // Lifecycle action presentation map
        $actionPresets = [
            'tenant.created' => ['label' => 'Tenant created', 'tone' => 'emerald', 'icon' => 'fa-circle-plus'],
            'tenant.updated' => ['label' => 'Tenant updated', 'tone' => 'sky', 'icon' => 'fa-pen-to-square'],
            'tenant.deleted' => ['label' => 'Tenant deleted', 'tone' => 'red', 'icon' => 'fa-trash'],
            'tenant.profile_updated' => ['label' => 'Profile updated', 'tone' => 'sky', 'icon' => 'fa-id-card'],
            'tenant.plan_updated' => ['label' => 'Plan updated', 'tone' => 'indigo', 'icon' => 'fa-layer-group'],
            'tenant.subscription_updated' => ['label' => 'Billing status changed', 'tone' => 'indigo', 'icon' => 'fa-credit-card'],
            'tenant.bandwidth_quota_updated' => ['label' => 'Bandwidth quota changed', 'tone' => 'sky', 'icon' => 'fa-gauge-high'],
            'tenant.domain_enabled' => ['label' => 'Domain enabled', 'tone' => 'emerald', 'icon' => 'fa-circle-check'],
            'tenant.domain_disabled' => ['label' => 'Domain disabled', 'tone' => 'red', 'icon' => 'fa-ban'],
            'tenant.domain_toggled' => ['label' => 'Domain toggled', 'tone' => 'sky', 'icon' => 'fa-globe'],
            'tenant.onboarding_approved' => ['label' => 'Onboarding approved', 'tone' => 'emerald', 'icon' => 'fa-thumbs-up'],
            'tenant.onboarding_rejected' => ['label' => 'Onboarding rejected', 'tone' => 'red', 'icon' => 'fa-thumbs-down'],
            'tenant.approve_onboarding' => ['label' => 'Onboarding approved', 'tone' => 'emerald', 'icon' => 'fa-thumbs-up'],
            'tenant.reject_onboarding' => ['label' => 'Onboarding rejected', 'tone' => 'red', 'icon' => 'fa-thumbs-down'],
            'tenant.email_resent' => ['label' => 'Owner email resent', 'tone' => 'slate', 'icon' => 'fa-paper-plane'],
            'tenant.suspended' => ['label' => 'Tenant suspended', 'tone' => 'amber', 'icon' => 'fa-pause'],
            'tenant.reactivated' => ['label' => 'Tenant reactivated', 'tone' => 'emerald', 'icon' => 'fa-play'],
        ];

        $resolveAction = function (string $action) use ($actionPresets): array {
            if (isset($actionPresets[$action])) {
                return $actionPresets[$action];
            }
            // Heuristic fallbacks based on suffix
            $tone = 'slate';
            $icon = 'fa-clock-rotate-left';
            if (str_contains($action, 'delete') || str_contains($action, 'reject') || str_contains($action, 'disable') || str_contains($action, 'remove')) {
                $tone = 'red';
                $icon = 'fa-circle-xmark';
            } elseif (str_contains($action, 'create') || str_contains($action, 'approve') || str_contains($action, 'enable') || str_contains($action, 'activate')) {
                $tone = 'emerald';
                $icon = 'fa-circle-check';
            } elseif (str_contains($action, 'update') || str_contains($action, 'change') || str_contains($action, 'edit')) {
                $tone = 'sky';
                $icon = 'fa-pen-to-square';
            } elseif (str_contains($action, 'suspend') || str_contains($action, 'pause') || str_contains($action, 'warn')) {
                $tone = 'amber';
                $icon = 'fa-triangle-exclamation';
            }

            return [
                'label' => Str::headline(str_replace(['.', '_'], ' ', $action)),
                'tone' => $tone,
                'icon' => $icon,
            ];
        };

        // Format a value for diff display (collapse arrays/booleans/nulls)
        $formatValue = function ($value): string {
            if ($value === null) return '—';
            if (is_bool($value)) return $value ? 'true' : 'false';
            if (is_array($value)) return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return (string) $value;
        };

        // Build a "diff" of changed keys between before/after states
        $buildDiff = function (?array $before, ?array $after) {
            $before = $before ?? [];
            $after = $after ?? [];
            $allKeys = array_unique(array_merge(array_keys($before), array_keys($after)));
            sort($allKeys);

            $diff = [];
            foreach ($allKeys as $key) {
                $b = $before[$key] ?? null;
                $a = $after[$key] ?? null;
                $bJson = is_array($b) ? json_encode($b) : (string) ($b ?? '');
                $aJson = is_array($a) ? json_encode($a) : (string) ($a ?? '');
                $changed = ($bJson !== $aJson);
                $diff[] = [
                    'key' => $key,
                    'before' => $b,
                    'after' => $a,
                    'changed' => $changed,
                ];
            }
            return $diff;
        };

        // Lightweight stats from the current page
        $statsTotal = $logs->total();
        $statsToday = $logs->getCollection()->filter(fn ($l) => $l->created_at && $l->created_at->isToday())->count();
        $statsWeek = $logs->getCollection()->filter(fn ($l) => $l->created_at && $l->created_at->isCurrentWeek())->count();
        $statsActors = $logs->getCollection()->pluck('actor_user_id')->filter()->unique()->count();
    @endphp

    <div class="dashboard-layout">
        <main class="main-content">
            @if(session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif

            <div class="page-header !mb-6 flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1>
                        <span class="page-title-icon"><i class="fa-solid fa-clock-rotate-left"></i></span>
                        <span>Tenant Lifecycle Logs</span>
                    </h1>
                    <p>Audit trail for every tenant lifecycle event in the central app.</p>
                </div>
                <a href="{{ route('admin.tenants', [], false) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-[12px] font-semibold text-emerald-700 border border-emerald-200 shadow-sm transition hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    Back to tenants
                </a>
            </div>

            {{-- Stat tiles --}}
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-emerald-200">
                    <div class="flex items-start gap-3.5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <i class="fa-solid fa-list-check text-[15px]"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Total entries</p>
                            <p class="mt-1 text-2xl font-bold leading-none text-gray-900">{{ number_format($statsTotal) }}</p>
                            <p class="mt-1 text-[10px] text-gray-400">across all pages</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-sky-200">
                    <div class="flex items-start gap-3.5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-700 border border-sky-100">
                            <i class="fa-solid fa-calendar-day text-[15px]"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Today</p>
                            <p class="mt-1 text-2xl font-bold leading-none text-gray-900">{{ $statsToday }}</p>
                            <p class="mt-1 text-[10px] text-gray-400">on this page</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-indigo-200">
                    <div class="flex items-start gap-3.5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-100">
                            <i class="fa-solid fa-calendar-week text-[15px]"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">This week</p>
                            <p class="mt-1 text-2xl font-bold leading-none text-gray-900">{{ $statsWeek }}</p>
                            <p class="mt-1 text-[10px] text-gray-400">on this page</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-amber-200">
                    <div class="flex items-start gap-3.5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700 border border-amber-100">
                            <i class="fa-solid fa-user-shield text-[15px]"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Distinct actors</p>
                            <p class="mt-1 text-2xl font-bold leading-none text-gray-900">{{ $statsActors }}</p>
                            <p class="mt-1 text-[10px] text-gray-400">on this page</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="ui-surface mb-4 rounded-xl border border-gray-200 bg-white shadow-sm">
                <form method="GET" action="{{ route('admin.tenants.lifecycle-logs') }}"
                      class="grid grid-cols-1 gap-3 p-4 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
                    <div class="filter-wrap">
                        <i class="fa-solid fa-magnifying-glass filter-icon"></i>
                        <input type="text" name="tenant" value="{{ request('tenant') }}" placeholder="Filter by tenant name or slug" class="filter-input">
                    </div>
                    <div class="filter-wrap">
                        <i class="fa-solid fa-bolt filter-icon"></i>
                        <input type="text" name="action" value="{{ request('action') }}" placeholder="Filter by action (e.g. plan, delete, approve)" class="filter-input">
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.tenants.lifecycle-logs') }}" class="btn-reset">
                            <i class="fa-solid fa-rotate-left text-[11px]"></i>
                            Reset
                        </a>
                        <button type="submit" class="btn-apply">
                            <i class="fa-solid fa-filter text-[11px]"></i>
                            Apply
                        </button>
                    </div>
                </form>
                @if(request('tenant') || request('action'))
                    <div class="border-t border-gray-100 px-4 py-2 text-[11px] text-gray-500">
                        <span class="font-semibold text-gray-700">Filters active:</span>
                        @if(request('tenant'))
                            <span class="ml-1 inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-800 ring-1 ring-emerald-200">
                                tenant: {{ request('tenant') }}
                            </span>
                        @endif
                        @if(request('action'))
                            <span class="ml-1 inline-flex items-center gap-1 rounded-full bg-sky-50 px-2 py-0.5 text-[11px] font-semibold text-sky-800 ring-1 ring-sky-200">
                                action: {{ request('action') }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Activity feed (vertical, no horizontal scroll) --}}
            <div class="space-y-3">
                @forelse($logs as $log)
                    @php
                        $preset = $resolveAction((string) $log->action);
                        $logName = $log->tenant?->name;
                        $logSlug = $log->tenant?->slug;
                        if (str_contains((string) $log->action, 'deleted') && ! $log->tenant) {
                            $logName = $log->before_state['name'] ?? $logName;
                            $logSlug = $log->before_state['slug'] ?? $logSlug;
                        }
                        $diff = $buildDiff($log->before_state, $log->after_state);
                        $changedDiff = array_values(array_filter($diff, fn ($d) => $d['changed']));
                        $hasDiff = ! empty($changedDiff);
                    @endphp

                    <div class="log-card p-4" x-data="{ open: false }">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:gap-4">
                            <div class="flex shrink-0 items-start gap-3">
                                <span class="action-rail {{ $preset['tone'] }}">
                                    <i class="fa-solid {{ $preset['icon'] }}"></i>
                                </span>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="action-chip {{ $preset['tone'] }}">
                                        <i class="fa-solid {{ $preset['icon'] }}"></i>
                                        {{ $preset['label'] }}
                                    </span>
                                    <span class="text-[11px] text-gray-400">·</span>
                                    <span class="font-mono text-[11px] text-gray-400">{{ $log->action }}</span>
                                </div>

                                <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <p class="text-[14px] font-semibold leading-tight text-gray-900">
                                        {{ $logName ?? 'Unknown tenant' }}
                                    </p>
                                    @if($logSlug)
                                        <span class="font-mono text-[11px] text-gray-500">{{ $logSlug }}</span>
                                    @endif
                                </div>

                                @if(! is_null($log->reason) && trim((string) $log->reason) !== '')
                                    <div class="mt-2 rounded-lg bg-gray-50 px-3 py-2 text-[12px] leading-snug text-gray-700 ring-1 ring-gray-200/80">
                                        <i class="fa-solid fa-quote-left mr-1 text-[10px] text-gray-400"></i>
                                        {{ $log->reason }}
                                    </div>
                                @endif

                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <span class="meta-pill">
                                        <i class="fa-solid fa-clock"></i>
                                        <span title="{{ $log->created_at?->format('M d, Y h:i A') }}">
                                            {{ $log->created_at?->diffForHumans() ?? '—' }}
                                        </span>
                                    </span>
                                    <span class="meta-pill">
                                        <i class="fa-solid fa-user-shield"></i>
                                        {{ $log->actor?->name ?? 'System' }}
                                        @if($log->actor?->email)
                                            <span class="text-gray-400">·</span>
                                            <span class="text-gray-500">{{ $log->actor->email }}</span>
                                        @endif
                                    </span>
                                    @if($hasDiff)
                                        <button type="button" @click="open = !open" class="toggle-diff" :aria-expanded="open">
                                            <i class="fa-solid" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                            <span x-text="open ? 'Hide changes' : ('View changes (' + {{ count($changedDiff) }} + ')')"></span>
                                        </button>
                                    @else
                                        <span class="meta-pill">
                                            <i class="fa-solid fa-circle-info"></i>
                                            No state changes recorded
                                        </span>
                                    @endif
                                </div>

                                @if($hasDiff)
                                    <div x-cloak x-show="open"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="mt-3 overflow-hidden rounded-xl border border-gray-200 bg-white">
                                        <div class="app-table-responsive"><table class="diff-table app-data-table">
                                            <thead>
                                                <tr>
                                                    <th class="w-[26%]">Field</th>
                                                    <th class="w-[37%]">Before</th>
                                                    <th class="w-[37%]">After</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($changedDiff as $row)
                                                    @php
                                                        $beforeStr = $formatValue($row['before']);
                                                        $afterStr = $formatValue($row['after']);
                                                    @endphp
                                                    <tr>
                                                        <td class="diff-key">{{ $row['key'] }}</td>
                                                        <td>
                                                            <div class="diff-cell before {{ $beforeStr === '—' ? 'empty' : '' }}">{{ $beforeStr }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="diff-cell after {{ $afterStr === '—' ? 'empty' : '' }}">{{ $afterStr }}</div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="log-card p-10 text-center">
                        <span class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-gray-400 ring-1 ring-gray-200">
                            <i class="fa-solid fa-inbox text-lg"></i>
                        </span>
                        <p class="text-sm font-semibold text-gray-700">No lifecycle logs found</p>
                        <p class="mt-1 text-xs text-gray-500">Try adjusting your filters or come back after admin actions are performed.</p>
                    </div>
                @endforelse
            </div>

            @if($logs->hasPages())
                <div class="mt-5 rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm">
                    {{ $logs->links() }}
                </div>
            @endif
        </main>
    </div>
</body>
</html>
