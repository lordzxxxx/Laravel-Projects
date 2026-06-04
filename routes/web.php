<?php

use Illuminate\Support\Facades\Route;

/*
| Route bootstrap
|
| Central hosts (localhost, CENTRAL_DOMAIN, …) → routes/central.php
| Tenant hosts (subdomains)                  → routes/tenant.php (inside tenant middleware)
*/

$centralDomain = env('CENTRAL_DOMAIN', parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost');

$registerCentralRoutes = function () {
    require __DIR__.'/central.php';
};

$centralHosts = array_values(array_unique([$centralDomain, 'localhost', '127.0.0.1', '::1']));

foreach ($centralHosts as $host) {
    Route::domain($host)->group($registerCentralRoutes);
}

Route::middleware([
    'tenant.port',
    'tenant.context',
    'tenant.required',
    'tenant.permissions_team',
    'tenant.active',
    'tenant.session',
    'tenant.bandwidth',
    'tenant.required_update',
])->group(function () {
    require __DIR__.'/tenant.php';
});
