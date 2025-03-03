<?php

namespace App\Http\Requests\Api\V1\Petition;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.id_user' => 'sometimes|integer',
            'data.attributes.description' => 'sometimes|string',
            'data.attributes.type' => 'sometimes|string',
            'data.attributes.date' => 'sometimes|date', //TODO: Validar que sea posterior
            'data.attributes.status' => 'sometimes|string|in:Enviada, Aceptada, Sin respuesta',
            'data.attributes.time' => 'sometimes|date_format:H:i:s',
            'data.attributes.message' => 'sometimes|string',
            'data.attributes.id_service' => 'sometimes|integer',
            'data.relationships.client.data.id' => 'sometimes|integer',
            'data.relationships.service.data.id' => 'sometimes|integer',
        ];
    }
}
