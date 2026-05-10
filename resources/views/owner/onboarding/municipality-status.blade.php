<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Municipality review - ImpaStay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #E8F5E9, #fff); font-family: system-ui, sans-serif; color: #1b5e20; }
        .card { max-width: 640px; margin: 4rem auto; padding: 2rem; background: #fff; border-radius: 1rem; box-shadow: 0 10px 40px rgba(27,94,32,0.12); border: 1px solid #C8E6C9; }
    </style>
</head>
<body>
    <div class="card">
        @include('partials.flash-alerts')

        <h1 class="text-2xl font-bold text-green-900">Host application status</h1>

        @php($status = (string) $tenant->onboarding_status)

        @if($status === \App\Models\Tenant::ONBOARDING_PENDING_APPROVAL)
            <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-950">
                <p class="font-semibold"><i class="fas fa-clock mr-2"></i>Under municipality review</p>
                <p class="mt-2 text-sm">Your documents were received on {{ optional($tenant->municipality_requirements_submitted_at)->timezone(config('app.timezone'))->format('M j, Y g:i A') ?? '—' }}. Staff will approve or reach out if anything is missing.</p>
            </div>
        @elseif($status === \App\Models\Tenant::ONBOARDING_REJECTED)
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-900">
                <p class="font-semibold"><i class="fas fa-times-circle mr-2"></i>Application needs revision</p>
                @if($tenant->municipality_admin_review_notes)
                    <p class="mt-2 text-sm">{{ $tenant->municipality_admin_review_notes }}</p>
                @endif
                <a href="{{ route('owner.onboarding.requirements') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-800">
                    <i class="fas fa-upload"></i> Resubmit documents
                </a>
            </div>
        @else
            <p class="mt-4 text-gray-700">Status: <strong>{{ $status }}</strong></p>
        @endif

        <p class="mt-8 text-center text-sm text-gray-600">
            <form action="/logout" method="POST" class="inline">@csrf<button type="submit" class="text-green-700 underline">Sign out</button></form>
        </p>
    </div>
</body>
</html>
