<?php

namespace App\Services\Web\Admin;

use App\Models\Item;
use App\Models\Category;

class ItemService
{
    public function item(): array
    {
        $categories = Category::get(['id', 'name']);
        $items = Item::select('id','title','description','post_type','is_approved','category_id','user_id','village_id')
        ->with(['category', 'user','village', 'itemImages' => fn ($query) => $query->primaryImage()->select('url','item_id')->take(1)])
        ->getFiltered(request())
        ->latest()
        ->paginate(6);
        return [
            $categories,
            $items,
        ];
    }
}
