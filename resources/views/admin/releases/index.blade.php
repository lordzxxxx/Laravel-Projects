<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('admin.partials.favicon')
    <title>System Updates — Release Registry</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @include('admin.partials.admin-shell-styles')
        @include('partials.ui-foundation-styles')
        /* Match other CA admin pages: full-width main under fixed navbar */
        .releases-main-content {
            max-width: none;
        }
        .updates-surface {
            background: var(--app-surface-bg, rgba(255, 255, 255, 0.9));
            border: 1px solid var(--app-surface-border, #e2e8f0);
            border-radius: 14px;
            box-shadow: var(--shadow-sm, 0 6px 20px rgba(15, 23, 42, 0.05));
            color: var(--ink-800);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .updates-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 12px;
            font-weight: 700;
            transition: all .18s ease;
            border: 1px solid transparent;
        }
        .updates-btn:hover { transform: translateY(-1px); }
        .updates-btn-primary { background: #0f766e; color: #fff; box-shadow: 0 4px 12px rgba(15, 118, 110, 0.22); }
        .updates-btn-primary:hover { background: #115e59; }
        .updates-btn-secondary { background: #fff; color: #334155; border-color: #cbd5e1; }
        .updates-btn-secondary:hover { background: #f8fafc; border-color: #94a3b8; }
        .updates-btn-warning { background: #fff7ed; color: #9a3412; border-color: #fed7aa; }
        .updates-btn-warning:hover { background: #ffedd5; }
        .updates-btn-danger { background: #fff1f2; color: #9f1239; border-color: #fecdd3; }
        .updates-btn-danger:hover { background: #ffe4e6; }
    </style>
</head>
<body class="admin-central-portal">
    @include('admin.partials.top-navbar', ['active' => 'updates'])

    <div class="dashboard-layout">
        <main class="main-content releases-main-content">
            <div class="w-full">
                    {{-- Page header --}}
                    <header class="page-header mb-6 flex shrink-0 flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <h1>
                                <span class="page-title-icon"><i class="fa-solid fa-rocket"></i></span>
                                <span>Global release registry</span>
                            </h1>
                            <p class="max-w-3xl">
                                Track GitHub releases and tenant adoption across Tulogans. Sync pulls tags and published releases into this registry.
                            </p>
                        </div>
                        <div class="flex shrink-0 flex-wrap items-center gap-3">
                            <form method="POST" action="{{ route('admin.releases.sync', [], false) }}">
                                @csrf
                                <button type="submit" class="updates-btn updates-btn-primary focus:outline-none focus:ring-2 focus:ring-teal-500/35 focus:ring-offset-1">
                                    <i class="fas fa-rotate" aria-hidden="true"></i>
                                    Sync from GitHub
                                </button>
                            </form>
                        </div>
                    </header>

                    @if (session('success'))
                        <div
                            class="mb-6 shrink-0 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900"
                            role="status"
                        >
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div
                            class="mb-6 shrink-0 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-900"
                            role="alert"
                        >
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Stats --}}
                    <section class="mb-8 shrink-0">
                        <h2 class="sr-only">Update statistics</h2>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 xl:grid-cols-6">
                            @php
                                $statTiles = [
                                    ['label' => 'Total tenants', 'value' => $stats['total_tenants'] ?? 0],
                                    ['label' => 'On latest', 'value' => $stats['tenants_on_latest'] ?? 0],
                                    ['label' => 'Pending latest', 'value' => $stats['tenants_pending_latest'] ?? 0],
                                    ['label' => 'Required overdue', 'value' => $stats['tenants_required_overdue'] ?? 0],
                                    ['label' => 'Failed updates', 'value' => $stats['tenants_with_failed_updates'] ?? 0],
                                    ['label' => 'Latest tag', 'value' => $stats['latest_release_tag'] ?? 'N/A'],
                                ];
                            @endphp
                            @foreach ($statTiles as $tile)
                                <div class="updates-surface p-4">
                                    <div class="text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">
                                        {{ $tile['label'] }}
                                    </div>
                                    <div class="mt-1 truncate text-lg font-bold text-slate-900 sm:text-xl" title="{{ is_scalar($tile['value']) ? (string) $tile['value'] : '' }}">
                                        {{ $tile['value'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Releases (fills remaining width) --}}
                    <section class="flex min-h-0 flex-1 flex-col gap-4" aria-labelledby="releases-heading">
                        <h2 id="releases-heading" class="text-lg font-semibold text-slate-900">
                            Releases
                        </h2>

                        <div class="flex min-h-0 flex-1 flex-col gap-4">
                            @forelse ($releases as $release)
                                <article class="updates-surface p-5 sm:p-6">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="min-w-0 space-y-1">
                                            <p class="font-mono text-base font-bold text-slate-900 sm:text-lg">
                                                {{ $release->tag }}
                                            </p>
                                            <p class="text-sm font-semibold text-slate-700 sm:text-base">
                                                {{ $release->title }}
                                            </p>
                                            <p class="text-xs text-slate-500 sm:text-sm">
                                                Published:
                                                {{ optional($release->published_at)->format('M d, Y h:i A') ?: 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="flex shrink-0 flex-wrap gap-2">
                                            @if ($release->is_required)
                                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-900 ring-1 ring-amber-200/80">
                                                    Required
                                                </span>
                                            @endif
                                            @if (! $release->is_stable)
                                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-700 ring-1 ring-slate-200/80">
                                                    Pre-release
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-5 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:flex-wrap sm:items-center">
                                        <form
                                            class="flex flex-wrap items-center gap-2"
                                            method="POST"
                                            action="{{ route('admin.releases.required', ['release' => $release], false) }}"
                                        >
                                            @csrf
                                            <label class="sr-only" for="grace-{{ $release->getKey() }}">Grace days</label>
                                            <input
                                                id="grace-{{ $release->getKey() }}"
                                                class="w-20 rounded-lg border border-slate-300 px-2 py-2 text-sm text-slate-800 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/30"
                                                type="number"
                                                name="grace_days"
                                                min="0"
                                                max="60"
                                                value="7"
                                            >
                                            <button type="submit" class="updates-btn updates-btn-warning focus:outline-none focus:ring-2 focus:ring-amber-500/35 focus:ring-offset-1">
                                                Mark required
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.releases.notify-all', ['release' => $release], false) }}">
                                            @csrf
                                            <button type="submit" class="updates-btn updates-btn-primary w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-teal-500/35 focus:ring-offset-1">
                                                Notify all
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.releases.force-mark-all-updated', ['release' => $release], false) }}"
                                            onsubmit="return confirm('Force mark all tenants as updated to this release?');"
                                        >
                                            @csrf
                                            <button type="submit" class="updates-btn updates-btn-danger w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-rose-400/35 focus:ring-offset-1">
                                                Force mark all updated
                                            </button>
                                        </form>

                                        @if ($release->release_url)
                                            <a
                                                href="{{ $release->release_url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="updates-btn updates-btn-secondary w-full sm:w-auto"
                                            >
                                                Open GitHub release
                                            </a>
                                        @endif
                                    </div>
                                </article>
                            @empty
                                <div class="flex flex-1 flex-col items-center justify-center rounded-2xl border border-dashed border-emerald-200 bg-white/60 px-6 py-16 text-center text-slate-600">
                                    <p class="max-w-md text-sm sm:text-base">
                                        No releases synced yet. Use <strong class="text-emerald-900">Sync from GitHub</strong> to import tags and published releases.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <div class="mt-8 border-t border-emerald-100/80 pt-6">
                        {{ $releases->onEachSide(1)->links('admin.releases.pagination') }}
                    </div>
            </div>
        </main>
    </div>
</body>
</html>
