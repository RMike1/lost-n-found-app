<?php

namespace App\Http\Controllers\web;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use App\Services\Web\Admin\ItemService;

class ItemController extends Controller
{
    public function __construct(private ItemService $itemService) {}

    public function getItems(Request $req)
    {
        $data = $this->itemService->item($req);

        return inertia()->render('items/Items', [
            'categories' => $data[0],
            'items' => inertia()->merge(function() use($data){
                return $data[1]->items();
            }),
            'itemsPaginated' => Arr::except($data[1]->toArray(), 'data'),
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
