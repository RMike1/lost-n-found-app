<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'telephone' => $this->phone_number,
            'location' => [
                'village' => $this->whenLoaded('village'),
                'cell' => $this->whenLoaded('cell', fn () => $this->cell->name),
                'sector' => $this->whenLoaded('sector', fn () => $this->sector->name),
                'district' => $this->whenLoaded('district', fn () => $this->district->name),
            ],
        ];
    }
}
