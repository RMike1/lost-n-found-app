<?php

use App\Exceptions\AppException;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Models\Village;
use Illuminate\Support\Facades\Exceptions;

beforeEach(function () {
    $this->village = Village::factory()->create();
    $this->user = User::factory()->create([
        'village_id' => $this->village->id,
    ]);
});

it('allows a user to view nearby locations', function () {
    $category = Category::factory()->create();

    $nearbyVillage = Village::factory()->create([
        'latitude' => $this->village->latitude + 23.01,
        'longitude' => $this->village->longitude + 9.01,
    ]);

    Item::factory()->create([
        'category_id' => $category->id,
        'user_id' => User::factory()->create(['email' => fake()->unique()->safeEmail(), 'village_id' => $nearbyVillage->id])->id,
        'post_type' => 'lost',
        'is_approved' => true,
    ]);
    $this->actingAs($this->user)->getJson(route('user.near-by-locations'))
        ->assertOk()
        ->assertJsonStructure([
            'near-locations' => [
                'current_location' => [
                    'name',
                    'coordinates' => ['lat', 'lng'],
                ],
                'nearby_locations' => [
                    '*' => [
                        'name',
                        'coordinates' => ['lat', 'lng'],
                        'items' => [
                            'lost',
                            'found',
                        ],
                        'distance',
                    ],
                ],
            ],
        ]);
});

it('reject unauthenticated user to view nearby locations')
    ->getJson('api/v1/user/near-by-locations')
    ->assertUnauthorized();

it('rejects user to view nearby locations if user is not found', function () {
    Exceptions::fake();
    $this->actingAs($this->user)->getJson(route('user.location', ['user' => '111']))
        ->assertNotFound();
    Exceptions::assertReported(AppException::class);
    Exceptions::assertReported(function (AppException $e) {
        return $e->getMessage() === 'This User does not exists.' && $e->getCode() === 404;
    });
});
