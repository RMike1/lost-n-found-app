<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    /** @use HasFactory<\Database\Factories\ConversationFactory> */
    use HasFactory, HasUlids;

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    #[Scope]
    public function whereConversationExist(Builder $query, $receiver, $item)
    {
        return $query->where('item_id', $item)
            ->where(function ($query) use ($receiver) {
                $query->where(function ($q) use ($receiver) {
                    $q->where('sender_id', Auth::id())
                        ->where('receiver_id', $receiver);
                })->orWhere(function ($q) use ($receiver) {
                    $q->where('sender_id', $receiver)
                        ->where('receiver_id', Auth::id());
                });
            });
    }
}
