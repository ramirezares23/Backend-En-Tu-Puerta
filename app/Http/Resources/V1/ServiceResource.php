<?php

namespace App\Http\Resources\V1;

use App\Models\User;
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
        $provider = User::find($this->id_provider);
        return [
            'type' => 'service',
            'id' => $this->id,
            'attributes' => [
                'id_provider' => $provider->id,
                'firstName_provider' => $provider->first_name,
                'lastName_provider' => $provider->last_name,
                'punctuation_provider' => $provider->punctuation,
                'address_provider' => $provider->address,

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
