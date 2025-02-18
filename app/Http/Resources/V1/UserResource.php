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
                'email' => $this->email,
                'address' => $this->address,
                $this->mergeWhen(
                    $request->routeIs('users.show'),
                    [
                        'schedules' => $this->schedules,
                        'type' => $this->type,
                        'area' => $this->area,
                        'images_paths' => $this->images_paths,
                        'punctuation' => $this->punctuation,
                    ]
                )
            ],
            'includes' => PetitionResource::collection($this->whenLoaded('petitions'))

        ];
    }
}
