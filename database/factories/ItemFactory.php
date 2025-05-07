<?php

namespace Database\Factories;

use App\Enums\PostTypeEnum;
use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(1),
            'description' => fake()->paragraph(3),
            'post_type' => fake()->randomElement(PostTypeEnum::cases()),
            'village_id' => Village::inRandomOrder()->first()?->id,
            'is_resolved' => fake()->boolean(5),
            'is_approved' => fake()->boolean(5),
        ];
    }
}
