<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'Gasabo' => [
                'Kimironko' => [
                    'Bibare Cell' => [
                        ['name' => 'Bibare Village A', 'latitude' => -1.9320, 'longitude' => 30.1030],
                        ['name' => 'Bibare Village B', 'latitude' => -1.9322, 'longitude' => 30.1032],
                        ['name' => 'Bibare Village C', 'latitude' => -1.9324, 'longitude' => 30.1034],
                        ['name' => 'Bibare Village D', 'latitude' => -1.9326, 'longitude' => 30.1036],
                        ['name' => 'Bibare Village E', 'latitude' => -1.9328, 'longitude' => 30.1038],
                    ],
                    'Nyagatovu Cell' => [
                        ['name' => 'Nyagatovu Village A', 'latitude' => -1.9400, 'longitude' => 30.1100],
                        ['name' => 'Nyagatovu Village B', 'latitude' => -1.9402, 'longitude' => 30.1102],
                        ['name' => 'Nyagatovu Village C', 'latitude' => -1.9404, 'longitude' => 30.1104],
                        ['name' => 'Nyagatovu Village D', 'latitude' => -1.9406, 'longitude' => 30.1106],
                        ['name' => 'Nyagatovu Village E', 'latitude' => -1.9408, 'longitude' => 30.1108],
                    ],
                ],
                'Remera' => [
                    'Nyabisindu Cell' => [
                        ['name' => 'Nyabisindu Village A', 'latitude' => -1.9500, 'longitude' => 30.1200],
                        ['name' => 'Nyabisindu Village B', 'latitude' => -1.9502, 'longitude' => 30.1202],
                        ['name' => 'Nyabisindu Village C', 'latitude' => -1.9504, 'longitude' => 30.1204],
                        ['name' => 'Nyabisindu Village D', 'latitude' => -1.9506, 'longitude' => 30.1206],
                        ['name' => 'Nyabisindu Village E', 'latitude' => -1.9508, 'longitude' => 30.1208],
                    ],
                    'Giporoso Cell' => [
                        ['name' => 'Giporoso Village A', 'latitude' => -1.9500, 'longitude' => 30.1200],
                        ['name' => 'Giporoso Village B', 'latitude' => -1.9502, 'longitude' => 30.1202],
                        ['name' => 'Giporoso Village C', 'latitude' => -1.9504, 'longitude' => 30.1204],
                        ['name' => 'Giporoso Village D', 'latitude' => -1.9506, 'longitude' => 30.1206],
                        ['name' => 'Giporoso Village E', 'latitude' => -1.9508, 'longitude' => 30.1208],
                    ],
                ],
            ],
            'Kicukiro' => [
                'Kagarama' => [
                    'Niboye Cell' => [
                        ['name' => 'Niboye Village A', 'latitude' => -1.9650, 'longitude' => 30.1300],
                        ['name' => 'Niboye Village B', 'latitude' => -1.9652, 'longitude' => 30.1302],
                        ['name' => 'Niboye Village C', 'latitude' => -1.9654, 'longitude' => 30.1304],
                        ['name' => 'Niboye Village D', 'latitude' => -1.9656, 'longitude' => 30.1306],
                        ['name' => 'Niboye Village E', 'latitude' => -1.9658, 'longitude' => 30.1308],
                    ],
                ],
            ],
        ];

        foreach ($locations as $districtName => $sectors) {
            $district = District::create(['name' => $districtName]);

            foreach ($sectors as $sectorName => $cells) {
                $sector = $district->sectors()->create(['name' => $sectorName]);

                foreach ($cells as $cellName => $villages) {
                    $cell = $sector->cells()->create(['name' => $cellName]);

                    foreach ($villages as $villageData) {
                        $cell->villages()->create([
                            'name' => $villageData['name'],
                            'latitude' => $villageData['latitude'],
                            'longitude' => $villageData['longitude'],
                        ]);
                    }
                }
            }
        }
    }
}
