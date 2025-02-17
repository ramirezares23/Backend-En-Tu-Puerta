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
                'amount_cents' => $this->amount_cents,
                'description' => $this->description,
                'type' => $this->type,
                'area' => $this->area,
                'datetime' => $this->datetime,
                'status' => $this->status,
                'service' => $this->id_service,
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
                        [
                            'self' => route(
                                'services.show',
                                [
                                    'service' => $this->id_service
                                ]
                            )
                        ]
                    ]
                ]
            ],
            'includes' => [
                new UserResource($this->user),
                new ServiceResource($this->service),
            ],
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
