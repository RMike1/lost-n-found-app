<?php

use App\Exceptions\AppException;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Item;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->category = Category::factory()->create();
    $this->village = Village::factory()->create();
});

it('shows all approved items but not resolved to an authenticated user', function () {
    Item::factory(3)->create([
        'category_id' => $this->category->id,
        'user_id' => $this->user->id,
        'village_id' => $this->village->id,
        'is_approved' => false,
        'is_resolved' => true,
    ]);
    Item::factory(2)->create([
        'category_id' => $this->category->id,
        'user_id' => $this->user->id,
        'village_id' => $this->village->id,
        'is_approved' => true,
        'is_resolved' => false,
    ]);
    $items = $this->actingAs($this->user)->getJson(route('items.index'))
        ->assertOk();
    expect($items->json())
        ->items_counts->toBe(2)
        ->items->toHaveCount(2);
});

it('create an item with valid data', function () {
    Storage::fake('images');

    $this->actingAs($this->user)
        ->postJson(route('items.store'), [
            'title' => 'Lost iPhone',
            'description' => 'Lost my iPhone at Nyabisindu',
            'category_id' => $this->category->id,
            'village_id' => $this->village->id,
            'itemImages' => [
                UploadedFile::fake()->image('image1.jpg')->size(300),
                UploadedFile::fake()->image('image2.jpg')->size(300),
            ],
            'post_type' => 'lost',
        ])->assertStatus(201)
        ->assertJsonStructure([
            [
                'id',
                'title',
                'description',
                'location',
                'post_type',
                'posted_at',
                'category',
                'posted_by' => [
                    'id',
                    'name',
                    'is_myItem',
                    'telephone',
                    'location',
                ],
                'itemImages',
            ],
        ]);

    $this->assertDatabaseHas('items', [
        'title' => 'Lost iPhone',
        'description' => 'Lost my iPhone at Nyabisindu',
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'post_type' => 'lost',
    ]);

    $item = Item::first();
    $this->assertCount(2, $item->itemImages);

    Storage::disk('images')->assertExists(
        $item->itemImages->pluck('url')->toArray()
    );
});

it('can not create item without authentication')
    ->postJson('/api/v1/items', [])
    ->assertUnauthorized()
    ->assertStatus(401);

it('can not create item with invalid data', function () {
    $this->actingAs($this->user)
        ->postJson(route('items.store'), [
            'title' => 'x',
            'description' => 'y',
            'category_id' => 123,
            'village_id' => 123,
            'itemImages' => [
                'lorem.txt',
            ],
            'post_type' => 'lorem',
        ])->assertStatus(422)
        ->assertOnlyInvalid([
            'title',
            'description',
            'category_id',
            'village_id',
            'itemImages',
            'post_type',
            'itemImages.0',
        ]);
});

it('user must upload exactly two images', function () {
    $this->actingAs($this->user)
        ->postJson(route('items.store'), [
            'title' => 'Lost iPhone',
            'description' => 'Lost my iPhone lorem ipsum',
            'category_id' => $this->category->id,
            'village_id' => $this->village->id,
            'itemImages' => [
                UploadedFile::fake()->image('image1.jpg')->size(300),
            ],
            'post_type' => 'lost',
        ])->assertStatus(422)
        ->assertOnlyInvalid(['itemImages']);
});

describe('user favorites tests', function () {

    it('allows authenticated user to favorite an item', function () {
        $item = Item::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'village_id' => $this->village->id,
        ]);

        $this->actingAs($this->user)
            ->postJson(route('items.favorite', $item->id))
            ->assertOk()
            ->assertJson([
                'is_favorited' => true,
                'favorites_count' => 1,
            ]);

        expect(Favorite::count())->toBe(1)
            ->and(Favorite::first())
            ->user_id->toBe($this->user->id)
            ->item_id->toBe($item->id);
    });

    it('allows authenticated user to unfavorite an item', function () {
        $item = Item::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'village_id' => $this->village->id,
        ]);

        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($this->user)
            ->postJson(route('items.favorite', $item->id))
            ->assertOk()
            ->assertJson([
                'is_favorited' => false,
                'favorites_count' => 0,
            ]);

        expect(Favorite::count())->toBe(0);
    });

    it('prevents guest users from favoriting items', function () {
        $item = Item::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'village_id' => $this->village->id,
        ]);
        $this->postJson(route('items.favorite', $item->id))
            ->assertUnauthorized();
    });

    it('allows user to get their favorited items', function () {
        $favoritedItems = Item::factory(3)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'village_id' => $this->village->id,
        ]);
        $unfavoritedItems = Item::factory(2)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'village_id' => $this->village->id,
        ]);

        foreach ($favoritedItems as $item) {
            Favorite::create([
                'user_id' => $this->user->id,
                'item_id' => $item->id,
            ]);
        }

        $response = $this->actingAs($this->user)
            ->getJson(route('favorites'))
            ->assertOk();

        expect($response->json())
            ->favorites_items->toHaveCount(3)
            ->items_counts->toBe(3);
    });
});

describe('user items test', function () {
    it('allows user to view their own items', function () {
        $item = Item::factory(3)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'village_id' => $this->village->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('items.user'))
            ->assertOk();

        expect($response->json())
            ->items_counts->toBe(3)
            ->user_items->toHaveCount(3);
    });

    it('returns 404 when user has no items', function () {
        Exceptions::fake();
        $item = Item::factory(0)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'village_id' => $this->village->id,
        ]);
        $this->actingAs($this->user)
            ->getJson(route('items.user'))
            ->assertNotFound()
            ->assertJson([
                'message' => 'No items found for this user.',
            ]);

        Exceptions::assertReported(AppException::class);
        Exceptions::assertReported(function (AppException $e) {
            return $e->getMessage() === 'No items found for this user.' && $e->getCode() === 404;
        });
    });

    it('prevents unauthorized access to user items', function () {
        $this->getJson(route('items.user'))
            ->assertUnauthorized();
    });
});
