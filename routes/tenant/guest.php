<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpdateTicketController;
use Illuminate\Support\Facades\Route;

/*
| Tenant host — guest / client routes
| Bookings, messages, profile, support tickets (tenant.client_guest_rbac).
*/

Route::middleware(['auth', 'client', 'tenant.client_guest_rbac'])->group(function () {
    Route::post('/accommodations/{accommodation}/book', [BookingController::class, 'store'])
        ->middleware('throttle:20,1')
        ->name('accommodations.book');

    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::put('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::post('/{booking}/message', [BookingController::class, 'sendMessage'])
            ->middleware('throttle:30,1')
            ->name('message');
        Route::get('/{booking}/payment', [BookingController::class, 'payment'])->name('payment');
        Route::post('/{booking}/payment/confirm', [BookingController::class, 'confirmPayment'])
            ->middleware('throttle:20,1')
            ->name('payment.confirm');
        Route::get('/{booking}/payment/success', [BookingController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/{booking}/payment/cancel', [BookingController::class, 'paymentCancel'])->name('payment.cancel');
        Route::post('/{booking}/payment-proof', [BookingController::class, 'uploadPaymentProof'])
            ->middleware('throttle:10,1')
            ->name('payment-proof.upload');
        Route::delete('/{booking}/payment-proof', [BookingController::class, 'removePaymentProof'])->name('payment-proof.remove');
    });

    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');
});

Route::middleware(['auth', 'tenant.client_guest_rbac'])->group(function () {
    Route::prefix('update-tickets')->name('update-tickets.')->group(function () {
        Route::get('/', [UpdateTicketController::class, 'clientIndex'])->name('index');
        Route::post('/', [UpdateTicketController::class, 'clientStore'])
            ->middleware('throttle:10,1')
            ->name('store');
        Route::get('/{updateTicket}', [UpdateTicketController::class, 'clientShow'])->name('show');
    });

    Route::get('/messages', [MessageController::class, 'index'])
        ->name('messages.index');

    Route::get('/messages/create', [MessageController::class, 'create'])
        ->name('messages.create');

    Route::post('/messages/mark-all-read', [MessageController::class, 'markAllAsRead'])
        ->middleware('throttle:30,1')
        ->name('messages.mark-all-read');

    Route::get('/messages/{message}', [MessageController::class, 'show'])
        ->name('messages.show');

    Route::middleware('throttle:30,1')->group(function () {
        Route::post('/messages', [MessageController::class, 'store'])
            ->name('messages.store');

        Route::post('/messages/{message}/reply', [MessageController::class, 'reply'])
            ->name('messages.reply');
    });

    Route::middleware('throttle:120,1')->group(function () {
        Route::put('/messages/{message}/read', [MessageController::class, 'markAsRead'])
            ->name('messages.mark-read');

        Route::put('/messages/{message}/archive', [MessageController::class, 'archive'])
            ->name('messages.archive');

        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])
            ->name('messages.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
