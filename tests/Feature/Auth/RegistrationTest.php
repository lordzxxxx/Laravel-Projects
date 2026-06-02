<?php

use App\Models\User;

test('generic registration entry presents guest and host choices', function () {
    $this->get('http://localhost:8005/register')
        ->assertOk()
        ->assertSee('How would you like to register?')
        ->assertSee('Guest')
        ->assertSee('Host')
        ->assertSee('/register/guest', false)
        ->assertSee('/register/owner', false);
});

test('guest and host registration screens share the sign-in password design', function () {
    foreach (['guest', 'owner'] as $registrationType) {
        $this->get("http://localhost:8005/register/{$registrationType}")
            ->assertOk()
            ->assertSee('Sign-in password')
            ->assertSee('Choose a password only you know. You will use it with your email to log in.')
            ->assertSee('Create a strong password')
            ->assertSee('Same password again')
            ->assertSee('data-password-toggle="password"', false)
            ->assertSee('data-password-toggle="password_confirmation"', false);
    }
});

test('guest registration creates a traveller account that can log in through the public portal', function () {
    $email = 'guest-registration@example.com';

    $this->post('http://localhost:8005/register/guest', [
        'name' => 'Public Portal Guest',
        'email' => $email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect(route('portal.guest.dashboard'));

    $guest = User::query()->where('email', $email)->firstOrFail();
    expect($guest->role)->toBe(User::ROLE_CLIENT)
        ->and($guest->tenant_id)->toBeNull();
    $this->assertAuthenticatedAs($guest);

    $this->post('http://localhost:8005/logout');
    $this->assertGuest();

    $this->post('http://localhost:8005/login', [
        'email' => $email,
        'password' => 'password',
    ])->assertRedirect('/guest/dashboard');

    $this->assertAuthenticatedAs($guest);
});

test('guest and host registration validate password confirmation consistently', function () {
    foreach (['guest', 'owner'] as $registrationType) {
        $this->from("http://localhost:8005/register/{$registrationType}")
            ->post("http://localhost:8005/register/{$registrationType}", [
                'name' => 'Password Check '.$registrationType,
                'email' => "password-check-{$registrationType}@example.com",
                'password' => 'password',
                'password_confirmation' => 'different-password',
            ])
            ->assertRedirect("http://localhost:8005/register/{$registrationType}")
            ->assertSessionHasErrors('password');
    }
});
