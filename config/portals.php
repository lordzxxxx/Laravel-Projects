<?php

return [

    'admin_port' => (int) env('ADMIN_PORTAL_PORT', 8000),

    'public_port' => (int) env('PUBLIC_PORTAL_PORT', 8005),

    /*
    |--------------------------------------------------------------------------
    | Listing discovery defaults (Guests & public landing)
    |--------------------------------------------------------------------------
    */
    'municipality_name' => env('PUBLIC_PORTAL_MUNICIPALITY_NAME', 'Impasug-ong'),

];
