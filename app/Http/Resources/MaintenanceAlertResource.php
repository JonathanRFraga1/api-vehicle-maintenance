<?php

namespace App\Http\Resources;

use App\Models\ServiceType;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceAlertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'vehicle_id'     => $this->vehicle_id,
            'description'    => $this->description,
            'type'           => $this->type,
            'value_to_alert' => $this->value_to_alert,
            'status'         => $this->status,
            'created_at'     => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'     => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
