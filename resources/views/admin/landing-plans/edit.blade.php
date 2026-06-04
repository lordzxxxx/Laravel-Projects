@php
    /** @var \App\Models\CentralLandingPlan $plan */
    /** @var bool $isCreate */
    $catalogLines = \App\Models\CentralLandingPlan::mergedTierCatalogFeatureLines();
    $defaultFeatureMode = old('feature_mode');
    if ($defaultFeatureMode === null) {
        $defaultFeatureMode = $plan->featureSelectionMode();
    }
    $pickedLines = old('feature_pick', $plan->features ?? []);
    if (! is_array($pickedLines)) {
        $pickedLines = [];
    }
    if ($isCreate && old('feature_mode') === null) {
        $defaultFeatureMode = 'custom_pick';
    }
    if ($isCreate && old('feature_pick') === null) {
        $pickedLines = $catalogLines;
    }
    $twField = 'w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/25';
    $twCheck = 'h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('admin.partials.favicon')
    <title>{{ $isCreate ? 'Add plan' : 'Edit plan' }} · Plan management</title>
    @vite(['resources/css/app.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @include('admin.partials.admin-shell-styles')
        @include('partials.ui-foundation-styles')
        .landing-plan-editor {
            max-width: min(1080px, 100%);
            margin-left: auto;
            margin-right: auto;
            width: 100%;
            padding-bottom: 48px;
        }
        .plan-form-shell {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.06), 0 20px 40px -12px rgba(15, 23, 42, 0.12);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .plan-form {
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .plan-panel {
            padding: 1.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .plan-panel:first-of-type {
            padding-top: 0;
        }
        .plan-panel:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
        }
        .plan-panel-header {
            margin-bottom: 1.25rem;
        }
        .plan-panel-title {
            font-size: 0.8125rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #0f172a;
            margin: 0 0 0.35rem 0;
        }
        .plan-panel-desc {
            margin: 0;
            font-size: 0.8125rem;
            line-height: 1.55;
            color: #64748b;
            max-width: 62ch;
        }
        .plan-grid-basics {
            display: grid;
            gap: 1.125rem 1.5rem;
            grid-template-columns: 1fr;
        }
        @media (min-width: 768px) {
            .plan-grid-basics {
                grid-template-columns: 1fr 1fr;
            }
            .plan-span-full {
                grid-column: 1 / -1;
            }
        }
        .form-label {
            margin-bottom: 0.45rem;
            margin-top: 0;
            display: block;
            font-size: 0.8125rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #0f172a;
        }
        .helper-copy {
            margin-top: 0.5rem;
            font-size: 0.8125rem;
            color: #64748b;
            line-height: 1.55;
            max-width: 70ch;
        }
        .radio-stack {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1rem;
        }
        .radio-row {
            margin-top: 0;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border: 1px solid #e2e8f0;
            background: #fff;
            border-radius: 14px;
            padding: 14px 16px;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
        }
        .radio-row:hover {
            border-color: #cbd5e1;
            background: #fafafa;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
        }
        #feature_pick_panel {
            margin-top: 1rem;
            border: 1px solid #e2e8f0 !important;
            background: #f8fafc !important;
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04);
            border-radius: 14px !important;
            padding: 14px 16px !important;
            max-height: min(520px, 62vh);
            overflow-y: auto;
            display: grid;
            gap: 6px 14px;
            grid-template-columns: 1fr;
        }
        @media (min-width: 640px) {
            #feature_pick_panel {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        .plan-feature-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 0;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.45;
            transition: background 0.12s ease;
        }
        .plan-feature-check:hover {
            background: rgba(16, 185, 129, 0.08);
        }
        .plan-note-box {
            margin-top: 1rem;
            padding: 12px 14px;
            border-radius: 12px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            font-size: 0.75rem;
            line-height: 1.55;
            color: #475569;
            max-width: 72ch;
        }
        .plan-visibility-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 0.25rem;
        }
        @media (min-width: 640px) {
            .plan-visibility-grid {
                flex-direction: row;
                flex-wrap: wrap;
                align-items: stretch;
                gap: 14px;
            }
            .plan-visibility-grid > label {
                flex: 1;
                min-width: min(260px, 100%);
            }
        }
        .plan-toggle-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: #fff;
            cursor: pointer;
            transition: border-color 0.15s ease, background 0.15s ease;
        }
        .plan-toggle-card:hover {
            border-color: #94a3b8;
            background: #fafafa;
        }
        .plan-toggle-card span.toggle-text {
            font-size: 0.875rem;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.35;
        }
        .plan-action-row {
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }
        .plan-action-row .btn-admin-primary {
            background: #0f766e;
            border: 1px solid rgba(15, 118, 110, 0.3);
            box-shadow: 0 6px 14px rgba(15, 118, 110, 0.22);
        }
        .plan-action-row .btn-admin-primary:hover {
            background: #115e59;
        }
        .plan-action-row .btn-admin-secondary {
            border-color: #cbd5e1;
            color: #334155;
            background: #fff;
        }
        .plan-back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            font-weight: 700;
            font-size: 0.875rem;
            color: #0f766e;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 999px;
            background: #fff;
            border: 1px solid #ccfbf1;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
            transition: background 0.15s ease, border-color 0.15s ease;
        }
        .plan-back-link:hover {
            background: #f0fdfa;
            border-color: #99f6e4;
        }
        @media (min-width: 768px) {
            .landing-plan-editor .plan-form-shell.card-padded {
                padding: 2rem 2rem;
            }
        }
        @media (min-width: 1024px) {
            .landing-plan-editor .plan-form-shell.card-padded {
                padding: 2.25rem 2.5rem;
            }
        }
    </style>
</head>
<body class="admin-central-portal">
    @include('admin.partials.top-navbar', ['active' => 'landing-plans'])

    <div class="dashboard-layout">
        <main class="main-content landing-plan-editor">
            <div class="page-header">
                <h1>{{ $isCreate ? 'Add plan' : 'Edit plan' }}</h1>
                <p style="margin:0;"><a href="{{ route('admin.landing-plans.index', [], false) }}" class="plan-back-link"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i><span>Back to plans</span></a></p>
            </div>

            <div class="card card-padded plan-form-shell">
                @if(!$isCreate)
                    <div class="mb-8 rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50/95 to-teal-50/70 p-5 text-sm shadow-sm ring-1 ring-emerald-500/10">
                        <strong style="color: var(--green-dark);">Live preview (as on CA landing)</strong>
                        @php $ep = $plan->effectivePrice(); @endphp
                        <p class="mt-2 text-gray-800">
                            {{ $plan->effectiveTitle() }} — {{ $plan->effectiveCurrency() }}{{ number_format($ep, floor($ep) == $ep ? 0 : 2) }}
                        </p>
                        <ul class="mt-3 list-none space-y-2 text-xs text-gray-700">
                            @foreach($plan->effectiveFeatures() as $f)
                                <li class="flex items-start gap-2">
                                    <span class="mt-0.5 inline-flex h-[18px] w-[18px] shrink-0 items-center justify-center rounded-full border-2 border-emerald-200 bg-white text-[10px] text-emerald-700">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                    <span>{{ $f }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ $isCreate ? route('admin.landing-plans.store', [], false) : route('admin.landing-plans.update', ['central_landing_plan' => $plan->getKey()], false) }}" class="plan-form">
                    @csrf
                    @if(!$isCreate)
                        @method('PUT')
                    @endif

                    <section class="plan-panel" aria-labelledby="plan-details-heading">
                        <header class="plan-panel-header">
                            <h2 id="plan-details-heading" class="plan-panel-title">Plan &amp; pricing</h2>
                            <p class="plan-panel-desc">Name and tier shown on the public card; optional override price and display order on the landing page.</p>
                        </header>
                        <div class="plan-grid-basics">
                            <div class="plan-span-full">
                                <label for="title" class="form-label">Plan name (shown on card)</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $plan->title) }}" maxlength="255" required placeholder="e.g. Summer Owner Promo" class="{{ $twField }}">
                                @error('title')<div class="mt-1 text-sm text-red-700">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="tenant_plan_key" class="form-label">Subscription tier</label>
                                <p class="helper-copy" style="margin-top:0;margin-bottom:0.5rem;">Register link &amp; catalog price source.</p>
                                <select id="tenant_plan_key" name="tenant_plan_key" required class="{{ $twField }}">
                                    @foreach(\App\Models\CentralLandingPlan::ALLOWED_PLAN_KEYS as $key)
                                        <option value="{{ $key }}" @selected(old('tenant_plan_key', $plan->tenant_plan_key ?? 'basic') === $key)>
                                            {{ $key }} ({{ \App\Models\Tenant::planLabel($key) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tenant_plan_key')<div class="mt-1 text-sm text-red-700">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="sort_order" class="form-label">Sort order</label>
                                <p class="helper-copy" style="margin-top:0;margin-bottom:0.5rem;">Lower numbers appear first on the landing page.</p>
                                <input type="number" id="sort_order" name="sort_order" min="0" max="32767" value="{{ old('sort_order', $plan->sort_order) }}" required class="{{ $twField }}">
                                @error('sort_order')<div class="mt-1 text-sm text-red-700">{{ $message }}</div>@enderror
                            </div>
                            <div class="plan-span-full">
                                <label for="price_amount" class="form-label">Card price <span style="font-weight:600;color:#64748b;">(optional)</span></label>
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="text-sm font-semibold text-slate-600 shrink-0">{{ $plan->effectiveCurrency() }}</span>
                                    <input type="number" id="price_amount" name="price_amount" value="{{ old('price_amount', $plan->price_amount) }}" min="0" max="999999.99" step="0.01" placeholder="Leave blank for catalog" class="{{ $twField }} min-w-0 flex-1 max-w-xl">
                                </div>
                                <p class="helper-copy">Shown on the public card. If left blank, the card uses the standard catalog price for the selected tier. Currency always matches that tier.</p>
                                @error('price_amount')<div class="mt-1 text-sm text-red-700">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="plan-note-box">
                            Owner signup still uses the selected tier’s
                            <code class="rounded bg-white px-1.5 py-0.5 font-mono text-[11px] text-slate-700 ring-1 ring-slate-200/80">plan</code>
                            query parameter (Basic / Standard / Premium / Promo).
                        </div>
                    </section>

                    <section class="plan-panel" aria-labelledby="plan-features-heading">
                        <header class="plan-panel-header">
                            <h2 id="plan-features-heading" class="plan-panel-title">Landing card features</h2>
                            <p class="plan-panel-desc">Choose how the bullet checklist is built. The landing button always reads <strong class="text-slate-700">Register</strong>.</p>
                        </header>
                        @error('feature_mode')<div class="mb-3 text-sm text-red-700">{{ $message }}</div>@enderror
                        @error('feature_pick')<div class="mb-3 text-sm text-red-700">{{ $message }}</div>@enderror

                        <div class="radio-stack" role="radiogroup" aria-label="Feature mode">
                            <div class="radio-row">
                                <input type="radio" name="feature_mode" id="feature_mode_tier" value="tier_catalog" @checked($defaultFeatureMode === 'tier_catalog') class="mt-1 h-4 w-4 shrink-0 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <label for="feature_mode_tier" class="m-0 cursor-pointer text-sm font-semibold leading-snug text-slate-900">
                                    This tier only
                                    <span class="mt-1 block text-xs font-normal leading-relaxed text-slate-500">Same bullet list as the standard checklist for the selected tier (e.g. Basic’s three lines).</span>
                                </label>
                            </div>
                            <div class="radio-row">
                                <input type="radio" name="feature_mode" id="feature_mode_full" value="full_catalog" @checked($defaultFeatureMode === 'full_catalog') class="mt-1 h-4 w-4 shrink-0 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <label for="feature_mode_full" class="m-0 cursor-pointer text-sm font-semibold leading-snug text-slate-900">
                                    All tiers (Basic → Standard → Premium → Promo)
                                    <span class="mt-1 block text-xs font-normal leading-relaxed text-slate-500">Full combined checklist in catalog order.</span>
                                </label>
                            </div>
                            <div class="radio-row">
                                <input type="radio" name="feature_mode" id="feature_mode_custom_pick" value="custom_pick" @checked($defaultFeatureMode === 'custom_pick') class="mt-1 h-4 w-4 shrink-0 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <label for="feature_mode_custom_pick" class="m-0 cursor-pointer text-sm font-semibold leading-snug text-slate-900">
                                    Choose bullets
                                    <span class="mt-1 block text-xs font-normal leading-relaxed text-slate-500">Use the checkboxes below to include only the lines you want (catalog lines only).</span>
                                </label>
                            </div>
                        </div>

                        <div class="plan-note-box">
                            If you pick <strong class="text-slate-800">This tier only</strong> or <strong class="text-slate-800">All tiers</strong>, checkbox choices are ignored when you save.
                        </div>

                        <div id="feature_pick_panel" role="group" aria-label="Catalog features to include">
                            @foreach($catalogLines as $line)
                                <label class="plan-feature-check">
                                    <input type="checkbox" name="feature_pick[]" value="{{ e($line) }}" @checked(in_array($line, $pickedLines, true)) class="mt-0.5 h-[18px] w-[18px] shrink-0 cursor-pointer rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="min-w-0 flex-1">{{ $line }}</span>
                                </label>
                            @endforeach
                        </div>
                    </section>

                    <section class="plan-panel" aria-labelledby="plan-publish-heading">
                        <header class="plan-panel-header">
                            <h2 id="plan-publish-heading" class="plan-panel-title">Visibility</h2>
                            <p class="plan-panel-desc">Control whether this card appears on the central landing page and if it is highlighted.</p>
                        </header>
                        <div class="plan-visibility-grid">
                            <label class="plan-toggle-card" for="is_visible">
                                <input type="checkbox" id="is_visible" name="is_visible" value="1" @checked(old('is_visible', $plan->is_visible)) class="{{ $twCheck }} mt-0.5 shrink-0">
                                <span class="toggle-text">Visible on CA landing<br><span style="font-weight:500;font-size:0.75rem;color:#64748b;">Show this plan on the public pricing grid.</span></span>
                            </label>
                            <label class="plan-toggle-card" for="is_featured">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $plan->is_featured)) class="{{ $twCheck }} mt-0.5 shrink-0">
                                <span class="toggle-text">Featured / highlighted card<br><span style="font-weight:500;font-size:0.75rem;color:#64748b;">Only one plan should be featured at a time.</span></span>
                            </label>
                        </div>

                        @if(!$isCreate)
                            <div class="mt-6 rounded-xl border border-amber-100 bg-amber-50/60 p-4">
                                <label class="flex cursor-pointer items-start gap-3">
                                    <input type="checkbox" id="clear_catalog_overrides" name="clear_catalog_overrides" value="1" class="{{ $twCheck }} mt-0.5 shrink-0">
                                    <span class="text-sm font-semibold leading-snug text-slate-900">
                                        Reset features to this tier only
                                        <span class="mt-1 block text-xs font-normal text-slate-600">Clears custom bullet picks and full-tier mode; the card uses only this plan’s tier checklist.</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                    </section>

                    <div class="plan-action-row">
                        <button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('admin.landing-plans.index', [], false) }}" class="btn-admin-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
