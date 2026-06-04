<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OnboardingGcashSettingsController;
use App\Http\Controllers\Admin\ReleaseController as AdminReleaseController;
use App\Http\Controllers\Admin\UpdateTicketController as CentralAdminUpdateTicketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UpdateTicketController;
use Illuminate\Support\Facades\Route;

/*
| Central portal — admin (CA) routes (portal.port:admin)
| Municipality / platform admin dashboard, tenants, releases, support.
*/

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
