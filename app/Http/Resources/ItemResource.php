<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => [
                'village' => $this->whenLoaded('village', fn ($village) => $village->name),
                'cell' => $this->whenLoaded('village', fn ($village) => $village->cell?->name),
                'sector' => $this->whenLoaded('village', fn ($village) => $village->cell?->sector?->name),
                'district' => $this->whenLoaded('village', fn ($village) => $village->cell?->sector?->district?->name),
            ],
            'post_type' => $this->post_type,
            'posted_at' => $this->created_at->diffForHumans(),
            'category' => $this->whenLoaded('category', fn ($category) => $category->name),
            'posted_by' => $this->whenLoaded('user', function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $this->when(Auth::id() === $user->id, $user->name.'(me)', $user->name),
                    'is_myItem' => $this->when(Auth::id() === $user->id, true, false),
                    'telephone' => $user->phone_number,
                    'location' => $this->whenLoaded('village', function () use ($user) {
                        return [
                            'village' => $user?->village?->name,
                            'cell' => $user?->cell?->name,
                            'sector' => $user?->sector?->name,
                            'district' => $user?->district?->name,
                        ];
                    }),
                ];
            }),
            'itemImages' => $this->whenLoaded('itemImages')->map(function ($image) {
                return [
                    'url' => $image->image_url,
                ];
            }),
        ];
    }
}
