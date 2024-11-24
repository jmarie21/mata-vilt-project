<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    // Create a test user before each test
    $this->user = User::factory()->create([
        'password' => Hash::make('password123'),
        'first_login' => true
    ]);
});

test('user can login with correct credentials', function () {
    $this->user->first_login = false;
    $this->user->save();

    $response = $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => 'password123',
    ]);

    expect(Auth::check())->toBeTrue()
        ->and(Auth::id())->toBe($this->user->id);

    $response->assertRedirect(route('login'));
});

test('user is redirected to password change on first login', function () {
    $response = $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => 'password123',
    ]);

    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($this->user->id);

    $response->assertRedirect(route('password.change'));
});

test('user cannot login with incorrect password', function () {
    $response = $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => 'wrongpassword',
    ]);

    expect(Auth::check())->toBeFalse();
    
    $response->assertRedirect()
        ->assertSessionHasErrors('email');
    
    expect(session()->get('errors')->first())
        ->toBe('The provided credentials do not match our records');
});

test('user cannot login with non-existent email', function () {
    $response = $this->post(route('login'), [
        'email' => 'nonexistent@example.com',
        'password' => 'password123',
    ]);

    expect(Auth::check())->toBeFalse();
    
    $response->assertRedirect()
        ->assertSessionHasErrors('email');
});

test('login requires valid email', function () {
    $response = $this->post(route('login'), [
        'email' => 'invalid-email',
        'password' => 'password123'
    ]);

    expect(Auth::check())->toBeFalse();
    $response->assertSessionHasErrors(['email']);
});

test('login requires all fields', function () {
    $response = $this->post(route('login'), []);

    expect(Auth::check())->toBeFalse();
    $response->assertSessionHasErrors(['email', 'password']);
});

test('session is regenerated on successful login', function () {
    $this->withoutExceptionHandling();
    
    $response = $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => 'password123',
    ]);

    $response->assertSessionHasNoErrors();
    expect(session()->token())->not->toBeEmpty();
});
