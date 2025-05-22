<?php

use App\Models\User;
use App\Models\Village;
use Illuminate\Support\Facades\Hash;

beforeEach(fn () => $this->village = Village::factory()->create()
);

it('registers new user', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'jondoe@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'phone_number' => '0784912345',
        'village_id' => $this->village->id,
    ];
    $this->postJson('api/v1/register', $userData)
        ->assertStatus(201)
        ->assertJsonStructure([
            'user' => ['name', 'email'],
        ]);
    $this->assertDatabaseHas('users', [
        'email' => $userData['email'],
    ]);
    $user = User::where('email', $userData['email'])->first();
    expect(Hash::check('password', $user->password))->toBeTrue();
});
it('returns errors for invalid input', function () {
    $this->postJson('api/v1/register', [
        'name' => 'Jon',
        'email' => '',
        'password' => 'password',
        'password_confirmation' => 'password',
        'phone_number' => '0784912345',
        'village_id' => $this->village->id,
    ])->assertStatus(422)->assertOnlyInvalid(['email']);
});

it('returns validation errors for unmatched passwords', function () {
    $this->postJson('api/v1/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password123',
        'phone_number' => '0784912345',
        'village_id' => $this->village->id,
    ])->assertStatus(422)->assertOnlyInvalid(['password']);
});
