<?php

namespace App\Http\Resources;

use App\Models\ServiceType;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'vehicle_id'   => $this->vehicle_id,
            'description'  => $this->description,
            'cost'         => $this->cost,
            'service_date' => $this->service_date,
            'mileage'      => $this->mileage,
            'service_type' => ServiceType::find($this->service_type_id)?->identifier,
            'created_at'   => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
