<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('user can signup with valid credentials', function () {
    $userData = User::factory()->make()->only(['name', 'email']);
    $userData['password'] = 'password123';

    $response = $this->post(route('signup'), $userData);

    expect(User::where('email', $userData['email'])->exists())->toBeTrue();
    expect(Auth::check())->toBeTrue();
    $response->assertRedirect(route('dashboard'));
});

test('signup requires valid email', function () {
    $userData = User::factory()->make([
        'email' => 'invalid-email'
    ])->only(['name', 'email']);
    $userData['password'] = 'password123';

    $response = $this->post(route('signup'), $userData);

    $response->assertSessionHasErrors(['email']);
    expect(User::where('email', $userData['email'])->exists())->toBeFalse();
});

test('signup requires unique email', function () {
    // Create an existing user
    $existingUser = User::factory()->create();
    
    $userData = User::factory()->make([
        'email' => $existingUser->email
    ])->only(['name', 'email']);
    $userData['password'] = 'password123';

    $response = $this->post(route('signup'), $userData);

    $response->assertSessionHasErrors(['email']);
    expect(User::where('email', $userData['email'])->count())->toBe(1);
});

test('signup requires all fields', function () {
    $response = $this->post(route('signup'), []);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
});

test('name cannot exceed 255 characters', function () {
    $userData = User::factory()->make([
        'name' => str_repeat('a', 256)
    ])->only(['name', 'email']);
    $userData['password'] = 'password123';

    $response = $this->post(route('signup'), $userData);

    $response->assertSessionHasErrors(['name']);
});