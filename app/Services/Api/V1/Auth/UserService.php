<?php

namespace App\Services\Api\V1\Auth;

use App\Exceptions\AppException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function userDetails(): JsonResource
    {
        if (Auth::user() === null) {
            throw AppException::forbidden('Unauthorized', 403);
        }

        return Auth::user()->toResource();
    }
}
