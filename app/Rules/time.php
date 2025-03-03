<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class time implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regex = '/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/';

        if (!preg_match($regex, $value)) {
            $fail('El formato de la hora debe ser HH:MM:SS (ej: 23:59:59).');
        }
    }
}
