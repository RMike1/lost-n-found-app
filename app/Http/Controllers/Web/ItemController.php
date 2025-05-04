<?php

namespace App\Http\Controllers\web;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public function getLostItems(){
            return Inertia::render('items/Lost-Items');
    }
    public function getFoundItems(){
            return Inertia::render('items/Found-Items');
    }
}
