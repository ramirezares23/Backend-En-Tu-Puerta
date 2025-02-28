<?php

namespace App\Http\Requests\Api\V1\Petition;

use Illuminate\Foundation\Http\FormRequest;

class BasePetitionRequest extends FormRequest
{
    public function mappedAttributes(){
        $attributeMap = [
            'data.attributes.id_user' => 'id_user',
            'data.attributes.description' => 'description',
            'data.attributes.type' => 'type',
            'data.attributes.date' => 'date',
            'data.attributes.status' => 'status',
            'data.attributes.time' => 'time',
            'data.attributes.message' => 'message',
            'data.attributes.id_service' => 'id_service',
            'data.attributes.createdAt' => 'created_at',
            'data.attributes.updatedAt' => 'updated_at'
        ];

        $attributesToUpdate =[];

        foreach($attributeMap as $key => $attribute){
            if($this->has($key)){
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }
    public function messages()
    {
        return [
            'data.attributes.status'
            => 'El atributo data.attributes.status es invalido. Por favor utilice Enviada, Aceptada, Sin respuesta'
        ];
    }
}
