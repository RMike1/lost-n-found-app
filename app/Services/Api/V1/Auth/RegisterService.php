<?php

namespace App\Services\Api\V1\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class RegisterService
{
    public function register($request)
    {
        return DB::transaction(function () use ($request) {
            $user = User::create($request);
            event(new Registered($user));

            return $user->load('village', 'cell', 'sector', 'district')->toResource();
        });
    }
}
