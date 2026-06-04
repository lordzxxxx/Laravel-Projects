<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\TenantUserController;
use App\Http\Controllers\Tenant\ModuleController as TenantModuleController;
use App\Http\Controllers\Tenant\SettingsController as TenantSettingsController;
use App\Http\Controllers\TenantLandingController;
use App\Http\Controllers\UpdateTicketController;
use Illuminate\Support\Facades\Route;

/*
| Tenant host — owner / tenant manager routes
| Owner portal, accommodations CRUD, bookings management, users.
*/

Route::middleware(['auth', 'tenant.manager'])->group(function () {
    Route::get('/admin/dashboard', [OwnerDashboardController::class, 'index'])
        ->name('tenant.admin.dashboard');
});

Route::middleware(['auth', 'tenant.manager'])->prefix('owner')->name('owner.')->group(function () {
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

    Route::get('/accommodations', [AccommodationController::class, 'ownerIndex'])
        ->name('accommodations.index');
    Route::resource('/accommodations', AccommodationController::class)
        ->except(['index']);
    Route::resource('/modules', TenantModuleController::class);

    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::put('/{booking}/status', [BookingController::class, 'updateStatus'])->name('update-status');
        Route::put('/{booking}/mark-paid', [BookingController::class, 'markAsPaid'])->name('mark-paid');
        Route::put('/{booking}/complete', [BookingController::class, 'complete'])->name('complete');
        Route::post('/{booking}/message', [BookingController::class, 'sendMessage'])->name('message');
        Route::post('/payment-settings/gcash-qr', [BookingController::class, 'uploadTenantGcashQr'])->name('payment-settings.gcash-qr.upload');
        Route::delete('/payment-settings/gcash-qr', [BookingController::class, 'removeTenantGcashQr'])->name('payment-settings.gcash-qr.remove');
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

// Canonical tenant settings updates routes (per two-layer update spec).
Route::middleware(['auth', 'tenant.manager'])->group(function () {
    Route::get('/settings/updates', [TenantSettingsController::class, 'index'])->name('settings.updates.index');
    Route::post('/settings/updates/apply', [TenantSettingsController::class, 'applyUpdate'])->name('settings.updates.apply');
});
