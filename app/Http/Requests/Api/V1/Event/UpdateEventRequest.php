<?php

namespace App\Http\Requests\Api\V1\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function rules()
    {
        return [
            'data.attributes.date' => 'required|date',
            'data.attributes.time' => 'required|string',
            'data.attributes.status' => 'required|string|in:Pendiente,Confirmado,Cancelado,Completado', // Ajusta según los estados permitidos
        ];
    }
}
