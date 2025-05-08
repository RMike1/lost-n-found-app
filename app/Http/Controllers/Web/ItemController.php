<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ItemController extends Controller
{
    public function getItems()
    {
        return Inertia::render('items/Items');
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
