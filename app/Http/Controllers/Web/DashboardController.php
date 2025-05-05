<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\Admin\DashboardService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function __invoke()
    {
        $dashboardService = $this->dashboardService->dashboard();

        return Inertia::render('Dashboard')->with([
            'lost_items' => $dashboardService[0],
            'found_items' => $dashboardService[1],
        ]);
    }
}
