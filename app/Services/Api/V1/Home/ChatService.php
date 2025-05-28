<?php

namespace App\Services\Api\V1\Home;

use App\Events\Api\MessageSent;
use App\Exceptions\AppException;
use App\Models\Conversation;
use App\Models\Item;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ChatService
{
    public function getConversation(int $receiver, string $item): array
    {
        $user = Auth::id();
        $receiverExists = User::whereKey($receiver)->exists();
        throw_unless($receiverExists, AppException::recordNotFound('Receiver user does not exist'));

        $itemModel = Item::find($item);
        throw_if(! $itemModel, AppException::recordNotFound('Item not found'));
        $itemOwner = $itemModel->user_id;

        return DB::transaction(function () use ($receiver, $item, $user, $itemModel) {
            $conversation = Conversation::query()
                ->whereConversationExist($receiver, $item)
                ->first() ?? Conversation::create([
                    'item_id' => $item,
                    'sender_id' => $user,
                    'receiver_id' => $receiver,
                ]);

            throw_if(! Gate::inspect('canMessage', [$conversation, $itemModel])->allowed(),
                AppException::forbidden('Unauthorized for this chat'));
            if ($conversation->exists) {
                // defer(function () use ($conversation, $user) {
                    Message::where('conversation_id', $conversation->id)
                        ->whereNull('read_at')
                        ->where('sender_id', '!=', $user)
                        ->update(['read_at' => now()]);
                // });
            }
            $messages = $conversation->messages()
                ->with('sender')
                ->orderBy('created_at')
                ->get()
                ->toResourceCollection();
            $receiver = $conversation->receiver_id === $user ? $conversation->sender->name : $conversation->receiver->name;

            return [
                $receiver,
                $conversation->id,
                $messages,
            ];
        });
    }

    public function sendMessage(array $data)
    {
        return DB::transaction(function () use ($data) {
            $conversation = Conversation::find($data['conversation_id']);
            throw_if(! $conversation, AppException::recordNotFound('this chat does not exist'));
            $itemModel = Item::find($conversation->item_id);
            throw_if(! $itemModel, AppException::recordNotFound());

            throw_if(! Gate::inspect('canMessage', [$conversation, $itemModel])->allowed(),
                AppException::forbidden('You can\'t send messages in this conversation'));
            $message = Message::create([
                'message' => $data['message'],
                'sender_id' => Auth::id(),
                'conversation_id' => $data['conversation_id'],
            ]);
            broadcast(new MessageSent($message))->toOthers();

            return $message->toResource();
        });
    }

    public function getConversationsList()
    {
        $user = Auth::id();

        return Conversation::query()
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user)
                    ->orWhere('receiver_id', $user);
            })
            ->with(['latestMessage', 'sender', 'receiver', 'item'])
            ->latest('updated_at')
            ->get()
            ->toResourceCollection();
    }

    public function getUnreadMessagesCount(): array
    {
        $userId = Auth::id();

        $totalUnread = Message::query()
            ->whereHas('conversation', function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    $q->where('sender_id', $userId)
                        ->orWhere('receiver_id', $userId);
                });
            })
            ->whereNull('read_at')
            ->where('sender_id', '!=', $userId)
            ->count();

        return [
            'unread_messages' => $totalUnread,
        ];
    }
}
