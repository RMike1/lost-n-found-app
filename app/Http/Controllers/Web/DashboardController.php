<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\Admin\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
