<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentUser = Auth::id();
        $otherUser = $this->sender_id === $currentUser ? $this->receiver : $this->sender;

        return [
            'conv_id' => $this->id,
            'receiver' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
            ],
            'item_id' => $this->item->id,
            'latest_message' => $this->whenLoaded('latestMessage', $this->latestMessage?->message ?? 'No messages yet'),
            'unread_count' => $this->messages()
                ->whereNull('read_at')
                ->where('sender_id', '!=', $currentUser)
                ->count(),
            'updated_at' => $this->whenLoaded('latestMessage', $this->latestMessage?->created_at?->format('H:i A'), now()->format('H:i A')),
        ];
    }
}
