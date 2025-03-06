<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'service',
            'id' => $this->id,
            'attributes' => [
                'id_provider' => $this->id_provider,
                'service_name' => $this->service_name,
                'service_price' => $this->service_price,
                'images_path' => $this->images_path,
                // $this->mergeWhen($request->routeIs('services.show'), //TODO: Evaluar si se necesita colocar
                //     [
                'description' => $this->description,
                'duration' => $this->duration,
                // ]
                // )
            ],
            'relationships' => [
                'provider' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->id_provider,
                    ],
                    'links' => [
                        [
                            'self' => route(
                                'users.show',
                                [
                                    'user' => $this->id_provider
                                ],
                            )
                        ]
                    ]
                ]
            ],
            'links' => [
                [
                    'self' => route(
                        'services.show',
                        [
                            'service' => $this->id
                        ]
                    )
                ]
            ]
        ];
    }
}
