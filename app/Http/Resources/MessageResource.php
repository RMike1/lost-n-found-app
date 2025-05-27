<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message_id' => $this->id,
            'message' => $this->message,
            'sender' => $this->whenLoaded('sender', fn ($user) => $user->name),
            'messaged_at' => Carbon::parse($this->created_at)->format('h:i A'),
            'read_at' => $this->when($this->read_at !== null && $this->sender_id === Auth::id(), Carbon::parse($this->read_at)->format('h:i A')),
        ];
    }
}
