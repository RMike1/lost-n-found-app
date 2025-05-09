<?php

namespace App\Services\Web\Admin;

use App\Models\Category;

class ItemService
{
    public function item()
    {
        $categories = Category::get(['id', 'name']);

        return $categories;
    }
}
