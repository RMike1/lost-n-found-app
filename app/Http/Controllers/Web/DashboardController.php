<?php

namespace App\Http\Controllers\Web;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Controllers\Controller;
use App\Services\Web\Admin\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function __invoke(Request $req)
    {
        
        return Inertia::render('Dashboard',
          $this->dashboardService->dashboard($req)
        );
    }
}
