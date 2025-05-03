<?php

use App\Models\User;
use App\Notifications\Api\ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;

it('can request reset password link', function () {
    Notification::fake();
    $user = User::factory()->create();
    $this->postJson('api/v1/forgot-password', ['email' => $user->email], [
        'email' => $user->email,
    ])
        ->assertSessionHasNoErrors()
        ->assertStatus(200)
        ->assertJson(['message' => 'We have emailed your password reset link.']);
    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

it('can reset password with valid token', function () {
    Notification::fake();
    $user = User::factory()->create();
    $this->postJson('api/v1/forgot-password', ['email' => $user->email]);
    Notification::assertSentTo($user, ResetPasswordNotification::class, function (object $notification) use ($user) {
        $this->postJson('api/v1/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasNoErrors()
            ->assertStatus(200);

        return true;
    });
});
