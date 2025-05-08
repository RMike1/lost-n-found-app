<?php

namespace App\Enums;

enum UserRole: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';
    case SUPER_ADMIN = 'super_admin';

    public function isAdmin(): bool
    {
        return $this === self::ADMIN || $this === self::SUPER_ADMIN;
    }

    public function isModerator(): bool
    {
        return $this === self::MODERATOR || $this === self::SUPER_ADMIN;
    }
    public function isSuperAdmin(): bool
    {
        return $this === self::SUPER_ADMIN;
    }
}
