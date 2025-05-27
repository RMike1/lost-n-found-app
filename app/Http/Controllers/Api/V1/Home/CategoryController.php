<?php

namespace App\Http\Controllers\Api\V1\Home;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\Home\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function __invoke(Request $request)
    {
        return response()->json([
            'categories' => $this->categoryService->index(),
        ], 200);
    }
}
