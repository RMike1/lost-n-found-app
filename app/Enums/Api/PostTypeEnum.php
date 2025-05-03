<?php

namespace App\Enums\Api;

enum PostTypeEnum: string
{
    case LOST = 'lost';
    case FOUND = 'found';
}
