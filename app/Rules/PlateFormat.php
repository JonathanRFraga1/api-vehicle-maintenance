<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class PlateFormat implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $plate = Str::upper(trim($value));

        $patternRenavan = '/^[A-Z]{3}\d{4}$/';       // Formato: AAA1234
        $patternMercosul = '/^[A-Z]{3}\d[A-Z]\d{2}$/'; // Formato: AAA1B23

        if (!preg_match($patternRenavan, $plate) && !preg_match($patternMercosul, $plate)) {
            $fail('The field :attribute is not a plate format valid (ex: AAA1234 ou AAA1B23).');
        }
    }
}
