<?php

namespace App\Rules;

use App\Models\ServiceType as ModelsServiceType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class ServiceType implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $serviceType = Str::lower(trim($value));
        $serviceType = preg_replace('/[^a-z_]/', '', $serviceType);

        $exists = ModelsServiceType::query()
            ->where('identifier', '=', $serviceType)
            ->exists();

        if (!$exists) {
            $fail('The field :attribute is not a valid service type.');
        }
    }
}
