<?php

namespace App\Exceptions;

use Exception;

class AppException extends Exception
{
    public static function recordNotFound(string $message = 'Record not found', int $code = 404): self
    {
        return new self($message, $code);
    }

    public static function forbidden(string $message = 'Only item owner can be messaged', int $code = 403): self
    {
        return new self($message, $code);
    }

    public static function noItemsFound(string $message = 'No items found for this user.', int $code = 404): self
    {
        return new self($message, $code);
    }

    public static function notOwner(string $message = 'You are not the owner of this item.', int $code = 403): self
    {
        return new self($message, $code);
    }
}
