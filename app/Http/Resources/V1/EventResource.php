<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\ServiceResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'event',
            'id' => $this->id,
            'attributes' => [
                'provider_id' => $this->provider_id,
                'client_id' => $this->client_id,
                'service_id' => $this->service_id,
                'date' => $this->date,
                'time' => $this->time,
                'status' => $this->status,
                'created_at' => $this->created_at,
            ],
            'relationships' => [
                'provider' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->provider_id,
                    ],
                    'links' => [
                        'self' => route(
                            'users.show',
                            [
                                'user' => $this->provider_id
                            ]
                        )
                    ]
                ],
                'client' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->client_id,
                    ],
                    'links' => [
                        'self' => route(
                            'users.show',
                            [
                                'user' => $this->client_id
                            ]
                        )
                    ]
                ],
                'service' => [
                    'data' => [
                        'type' => 'service',
                        'id' => $this->service_id,
                    ],
                    'links' => [
                        'self' => route(
                            'services.show',
                            [
                                'service' => $this->service_id
                            ]
                        )
                    ]
                ]
            ],
            'includes' => [
                new UserResource($this->whenLoaded('provider')),
                new UserResource($this->whenLoaded('client')),
                new ServiceResource($this->whenLoaded('service')),
            ],
            'links' => [
                [
                    'self' => route(
                        'events.show',
                        [
                            'event' => $this->id
                        ]
                    )
                ]
            ]
        ];
    }
}
