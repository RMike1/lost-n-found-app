<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $villages = Village::all();
        $users = User::factory()->count(100)
            ->state(function () use ($villages) {
                return [
                    'village_id' => $villages->random()->id,
                ];
            })->create();
        $categories = [
            'Documents',
            'Cards',
            'Personal Items',
            'Others',
        ];
        foreach ($categories as $categoryName) {
            Category::factory()->state(function () use ($categoryName) {
                return [
                    'name' => $categoryName,
                ];
            })->has(Item::factory()->count(5)->state(function () use ($villages, $users) {
                return [
                    'village_id' => $villages->random()->id,
                    'user_id' => $users->random()->id,
                ];
            })->has(
                ItemImage::factory()->count(2)
            )
            )->create();
        }
    }
}
