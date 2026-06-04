<?php

use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\NotificationBellController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\TenantUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant\ModuleController as TenantModuleController;
use App\Http\Controllers\Tenant\SettingsController as TenantSettingsController;
use App\Http\Controllers\TenantLandingController;
use App\Http\Controllers\UpdateTicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$centralDomain = env('CENTRAL_DOMAIN', parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost');
$registerCentralRoutes = function () {
    require __DIR__.'/_central_portal_routes.php';
};

$centralHosts = array_values(array_unique([$centralDomain, 'localhost', '127.0.0.1', '::1']));

foreach ($centralHosts as $host) {
    Route::domain($host)->group($registerCentralRoutes);
}

Route::middleware(['tenant.port', 'tenant.context', 'tenant.required', 'tenant.permissions_team', 'tenant.active', 'tenant.session', 'tenant.bandwidth', 'tenant.required_update'])
    ->group(function () {
        Route::get('/', [TenantLandingController::class, 'showPublic'])
            ->name('landing');

        Route::get('/browse-accommodations', function (Request $request) {
            if ($request->user()) {
                return redirect()->route('dashboard', $request->query());
            }

            return redirect()->route('dashboard', $request->query());
        })->name('landing.browse-accommodations');

        Route::get('/dashboard', [ClientDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/accommodations', [\App\Http\Controllers\AccommodationController::class, 'index'])
            ->name('accommodations.index');

        Route::get('/accommodations/{accommodation}', [\App\Http\Controllers\AccommodationController::class, 'show'])
            ->name('accommodations.show');

        Route::middleware(['auth', 'client', 'tenant.client_guest_rbac'])->group(function () {
            Route::post('/accommodations/{accommodation}/book', [\App\Http\Controllers\BookingController::class, 'store'])
                ->middleware('throttle:20,1')
                ->name('accommodations.book');

            Route::prefix('bookings')->name('bookings.')->group(function () {
                Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('index');
                Route::get('/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])->name('show');
                Route::put('/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('cancel');
                Route::post('/{booking}/message', [\App\Http\Controllers\BookingController::class, 'sendMessage'])
                    ->middleware('throttle:30,1')
                    ->name('message');
                Route::get('/{booking}/payment', [\App\Http\Controllers\BookingController::class, 'payment'])->name('payment');
                Route::post('/{booking}/payment/confirm', [\App\Http\Controllers\BookingController::class, 'confirmPayment'])
                    ->middleware('throttle:20,1')
                    ->name('payment.confirm');
                Route::get('/{booking}/payment/success', [\App\Http\Controllers\BookingController::class, 'paymentSuccess'])->name('payment.success');
                Route::get('/{booking}/payment/cancel', [\App\Http\Controllers\BookingController::class, 'paymentCancel'])->name('payment.cancel');
                Route::post('/{booking}/payment-proof', [\App\Http\Controllers\BookingController::class, 'uploadPaymentProof'])
                    ->middleware('throttle:10,1')
                    ->name('payment-proof.upload');
                Route::delete('/{booking}/payment-proof', [\App\Http\Controllers\BookingController::class, 'removePaymentProof'])->name('payment-proof.remove');
            });

            Route::get('/home', function () {
                return redirect()->route('dashboard');
            })->name('home');
        });

        // Tenant admin dashboard alias for views shared with central app.
        Route::middleware(['auth', 'tenant.manager'])->group(function () {
            Route::get('/admin/dashboard', [OwnerDashboardController::class, 'index'])
                ->name('tenant.admin.dashboard');
        });

        // Tenant manager routes (same owner pages/functions, available to owner or tenant admin)
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

            Route::get('/accommodations', [\App\Http\Controllers\AccommodationController::class, 'ownerIndex'])
                ->name('accommodations.index');
            Route::resource('/accommodations', \App\Http\Controllers\AccommodationController::class)
                ->except(['index']);
            Route::resource('/modules', TenantModuleController::class);

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

        // Canonical tenant settings updates routes (per two-layer update spec).
        Route::middleware(['auth', 'tenant.manager'])->group(function () {
            Route::get('/settings/updates', [TenantSettingsController::class, 'index'])->name('settings.updates.index');
            Route::post('/settings/updates/apply', [TenantSettingsController::class, 'applyUpdate'])->name('settings.updates.apply');
        });

        Route::middleware(['auth'])->group(function () {
            Route::middleware('throttle:120,1')->group(function () {
                Route::get('/notifications', [NotificationBellController::class, 'index']);
                Route::post('/notifications/read-all', [NotificationBellController::class, 'markAllRead']);
                Route::post('/notifications/{id}/read', [NotificationBellController::class, 'markRead'])
                    ->where('id', '[0-9a-fA-F-]{36}');
            });

            Route::middleware('throttle:60,1')->group(function () {
                Route::get('/secure-media/onboarding-proof/{tenant}', [\App\Http\Controllers\SecureMediaController::class, 'onboardingProof']);
                Route::get('/secure-media/booking-proof/{booking}', [\App\Http\Controllers\SecureMediaController::class, 'bookingProof']);
            });
        });

        Route::middleware(['auth', 'tenant.client_guest_rbac'])->group(function () {
            Route::prefix('update-tickets')->name('update-tickets.')->group(function () {
                Route::get('/', [UpdateTicketController::class, 'clientIndex'])->name('index');
                Route::post('/', [UpdateTicketController::class, 'clientStore'])
                    ->middleware('throttle:10,1')
                    ->name('store');
                Route::get('/{updateTicket}', [UpdateTicketController::class, 'clientShow'])->name('show');
            });

            Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])
                ->name('messages.index');

            Route::get('/messages/create', [\App\Http\Controllers\MessageController::class, 'create'])
                ->name('messages.create');

            Route::post('/messages/mark-all-read', [\App\Http\Controllers\MessageController::class, 'markAllAsRead'])
                ->middleware('throttle:30,1')
                ->name('messages.mark-all-read');

            Route::get('/messages/{message}', [\App\Http\Controllers\MessageController::class, 'show'])
                ->name('messages.show');

            Route::middleware('throttle:30,1')->group(function () {
                Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store'])
                    ->name('messages.store');

                Route::post('/messages/{message}/reply', [\App\Http\Controllers\MessageController::class, 'reply'])
                    ->name('messages.reply');
            });

            Route::middleware('throttle:120,1')->group(function () {
                Route::put('/messages/{message}/read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])
                    ->name('messages.mark-read');

                Route::put('/messages/{message}/archive', [\App\Http\Controllers\MessageController::class, 'archive'])
                    ->name('messages.archive');

                Route::delete('/messages/{message}', [\App\Http\Controllers\MessageController::class, 'destroy'])
                    ->name('messages.destroy');
            });
        });

        Route::middleware(['auth', 'tenant.client_guest_rbac'])->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

        require __DIR__.'/auth.php';
    });
