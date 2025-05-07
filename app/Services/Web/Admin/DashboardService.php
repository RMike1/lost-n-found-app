<?php

namespace App\Services\Web\Admin;

use App\Models\Item;
use App\Enums\PostTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class DashboardService
{

    public function dashboard(Request $req): array
    {
        $start = $req->query('start_date');
        $end = $req->query('end_date');

        $startDate = $start ? Carbon::parse($start) : now()->startOfMonth();
        $endDate = $end ? Carbon::parse($end) : now()->endOfMonth();

        $lastMonthStart = $startDate->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = $endDate->subMonthNoOverflow()->endOfMonth();

        $itemsQuery = Item::query()->whereIn('post_type', [PostTypeEnum::LOST, PostTypeEnum::FOUND])
            ->whereBetween('created_at', [$startDate, $endDate]);

        $items = $itemsQuery->get();

        $lostItems = $items->where('post_type', PostTypeEnum::LOST)->count();
        $foundItems = $items->where('post_type', PostTypeEnum::FOUND)->count();

        $matches = Item::query()
            ->where('is_resolved', true)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();

        $unapproved = Item::query()
            ->where('is_approved', false)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();

        $lastMonthItems = Item::query()
            ->whereIn('post_type', [PostTypeEnum::LOST, PostTypeEnum::FOUND])
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->get();

        $lastMonthLost = $lastMonthItems->where('post_type', PostTypeEnum::LOST)->count();
        $lastMonthFound = $lastMonthItems->where('post_type', PostTypeEnum::FOUND)->count();

        $lastMonthMatches = Item::query()
            ->where('is_resolved', true)
            ->whereBetween('updated_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $lastMonthUnapproved = Item::query()
            ->where('is_approved', false)
            ->whereBetween('updated_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $percentage = fn($current, $previous) => $previous == 0
            ? ($current > 0 ? 100 : 0) : round((($current - $previous) / $previous) * 100, 1);
        
        $range = [
            'startDate'=>$startDate, 
            'endDate'=>$endDate
        ];

        return [
            'lost_items' => [
                'count' => $lostItems,
                'change' => ($lostItems > $lastMonthLost ? '+' : '') . Number::percentage($percentage($lostItems, $lastMonthLost)),
            ],
            'found_items' => [
                'count' => $foundItems,
                'change' => ($foundItems > $lastMonthFound ? '+' : '') . Number::percentage($percentage($foundItems, $lastMonthFound)),
            ],
            'matches' => [
                'count' => $matches,
                'change' => ($matches > $lastMonthMatches ? '+' : '') . Number::percentage($percentage($matches, $lastMonthMatches)),
            ],
            'unapproved' => [
                'count' => $unapproved,
                'change' => ($unapproved > $lastMonthUnapproved ? '+' : '') . Number::percentage($percentage($unapproved, $lastMonthUnapproved)),
            ],
            'date_range'=>$range
        ];
    }
}