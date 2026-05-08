<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SecureMediaController extends Controller
{
    public function onboardingProof(Request $request, Tenant $tenant): Response
    {
        $user = $request->user();
        abort_unless($user, 403);

        $canView = false;
        if ($user->isAdmin()) {
            $canView = $user->tenant_id === null || (int) $user->tenant_id === (int) $tenant->id;
        } elseif ($user->isOwner()) {
            $canView = (int) ($user->tenant_id ?? 0) === (int) $tenant->id
                || (int) optional($user->ownedTenant)->id === (int) $tenant->id;
        }

        abort_unless($canView, 403);
        abort_unless((string) $tenant->onboarding_gcash_proof_path !== '', 404);

        return $this->streamStoredFile((string) $tenant->onboarding_gcash_proof_path);
    }

    public function bookingProof(Request $request, Booking $booking): Response
    {
        $user = $request->user();
        abort_unless($user, 403);
        abort_unless($user->can('view', $booking), 403);

        $currentTenant = Tenant::current();
        if ($currentTenant && (int) $booking->tenant_id !== (int) $currentTenant->id) {
            abort(404);
        }

        abort_unless((string) $booking->gcash_payment_proof_path !== '', 404);

        return $this->streamStoredFile((string) $booking->gcash_payment_proof_path);
    }

    private function streamStoredFile(string $path): Response
    {
        $path = ltrim($path, '/');
        $disksToTry = ['local', 'public'];

        foreach ($disksToTry as $disk) {
            if (! Storage::disk($disk)->exists($path)) {
                continue;
            }

            $absolutePath = Storage::disk($disk)->path($path);
            abort_unless(is_file($absolutePath), 404);

            return response()->file($absolutePath, [
                'Cache-Control' => 'private, no-store, max-age=0',
                'Pragma' => 'no-cache',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }

        abort(404);
    }
}
