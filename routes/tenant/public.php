<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\TenantLandingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
| Tenant host — public routes (no auth required)
| Landing, browse accommodations, tenant dashboard entry.
*/

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

Route::get('/accommodations', [AccommodationController::class, 'index'])
    ->name('accommodations.index');

Route::get('/accommodations/{accommodation}', [AccommodationController::class, 'show'])
    ->name('accommodations.show');
