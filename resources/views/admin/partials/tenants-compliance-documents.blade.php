@php
    $submittedAt = $tenant->municipality_requirements_submitted_at;
    $hasAnyDoc = $tenant->hasMunicipalityDocuments();
@endphp
@if($hasAnyDoc || $submittedAt)
    <div class="mb-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="flex min-w-0 items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-700 ring-1 ring-slate-200">
                    <i class="fa-solid fa-file-shield" aria-hidden="true"></i>
                </span>
                <div class="min-w-0">
                    <p class="text-[13px] font-semibold leading-tight text-slate-900">Compliance documents</p>
                    <p class="mt-0.5 text-[11px] leading-snug text-slate-600">
                        Uploaded during owner registration (business permit, mayor's permit, barangay clearance, government ID).
                        @if($submittedAt)
                            Submitted {{ $submittedAt->timezone(config('app.timezone'))->format('M j, Y g:i A') }}.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <ul class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
            @foreach(\App\Models\Tenant::MUNICIPALITY_DOCUMENTS as $key => $meta)
                @php
                    $path = (string) ($tenant->{$meta['column']} ?? '');
                    $docUrl = $tenant->municipalityDocumentUrl($key);
                    $isImage = $path !== '' && preg_match('/\.(jpe?g|png|gif|webp)$/i', $path);
                @endphp
                <li class="flex items-center justify-between gap-2 rounded-lg border border-slate-100 bg-slate-50/80 px-3 py-2.5">
                    <span class="min-w-0 text-[12px] font-medium text-slate-800">{{ $meta['label'] }}</span>
                    @if($docUrl)
                        <a href="{{ $docUrl }}" target="_blank" rel="noopener"
                           class="inline-flex shrink-0 items-center gap-1 rounded-full bg-emerald-600 px-2.5 py-1 text-[11px] font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            <i class="fa-solid {{ $isImage ? 'fa-image' : 'fa-file-pdf' }} text-[10px]" aria-hidden="true"></i>
                            View
                        </a>
                    @else
                        <span class="shrink-0 text-[11px] font-medium text-slate-400">Not uploaded</span>
                    @endif
                </li>
            @endforeach
        </ul>

        @if($tenant->municipality_admin_review_notes)
            <p class="mt-3 rounded-md bg-slate-50 px-2.5 py-2 text-[11px] leading-snug text-slate-700 ring-1 ring-slate-200/80">
                <span class="font-semibold text-slate-800">Owner notes:</span>
                {{ $tenant->municipality_admin_review_notes }}
            </p>
        @endif
    </div>
@endif
