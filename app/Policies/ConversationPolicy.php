<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ConversationPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Conversation $conversation): Response
    {
        return ($user->id === $conversation->sender_id
            || $user->id === $conversation->receiver_id)
            ? Response::allow()
            : Response::deny('You do not have permission to view this conversation');
    }

    public function sendMessage(User $user, Conversation $conversation): Response
    {
        return ($user->id === $conversation->sender_id
            || $user->id === $conversation->receiver_id)
            ? Response::allow()
            : Response::deny('You cannot send messages in this conversation');
    }
}
