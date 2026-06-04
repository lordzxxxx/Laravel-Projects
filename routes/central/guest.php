<?php

use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\MunicipalityOnboardingController;
use App\Http\Controllers\Owner\TenantUserController;
use App\Http\Controllers\Portal\FavoriteAccommodationController;
use App\Http\Controllers\Tenant\SettingsController as TenantSettingsController;
use App\Http\Controllers\TenantLandingController;
use App\Http\Controllers\UpdateTicketController;
use Illuminate\Support\Facades\Route;

/*
| Central portal — guest (public port) routes (portal.port:public)
| Municipality guest stays, bookings, and onboarded owner management.
*/

Route::middleware('portal.port:public')->group(function () {
    Route::middleware(['auth', 'tenant.context', 'client', 'tenant.client_guest_rbac'])
        ->get('/bookings', fn () => redirect()->route('portal.bookings.index'));

    Route::middleware(['auth', 'tenant.context', 'client', 'tenant.client_guest_rbac'])->prefix('guest')->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('portal.guest.dashboard');
        Route::get('/wishlist', [FavoriteAccommodationController::class, 'index'])->name('portal.wishlist.index');
        Route::post('/wishlist/{accommodation}', [FavoriteAccommodationController::class, 'toggle'])
            ->middleware('throttle:60,1')->name('portal.wishlist.toggle');

        Route::post('/stay/{accommodation}/book', [\App\Http\Controllers\BookingController::class, 'store'])
            ->middleware('throttle:20,1')->name('portal.bookings.store');

        Route::prefix('bookings')->name('portal.bookings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('index');
            Route::get('/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])->name('show');
            Route::put('/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('cancel');
            Route::post('/{booking}/message', [\App\Http\Controllers\BookingController::class, 'sendMessage'])
                ->middleware('throttle:30,1')->name('message');
            Route::get('/{booking}/payment', [\App\Http\Controllers\BookingController::class, 'payment'])->name('payment');
            Route::post('/{booking}/payment/confirm', [\App\Http\Controllers\BookingController::class, 'confirmPayment'])
                ->middleware('throttle:20,1')->name('payment.confirm');
            Route::get('/{booking}/payment/success', [\App\Http\Controllers\BookingController::class, 'paymentSuccess'])->name('payment.success');
            Route::get('/{booking}/payment/cancel', [\App\Http\Controllers\BookingController::class, 'paymentCancel'])->name('payment.cancel');
            Route::post('/{booking}/payment-proof', [\App\Http\Controllers\BookingController::class, 'uploadPaymentProof'])
                ->middleware('throttle:10,1')->name('payment-proof.upload');
            Route::delete('/{booking}/payment-proof', [\App\Http\Controllers\BookingController::class, 'removePaymentProof'])->name('payment-proof.remove');
        });
    });

    Route::middleware(['auth', 'tenant.context', 'tenant.permissions_team', 'owner'])->prefix('owner')->group(function () {
        Route::get('/onboarding/status', [MunicipalityOnboardingController::class, 'status'])->name('owner.onboarding.status');
        Route::get('/onboarding/requirements', [MunicipalityOnboardingController::class, 'requirementsForm'])->name('owner.onboarding.requirements');
        Route::post('/onboarding/requirements', [MunicipalityOnboardingController::class, 'updateRequirements'])->name('owner.onboarding.requirements.submit');
    });

    Route::middleware(['auth', 'tenant.context', 'tenant.permissions_team', 'owner', 'owner.onboarded'])->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/reports/monthly', [OwnerDashboardController::class, 'monthlyReport'])->name('reports.monthly');
        Route::get('/reports/monthly/download-sales', [OwnerDashboardController::class, 'downloadMonthlySalesPdf'])->name('reports.monthly.download-sales');
        Route::get('/reports/monthly/download-guests', [OwnerDashboardController::class, 'downloadMonthlyGuestsPdf'])->name('reports.monthly.download-guests');
        Route::get('/settings/updates', [TenantSettingsController::class, 'index'])->name('settings.updates.index');
        Route::post('/settings/updates/apply', [TenantSettingsController::class, 'applyUpdate'])->name('settings.updates.apply');
        Route::post('/update-tickets', [UpdateTicketController::class, 'ownerStore'])->name('update-tickets.store');
        Route::get('/update-tickets/{updateTicket}', [UpdateTicketController::class, 'ownerShow'])->name('update-tickets.show');

        Route::get('/landing-page', [TenantLandingController::class, 'edit'])->name('landing.edit');
        Route::put('/landing-page', [TenantLandingController::class, 'update'])->name('landing.update');

        Route::get('/accommodations', [\App\Http\Controllers\AccommodationController::class, 'ownerIndex'])
            ->name('accommodations.index');
        Route::resource('/accommodations', \App\Http\Controllers\AccommodationController::class)
            ->except(['index']);

        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('index');
            Route::get('/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])->name('show');
            Route::put('/{booking}/status', [\App\Http\Controllers\BookingController::class, 'updateStatus'])->name('update-status');
            Route::put('/{booking}/mark-paid', [\App\Http\Controllers\BookingController::class, 'markAsPaid'])->name('mark-paid');
            Route::put('/{booking}/complete', [\App\Http\Controllers\BookingController::class, 'complete'])->name('complete');
            Route::post('/{booking}/message', [\App\Http\Controllers\BookingController::class, 'sendMessage'])->name('message');
            Route::post('/payment-settings/gcash-qr', [\App\Http\Controllers\BookingController::class, 'uploadTenantGcashQr'])->name('payment-settings.gcash-qr.upload');
            Route::delete('/payment-settings/gcash-qr', [\App\Http\Controllers\BookingController::class, 'removeTenantGcashQr'])->name('payment-settings.gcash-qr.remove');
        });

        Route::get('/users', [TenantUserController::class, 'index'])->name('users.index');
        Route::post('/users', [TenantUserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [TenantUserController::class, 'update'])->name('users.update');
        Route::put('/users/{user}/permissions', [TenantUserController::class, 'updatePermissions'])->name('users.permissions');
        Route::put('/users/{user}/activate', [TenantUserController::class, 'toggleActive'])->name('users.activate');
        Route::post('/users/custom-roles', [TenantUserController::class, 'storeCustomRole'])->name('users.custom-roles.store');
        Route::put('/users/custom-roles/{tenantCustomRole}', [TenantUserController::class, 'updateCustomRole'])->name('users.custom-roles.update');
        Route::delete('/users/custom-roles/{tenantCustomRole}', [TenantUserController::class, 'destroyCustomRole'])->name('users.custom-roles.destroy');
    });
});
