<?php

use App\Models\Category;
use App\Models\Conversation;
use App\Models\Item;
use App\Models\Message;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create([
        'name' => 'Jon',
        'email' => fake()->unique()->safeEmail(),
    ]);

    $this->otherUser = User::factory()->create([
        'name' => 'Jane',
        'email' => fake()->unique()->safeEmail(),
    ]);

    $category = Category::factory()->create();
    $this->item = Item::factory()->create([
        'user_id' => $this->otherUser->id,
        'category_id' => $category->id,
    ]);
    $this->conversation = Conversation::factory()->create([
        'sender_id' => $this->user->id,
        'receiver_id' => $this->otherUser->id,
        'item_id' => $this->item->id,
    ]);
    Message::factory()->create([
        'conversation_id' => $this->conversation->id,
        'sender_id' => $this->user->id,
        'message' => 'Hi Jane!',
    ]);
    Message::factory()->create([
        'conversation_id' => $this->conversation->id,
        'sender_id' => $this->otherUser->id,
        'message' => 'Hi Jon!',
    ]);

    $this->actingAs($this->user);
});

it('allows user to get conversation', function () {
    $this->withoutDefer();

    $this->getJson("/api/v1/conversation/{$this->otherUser->id}/{$this->item->id}")
        ->assertOk()
        ->assertJsonStructure([
            'receiver',
            'conv_id',
            'messages' => [
                '*' => [
                    'message',
                    'sender',
                    'messaged_at',
                ],
            ],
        ])
        ->assertJsonfragment(['message' => 'Hi Jane!', 'message' => 'Hi Jon!']);

    $this->assertDatabaseHas('conversations', [
        'sender_id' => $this->user->id,
        'receiver_id' => $this->otherUser->id,
        'item_id' => $this->item->id,
    ]);
});

it('lets user send message', function () {
    $conversation = Conversation::factory()->create([
        'sender_id' => $this->user->id,
        'receiver_id' => $this->otherUser->id,
        'item_id' => $this->item->id,
    ]);

    $this->postJson(route('message.send'), [
        'conversation_id' => $conversation->id,
        'message' => 'Hello Jon, do u have this item?',
    ])->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'message_id',
                'message',
                'messaged_at',
            ],
        ]);
    $this->assertDatabaseHas('messages', [
        'conversation_id' => $conversation->id,
        'message' => 'Hello Jon, do u have this item?',
    ]);
});
