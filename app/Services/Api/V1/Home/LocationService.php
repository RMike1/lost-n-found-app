<?php

namespace App\Services\Api\V1\Home;

use App\Exceptions\AppException;
use App\Models\Cell;
use App\Models\District;
use App\Models\Sector;
use App\Models\User;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LocationService
{
    public function getDistricts()
    {
        return District::get()->toResourceCollection();
    }

    public function getSectors($districtId)
    {
        return District::find($districtId)->sectors->toResourceCollection();
    }

    public function getCells($sectorId)
    {
        return Sector::find($sectorId)->cells->toResourceCollection();
    }

    public function getVillages($cellId)
    {
        return Cell::find($cellId)->villages->toResourceCollection();
    }

    public function getAllVillages()
    {
        return Village::get()->toResourceCollection();
    }

    public function getNearByLocations(int $radius = 50000): array
    {
        $userVillage = Auth::user()->village;

        if (! $userVillage) {
            return ['locations' => []];
        }

        $locations = DB::table('items')
            ->join('users', 'items.user_id', '=', 'users.id')
            ->join('villages', 'users.village_id', '=', 'villages.id')
            ->join('cells', 'villages.cell_id', '=', 'cells.id')
            ->join('sectors', 'cells.sector_id', '=', 'sectors.id')
            ->join('districts', 'sectors.district_id', '=', 'districts.id')
            ->where('items.is_approved', true)
            ->select([
                'villages.name as village_name',
                'villages.latitude',
                'villages.longitude',
                'items.post_type',
                DB::raw('COUNT(*) as items_count'),
            ])->selectRaw('
            (6371  * acos(cos(radians(?)) * 
                cos(radians(villages.latitude)) * 
                cos(radians(villages.longitude) - radians(?)) + 
                sin(radians(?)) * 
                sin(radians(villages.latitude))
            )) AS distance
        ', [$userVillage->latitude, $userVillage->longitude, $userVillage->latitude])
            ->groupBy('villages.name', 'villages.latitude', 'villages.longitude', 'items.post_type', 'distance')
            ->havingRaw('distance <= ?', [$radius])
            ->orderBy('distance')
            ->get();

        $groupedLocations = $locations->groupBy('village_name')->map(function ($villageItems) {
            $first = $villageItems->first();

            return [
                'name' => $first->village_name,
                'coordinates' => [
                    'lat' => (float) $first->latitude,
                    'lng' => (float) $first->longitude,
                ],
                'items' => [
                    'lost' => $villageItems->where('post_type', 'lost')->sum('items_count'),
                    'found' => $villageItems->where('post_type', 'found')->sum('items_count'),
                ],
                'distance' => round($first->distance, 2).' km',
            ];
        })->values();

        return [
            'current_location' => [
                'name' => $userVillage->name,
                'coordinates' => [
                    'lat' => (float) $userVillage->latitude,
                    'lng' => (float) $userVillage->longitude,
                ],
            ],
            'nearby_locations' => $groupedLocations,
        ];
    }

    public function getUserLocation(int $user)
    {
        $user = User::whereKey($user)->first();
        throw_unless($user, AppException::recordNotFound('This User does not exists.'));

        return $user->village->toResource();
    }
}
