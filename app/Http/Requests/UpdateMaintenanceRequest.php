<?php

namespace App\Http\Requests;

use App\Models\ServiceType as ModelsServiceType;
use App\Rules\ServiceType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepara os dados para validação.
     */
    protected function prepareForValidation(): void
    {
        $serviceType = ModelsServiceType::where('identifier', $this->service_type)->first();

        if ($serviceType) {
            $this->merge([
                'service_type_id' => $serviceType->id,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description'  => 'required|string|max:255',
            'cost'         => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'service_date' => 'required|date',
            'mileage'      => 'required|integer|min:0',
            'vehicle_id'   => 'required|integer',
            'service_type' => [
                'required',
                'string',
                new ServiceType()
            ],
        ];
    }
}
