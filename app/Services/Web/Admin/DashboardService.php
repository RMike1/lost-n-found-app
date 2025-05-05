<?php

namespace App\Services\Web\Admin;

use App\Enums\PostTypeEnum;
use App\Models\Item;

class DashboardService
{
    public function dashboard(): array
    {
        $items = Item::query()->whereIn('post_type', [PostTypeEnum::LOST, PostTypeEnum::FOUND])->get();
        $lostItems = $items->where('post_type', PostTypeEnum::LOST)->count();
        $foundItems = $items->where('post_type', PostTypeEnum::FOUND)->count();

        return [
            $lostItems,
            $foundItems,
        ];
    }
}
