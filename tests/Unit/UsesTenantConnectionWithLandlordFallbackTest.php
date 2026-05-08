<?php

use App\Models\Concerns\UsesTenantConnectionWithLandlordFallback;

uses(Tests\TestCase::class);

test('unified single-db mode uses landlord connection outside testing', function () {
    $app = app();
    $previous = $app['env'] ?? 'testing';

    $app['env'] = 'local';

    try {
        $resolver = new class
        {
            use UsesTenantConnectionWithLandlordFallback;
        };

        expect($resolver->getConnectionName())->toBe(config('multitenancy.landlord_database_connection_name', 'landlord'));
    } finally {
        $app['env'] = $previous;
    }
});

test('uses default database connection in testing environment', function () {
    expect(app()->environment('testing'))->toBeTrue();

    $resolver = new class
    {
        use UsesTenantConnectionWithLandlordFallback;
    };

    expect($resolver->getConnectionName())->toBe(config('database.default'));
});
