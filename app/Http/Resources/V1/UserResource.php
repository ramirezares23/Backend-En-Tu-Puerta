<?php

namespace App\Http\Resources\V1;

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
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'username' => $this->username,
                'email' => $this->email,
                'address' => $this->address,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'type' => $this->type,
                'profile_image_path' => $this->profile_image_path,
                'punctuation' => $this->punctuation,
            ],
            'includes' => PetitionResource::collection($this->whenLoaded('petitions'))

        ];
    }
}
