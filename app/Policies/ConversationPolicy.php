<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\Item;
use App\Models\User;

class ConversationPolicy
{
    public function canMessage(User $user, Conversation $conversation, Item $itemModel): bool
    {
        return $user->id !== $itemModel->user_id && $conversation->receiver_id === $itemModel->user_id &&
                    ($conversation->sender_id === $user->id || $conversation->receiver_id === $user->id);
    }
}
