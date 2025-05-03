<?php

namespace App\Enums\Api;

enum ApiException
{
    case VALIDATION;
    case NOT_FOUND;
    case AUTH_FAILED;
    case FORBIDDEN;
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
                'status' => 401,
            ],
            self::FORBIDDEN => [
                'message' => 'Access denied.',
                'status' => 403,
            ],
            self::SERVER_ERROR => [
                'message' => 'Something went wrong.',
                'status' => 500,
            ]
        };
    }
}
