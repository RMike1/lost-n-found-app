<?php

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('conversation.{conversation}', function (User $user, Conversation $conversation) {
    return $conversation->sender_id === $user->id ||
           $conversation->receiver_id === $user->id;
});
