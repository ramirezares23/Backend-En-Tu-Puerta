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
            'data.id' => 'required|integer',
            'data.attributes.id_user' => 'sometimes|integer',
            'data.attributes.date' => 'required|date', //TODO: Validar que sea posterior
            'data.attributes.status' => 'required|string|in:Enviada, Aceptada, Sin respuesta',
            'data.attributes.time' => 'required|date_format:H:i:s',
            'data.attributes.message' => 'sometimes|string',
            'data.attributes.id_service' => 'required|integer',
            'data.relationships.client.data.id' => 'required|integer',
            'data.relationships.service.data.id' => 'required|integer',
        ];
    }
}
