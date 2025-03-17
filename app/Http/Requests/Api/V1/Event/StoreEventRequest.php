<?php

namespace App\Http\Requests\Api\V1\Event;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\time;

class StoreEventRequest extends FormRequest
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
            'data.attributes.provider_id' => 'required|exists:users,id',
            'data.attributes.title' => 'string|max:255',
            'data.attributes.date' => 'required|date|after:today',
            'data.attributes.time' => ['required', new time],
        ];
    }
}
