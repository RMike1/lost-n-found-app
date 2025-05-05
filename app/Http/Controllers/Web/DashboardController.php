<?php

namespace App\Http\Controllers\Web;

use App\Models\Item;
use Inertia\Inertia;
use App\Enums\PostTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        
        $items=Item::query()->whereIn('post_type',[PostTypeEnum::LOST, PostTypeEnum::FOUND])->get();
        $lostItems=$items->where('post_type',PostTypeEnum::LOST)->count();
        $foundItems=$items->where('post_type',PostTypeEnum::FOUND)->count();
        return Inertia::render('Dashboard')->with([
            'lost_items'=>$lostItems,
            'found_items'=>$foundItems
        ]);
    }
}
