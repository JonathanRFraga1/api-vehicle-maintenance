<?php

namespace App\Http\Requests;

use App\Rules\PlateFormat;
use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand'   => 'required|string|max:255',
            'model'   => 'required|string|max:255',
            'year'    => 'required|integer|digits:4|min:1900|max:' . date('Y'),
            'mileage' => 'required|integer|min:0',
            'plate'   => [
                'required',
                'string',
                'unique:vehicles,plate',
                new PlateFormat()
            ],
        ];
    }
}
