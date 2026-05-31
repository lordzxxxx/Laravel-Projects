<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OnboardingGcashSettingsController;
use App\Http\Controllers\Admin\ReleaseController as AdminReleaseController;
use App\Http\Controllers\Admin\UpdateTicketController as CentralAdminUpdateTicketController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\NotificationBellController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\MunicipalityOnboardingController;
use App\Http\Controllers\Owner\TenantUserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Portal\FavoriteAccommodationController;
use App\Http\Controllers\Portal\PublicLandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant\SettingsController as TenantSettingsController;
use App\Http\Controllers\TenantLandingController;
use App\Http\Controllers\UpdateTicketController;
use App\Support\PortalDetector;
use Illuminate\Support\Facades\Route;

Route::middleware(['portal.port:admin'])->group(function () {
    Route::middleware(['auth', 'throttle:20,1'])->group(function () {
        Route::get('/checkout', [PaymentController::class, 'showCheckoutForm'])->name('payments.form');
        Route::post('/checkout', [PaymentController::class, 'checkout'])->name('payments.checkout');
        Route::get('/success', [PaymentController::class, 'success'])->name('payments.success');
        Route::get('/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');
    });

    Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])
        ->middleware('throttle:120,1')
        ->name('payments.webhook');
});

Route::middleware('portal.port:any')->group(function () {
    /*
     | Single "/" handler — Laravel keeps only one named route per domain+path pair.
     | Do not duplicate GET / under portal.port:admin vs :public or the landing route overwrites admin.
     */
    Route::get('/', function (\Illuminate\Http\Request $request) {
        return app(PublicLandingController::class)->index($request);
    })->name('portal.landing');

    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.store');

        Route::get('/register/guest', [RegisteredUserController::class, 'createGuest'])->name('register.guest');
        Route::post('/register/guest', [RegisteredUserController::class, 'storeGuest']);

        Route::get('/register/owner', [RegisteredUserController::class, 'createOwner'])->name('register.owner');
        Route::post('/register/owner', [RegisteredUserController::class, 'storeOwner']);

        Route::redirect('/register', '/register/guest')->name('register');
    });

    Route::view('/about', 'portal.about')->name('portal.about');

    Route::get('/browse/directory', function () {
        return redirect()->route('portal.accommodations.index');
    })->name('portal.browse');

    Route::get('/explore/accommodations', [\App\Http\Controllers\AccommodationController::class, 'index'])
        ->name('portal.accommodations.index');

    Route::get('/explore/accommodations/{accommodation}', [\App\Http\Controllers\AccommodationController::class, 'show'])
        ->name('portal.accommodations.show');

    Route::middleware(['auth', 'tenant.context'])->group(function () {
        Route::get('verify-email', EmailVerificationPromptController::class)
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/', [\App\Http\Controllers\MessageController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\MessageController::class, 'create'])->name('create');
            Route::get('/{message}', [\App\Http\Controllers\MessageController::class, 'show'])->name('show');

            Route::middleware('throttle:30,1')->group(function () {
                Route::post('/', [\App\Http\Controllers\MessageController::class, 'store'])->name('store');
                Route::post('/{message}/reply', [\App\Http\Controllers\MessageController::class, 'reply'])->name('reply');
            });

            Route::middleware('throttle:120,1')->group(function () {
                Route::put('/{message}/read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('mark-read');
                Route::put('/{message}/archive', [\App\Http\Controllers\MessageController::class, 'archive'])->name('archive');
                Route::delete('/{message}', [\App\Http\Controllers\MessageController::class, 'destroy'])->name('destroy');
            });
        });

        Route::get('/notifications', [NotificationBellController::class, 'index']);
        Route::post('/notifications/read-all', [NotificationBellController::class, 'markAllRead']);
        Route::post('/notifications/{id}/read', [NotificationBellController::class, 'markRead'])
            ->where('id', '[0-9a-fA-F-]{36}');

        Route::get('/secure-media/onboarding-proof/{tenant}', [\App\Http\Controllers\SecureMediaController::class, 'onboardingProof'])
            ->name('secure-media.onboarding-proof');
        Route::get('/secure-media/booking-proof/{booking}', [\App\Http\Controllers\SecureMediaController::class, 'bookingProof'])
            ->name('secure-media.booking-proof');

        Route::get('/dashboard', function () {
            $user = request()->user();

            if (! $user) {
                return redirect()->route('login');
            }

            if ($user->isAdmin()) {
                return redirect('/admin/dashboard');
            }

            if ($user->isOwner()) {
                return redirect('/owner/dashboard');
            }

            if ($user->isClient()) {
                if (! PortalDetector::isPublicPortal(request())) {
                    abort(403, 'Guests must use the Municipality portal.');
                }

                return redirect()->route('portal.guest.dashboard');
            }

            return redirect()->route('portal.landing');
        })->name('dashboard');

        Route::get('/home', function () {
            return redirect()->route('dashboard');
        })->name('home');
    });
});

Route::middleware('portal.port:admin')->group(function () {
    Route::middleware(['auth', 'tenant.context', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/system-updates', [AdminReleaseController::class, 'index'])->name('updates.index');
        Route::post('/system-updates/sync', [AdminReleaseController::class, 'sync'])->name('releases.sync');
        Route::post('/system-updates/{release}/required', [AdminReleaseController::class, 'markRequired'])->name('releases.required');
        Route::post('/system-updates/{release}/notify-all', [AdminReleaseController::class, 'notifyAll'])->name('releases.notify-all');
        Route::post('/system-updates/{release}/force-mark-all-updated', [AdminReleaseController::class, 'forceMarkAllUpdated'])->name('releases.force-mark-all-updated');
        Route::post('/system-updates/tickets/report', [UpdateTicketController::class, 'ownerStore'])->name('update-tickets.store');
        Route::get('/system-updates/tickets/report/{updateTicket}', [UpdateTicketController::class, 'ownerShow'])->name('update-tickets.staff-show');

        Route::get('/system-updates/tickets', [CentralAdminUpdateTicketController::class, 'index'])->name('update-tickets.index');
        Route::get('/system-updates/tickets/{updateTicket}', [CentralAdminUpdateTicketController::class, 'show'])->name('update-tickets.show');
        Route::patch('/system-updates/tickets/{updateTicket}', [CentralAdminUpdateTicketController::class, 'update'])->name('update-tickets.update');

        Route::get('/tenants', [AdminDashboardController::class, 'tenants'])->name('tenants');
        Route::patch('/tenants/onboarding-gcash', [OnboardingGcashSettingsController::class, 'update'])->name('tenants.onboarding-gcash.update');
        Route::redirect('/onboarding-gcash', '/admin/tenants');
        Route::get('/tenant-lifecycle-logs', [AdminDashboardController::class, 'tenantLifecycleLogs'])->name('tenants.lifecycle-logs');
        Route::get('/users', function () {
            return redirect()->route('admin.tenants');
        })->name('users');
        Route::put('/tenants/{tenant}/subscription', [AdminDashboardController::class, 'updateTenantSubscription'])->name('tenants.update-subscription');
        Route::put('/tenants/{tenant}/plan', [AdminDashboardController::class, 'updateTenantPlan'])->name('tenants.update-plan');
        Route::put('/tenants/{tenant}/profile', [AdminDashboardController::class, 'updateTenantProfile'])->name('tenants.update-profile');
        Route::patch('/tenants/{tenant}/municipality-compliance', [AdminDashboardController::class, 'updateTenantMunicipalityCompliance'])->name('tenants.municipality-compliance');
        Route::put('/tenants/{tenant}/bandwidth-quota', [AdminDashboardController::class, 'updateTenantBandwidthQuota'])->name('tenants.update-bandwidth-quota');
        Route::put('/tenants/{tenant}/domain-status', [AdminDashboardController::class, 'toggleTenantDomain'])->name('tenants.toggle-domain');
        Route::post('/tenants/{tenant}/resend-onboarding-email', [AdminDashboardController::class, 'resendTenantOnboardingEmail'])->name('tenants.resend-onboarding-email');
        Route::post('/tenants/{tenant}/approve-onboarding', [AdminDashboardController::class, 'approveTenantOnboarding'])->name('tenants.approve-onboarding');
        Route::post('/tenants/{tenant}/reject-onboarding', [AdminDashboardController::class, 'rejectTenantOnboarding'])->name('tenants.reject-onboarding');
        Route::delete('/tenants/{tenant}', [AdminDashboardController::class, 'destroyTenant'])->name('tenants.destroy');

        Route::post('/reports/monthly-booking-pdf', [AdminDashboardController::class, 'downloadMonthlyBookingPdf'])->name('monthly-booking-pdf');
        Route::get('/reports/monthly-booking-pdf', [AdminDashboardController::class, 'generateMonthlyBookingReport'])->name('monthly-booking-report');
        Route::get('/reports/demographics', [AdminDashboardController::class, 'demographicsReport'])->name('reports.demographics');
        Route::post('/reports/demographics/export', [AdminDashboardController::class, 'exportDemographicsReport'])->name('reports.demographics.export');

        Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'adminIndex'])->name('messages');
        Route::post('/messages/contact', [\App\Http\Controllers\MessageController::class, 'adminContactUser'])->name('messages.contact');
        Route::get('/messages/{tenant}/{message}', [\App\Http\Controllers\MessageController::class, 'adminShow'])
            ->whereNumber('message')
            ->name('messages.thread');
        Route::delete('/messages/{tenant}/{message}', [\App\Http\Controllers\MessageController::class, 'adminDestroy'])
            ->whereNumber('message')
            ->name('messages.destroy');
        Route::post('/messages/{tenant}/{message}/reply', [\App\Http\Controllers\MessageController::class, 'adminReply'])
            ->whereNumber('message')
            ->name('messages.support-reply');

        Route::prefix('owner/accommodations')->name('owner.accommodations.')->group(function () {
            Route::get('/', [\App\Http\Controllers\AccommodationController::class, 'ownerIndex'])->name('index');
            Route::get('/{accommodation}', [\App\Http\Controllers\AccommodationController::class, 'show'])->name('show');
        });
    });
});

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
