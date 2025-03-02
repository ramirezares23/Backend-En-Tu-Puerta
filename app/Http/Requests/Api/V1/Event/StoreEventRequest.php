<?php

namespace App\Http\Requests\Api\V1\Event;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function rules()
    {
        return [
            'data.relationships.provider.data.id' => 'required|exists:users,id',
            'data.relationships.client.data.id' => 'required|exists:users,id',
            'data.relationships.service.data.id' => 'required|exists:services,id',
            'data.attributes.date' => 'required|date',
            'data.attributes.time' => 'required|string',
            'data.attributes.status' => 'required|string',
        ];
    }
}
