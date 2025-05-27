<?php

namespace App\Http\Controllers\Api\V1\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreMessageRequest;
use App\Services\Api\V1\Home\ChatService;

class ChatController extends Controller
{
    public function __construct(private ChatService $chatService) {}

    public function getConversation(int $receiver, string $item)
    {
        $conversation = $this->chatService->getConversation($receiver, $item);

        return response()->json([
            'receiver' => $conversation[0],
            'conv_id' => $conversation[1],
            'messages' => $conversation[2],
        ], 200);
    }

    public function sendMessage(StoreMessageRequest $req)
    {
        return response()->json([
            'data' => $this->chatService->sendMessage($req->validated()),
        ], 201);
    }

    public function conversationsList()
    {
        return response()->json([
            'conversations' => $this->chatService->getConversationsList(),
        ]);
    }

    public function unreadCount(ChatService $service)
    {
        return response()->json([
            'conversations' => $this->chatService->getUnreadMessagesCount(),
        ]);
    }
}
