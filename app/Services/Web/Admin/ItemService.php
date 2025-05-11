<?php

namespace App\Services\Web\Admin;

use App\Models\Item;
use App\Models\Category;

class ItemService
{
    public function item()
    {
        $categories = Category::get(['id', 'name']);
        $items = Item::query()
        ->with(['category', 'user', 'village', 'cell', 'sector', 'district', 'itemImages' => fn ($query) => $query->primaryImage()->take(1)])
        ->getFiltered(request())
        ->latest()
        ->get();
        return [
            $categories,
            $items
        ];
    }
}
