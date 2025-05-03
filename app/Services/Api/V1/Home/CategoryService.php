<?php

namespace App\Services\Api\V1\Home;

use App\Models\Category;

class CategoryService
{
    public function index()
    {
        return Category::query()->get()->toResourceCollection();
    }
}
