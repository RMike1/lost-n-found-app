<?php

namespace App\Http\Controllers\Api\V1\Home;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\Home\LocationService;

class LocationController extends Controller
{
    public function __construct(private LocationService $locationService) {}

    // ========================= get Districts==========================

    public function getDistricts()
    {
        return response()->json([
            'districts' => $this->locationService->getDistricts(),
        ], 200);
    }

    // =========================get Sectors==========================

    public function getSectors($districtId)
    {
        return response()->json([
            'sectors' => $this->locationService->getSectors($districtId),
        ], 200);
    }

    // =========================get Cells==========================

    public function getCells($sectorId)
    {
        return response()->json([
            'cells' => $this->locationService->getCells($sectorId),
        ], 200);
    }

    // =========================get Villages==========================

    public function getVillages($cellId)
    {
        return response()->json([
            'villages' => $this->locationService->getVillages($cellId),
        ], 200);
    }

    public function getAllVillages()
    {
        return response()->json([
            'villages' => $this->locationService->getAllVillages(),
        ], 200);
    }

    public function getNearByLocations()
    {
        return response()->json([
            'near-locations' => $this->locationService->getNearByLocations(),
        ]);
    }

    public function getUserLocation($user)
    {
        return response()->json([
            'user-location' => $this->locationService->getUserLocation($user),
        ]);
    }
}
