<?php

use App\Http\Controllers\NotificationBellController;
use App\Http\Controllers\SecureMediaController;
use Illuminate\Support\Facades\Route;

/*
| Tenant host — shared authenticated routes
| Notifications and secure media (all authenticated roles).
*/

Route::middleware(['auth'])->group(function () {
    Route::middleware('throttle:120,1')->group(function () {
        Route::get('/notifications', [NotificationBellController::class, 'index']);
        Route::post('/notifications/read-all', [NotificationBellController::class, 'markAllRead']);
        Route::post('/notifications/{id}/read', [NotificationBellController::class, 'markRead'])
            ->where('id', '[0-9a-fA-F-]{36}');
    });

    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/secure-media/onboarding-proof/{tenant}', [SecureMediaController::class, 'onboardingProof']);
        Route::get('/secure-media/booking-proof/{booking}', [SecureMediaController::class, 'bookingProof']);
    });
});
