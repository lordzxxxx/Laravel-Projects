<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\NotificationBellController;
use App\Http\Controllers\Portal\PublicLandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecureMediaController;
use App\Models\Tenant;
use App\Support\PortalDetector;
use Illuminate\Support\Facades\Route;

/*
| Central portal — shared routes (portal.port:any)
| Landing, auth, explore, dashboard router, profile, messages.
*/

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

        Route::get('/secure-media/onboarding-proof/{tenant}', [SecureMediaController::class, 'onboardingProof'])
            ->name('secure-media.onboarding-proof');
        Route::get('/secure-media/municipality-document/{tenant}/{document}', [SecureMediaController::class, 'municipalityDocument'])
            ->whereIn('document', array_keys(Tenant::MUNICIPALITY_DOCUMENTS))
            ->name('secure-media.municipality-document');
        Route::get('/secure-media/booking-proof/{booking}', [SecureMediaController::class, 'bookingProof'])
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
