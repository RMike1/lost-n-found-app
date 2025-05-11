<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Services\Web\Admin\ItemService;
use Inertia\Inertia;

class ItemController extends Controller
{
    public function __construct(private ItemService $itemService) {}

    public function getItems()
    {
        $data = $this->itemService->item();

        return Inertia::render('items/Items', [
            'categories' => $data[0],
            'items' => $data[1],
        ]);
    }

    public function getLostItems()
    {
        return Inertia::render('items/Lost-Items');
    }

    public function getFoundItems()
    {
        return Inertia::render('items/Found-Items');
    }
}
