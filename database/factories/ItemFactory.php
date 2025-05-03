<?php

namespace Database\Factories;

use App\Enums\Api\PostTypeEnum;
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
        $isResolved = fake()->boolean(5);

        return [
            'title' => fake()->sentence(1),
            'description' => fake()->paragraph(3),
            'post_type' => fake()->randomElement(PostTypeEnum::cases()),
            'village_id' => Village::inRandomOrder()->first()?->id,
            'resolved' => $isResolved,
            'active' => ! $isResolved,
        ];
    }
}
