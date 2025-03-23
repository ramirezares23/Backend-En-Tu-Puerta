<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\ServiceResource;

class PetitionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'petition',
            'id' => $this->id,
            'attributes' => [
                'id_user' => $this->id_user,
                'firstname_user' => $this->user->first_name,
                'lastname_user' => $this->user->last_name,
                'image_user' => $this->user->profile_image_path,
                'date' => $this->date,
                'time' => $this->time,
                'status' => $this->status,
                'message' => $this->message,
                'id_service' => $this->id_service,
                'name_service' => $this->service->service_name,
                'id_provider' => $this->provider->id,
            ],
            'relationships' => [
                'client' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->id_user,
                    ],
                    'links' => [
                        [
                            'self' => route(
                                'users.show',
                                [
                                    'user' => $this->id_user
                                ]
                            )
                        ]
                    ]
                ],
                'service' => [
                    'data' => [
                        'type' => 'service',
                        'id' => $this->id_service,
                    ],
                    'links' => [
                        'self' => route(
                            'services.show',
                            [
                                'service' => $this->id_service
                            ]
                        )
                    ]
                ]
            ],
            'includes' =>
                new UserResource($this->whenLoaded('user')),
            new ServiceResource($this->whenLoaded('service')),

            'links' => [
                [
                    'self' => route(
                        'petitions.show',
                        [
                            'petition' => $this->id
                        ]
                    )
                ]
            ]
        ];
    }
}
