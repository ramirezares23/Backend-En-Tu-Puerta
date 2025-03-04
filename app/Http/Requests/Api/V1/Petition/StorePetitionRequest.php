<?php

namespace App\Http\Requests\Api\V1\Petition;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Rules\time;

class StorePetitionRequest extends FormRequest
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
            'data.attributes.id_user' => 'required|integer',
            'data.attributes.description' => 'required|string',
            'data.attributes.type' => 'required|string',
            'data.attributes.area' => 'required|string',
            'data.attributes.date' => 'required|date', //TODO: Validar que sea posterior
            'data.attributes.time' => ['required',new time],
            'data.attributes.id_service' => 'required|integer',

        ];

        //TODO: Revisar todo esto, y lo de date y lo de los estados de la solicitud
    }

    public function messages()
    {
        return [
            'data.attributes.status'
            => 'El atributo data.attributes.status es invalido. Por favor utilice Enviada, Aceptada, Sin respuesta'
        ];
    }
}
