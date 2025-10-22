<?php

namespace App\Http\Requests;

use App\Rules\PlateFormat;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // A policy já se encarrega
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $vehicle = $this->route('vehicle');
        $currentMileage = $vehicle?->mileage ?? 0;

        return [
            'brand'   => 'required|string|max:255',
            'model'   => 'required|string|max:255',
            'year'    => 'required|integer|digits:4|min:1900|max:' . date('Y'),
            'mileage' => 'required|integer|min:0|gte:' . $currentMileage,
            'plate'   => [
                'required',
                'string',
                'unique:vehicles,plate,' .  $vehicle?->id,
                new PlateFormat()
            ],
        ];
    }
}
