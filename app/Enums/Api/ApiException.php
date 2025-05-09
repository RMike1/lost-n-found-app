<?php

namespace App\Enums\Api;

enum ApiException
{
    case VALIDATION;
    case NOT_FOUND;
    case AUTH_FAILED;
    case FORBIDDEN;
    case THROTTLE;
    case SERVER_ERROR;

    public function response(): array
    {
        return match ($this) {
            self::VALIDATION => [
                'message' => 'Validation Failed.',
                'status' => 422,
            ],
            self::NOT_FOUND => [
                'message' => 'Not found.',
                'status' => 404,
            ],
            self::AUTH_FAILED => [
                'message' => 'Unauthenticated..',
                'status' => 401,
            ],
            self::FORBIDDEN => [
                'message' => 'Access denied.',
                'status' => 403,
            ],
            self::THROTTLE => [
                'message' => 'Too Many Request..',
                'status' => 429,
            ],
            self::SERVER_ERROR => [
                'message' => 'Something went wrong.',
                'status' => 500,
            ]
        };
    }
}
