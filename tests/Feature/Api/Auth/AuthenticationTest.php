<?php

use App\Models\User;

beforeEach(
    fn () => $this->user = User::factory()->create([
        'name' => 'Jon',
        'email' => 'jondoe@gmail.com',
        'password' => 'password',
    ])
);

test('users can login', function () {
    $this->postJson('api/v1/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'user' => ['name', 'email'],
            'token',
        ]);
});

it('can not authenticate user with invalid credentials')
    ->postJson('api/v1/login', [
        'email' => 'jondoe@gmail.com',
        'password' => 'password123',
    ])->assertStatus(422)->assertJson(['message' => 'Validation Failed.']);

it('can logout user', function () {
    $this->actingAs(User::factory()->create());
    $this->post('api/v1/logout')
        ->assertStatus(200)
        ->assertJson(['message' => 'Logged out successfully']);
    $this->assertDatabaseCount('personal_access_tokens', 0);
});
