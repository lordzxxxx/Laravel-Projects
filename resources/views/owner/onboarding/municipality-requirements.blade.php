<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Resubmit documents - ImpaStay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @include('partials.app-typography-styles')
        body { min-height: 100vh; background: linear-gradient(135deg, #E8F5E9, #fff); }
        .card { max-width: min(520px, 100%); margin: 3rem auto; padding: clamp(1rem, 4vw, 2rem); background: #fff; border-radius: 1rem; box-shadow: 0 10px 40px rgba(27,94,32,0.12); border: 1px solid #C8E6C9; }
        label { display: block; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #374151; margin-top: 1rem; }
        input[type="file"] { margin-top: 0.35rem; width: 100%; max-width: 100%; font-size: 0.875rem; }
        @media (max-width: 768px) {
            .card { margin: 1.25rem auto; width: calc(100% - 1.5rem); }
            body { overflow-x: hidden; }
        }
    </style>
</head>
<body>
    <div class="card">
        <h1 class="text-xl font-bold text-green-900">Resubmit municipality documents</h1>
        <p class="mt-2 text-sm text-gray-600">Upload clear copies of each requirement (PDF or image). We will notify you once re-review is complete.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('owner.onboarding.requirements.submit') }}" enctype="multipart/form-data" class="mt-6 space-y-1">
            @csrf
            @foreach ([
                'business_permit' => 'Business permit',
                'mayors_permit' => "Mayor's permit",
                'barangay_clearance' => 'Barangay clearance',
                'valid_id' => 'Valid government ID',
            ] as $field => $label)
                <div>
                    <label for="{{ $field }}">{{ $label }}</label>
                    <input id="{{ $field }}" type="file" name="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
            @endforeach
            <button type="submit" class="mt-6 w-full rounded-lg bg-green-700 py-3 text-sm font-bold text-white shadow hover:bg-green-800">
                Submit for review
            </button>
        </form>

        <p class="mt-6 text-center">
            <a href="{{ route('owner.onboarding.status') }}" class="text-sm font-semibold text-green-700 hover:underline">Back to status</a>
        </p>
    </div>
</body>
</html>
