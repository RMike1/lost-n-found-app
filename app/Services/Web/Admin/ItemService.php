<?php

namespace App\Services\Web\Admin;

use App\Models\Category;
use App\Models\Item;

class ItemService
{
    public function item($req): array
    {
        $categories = Category::get(['id', 'name']);
        $items = Item::select('id', 'title', 'description', 'post_type', 'is_approved', 'category_id', 'user_id', 'village_id')
            ->with(['category', 'user', 'village', 'itemImages' => fn ($query) => $query->primaryImage()->select('url', 'item_id')->take(1)])
            ->getFiltered($req)
            ->latest()
            ->paginate(6);

        return [
            $categories,
            $items,
        ];
    }
    public function getItemDetails($item): Item
    {
        return $item->load(['category', 'user', 'village','cell','sector','district', 'itemImages' => fn ($query) => $query->select('url', 'item_id')]);
    }
}
