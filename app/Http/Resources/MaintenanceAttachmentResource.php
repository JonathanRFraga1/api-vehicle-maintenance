<?php

namespace App\Http\Resources;

use App\Models\ServiceType;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceAttachmentResource extends JsonResource
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
            'maintenance_id' => $this->maintenance_id,
            'description'    => $this->description,
            'url'            => route('maintenance-attachments.show', ['attachment' => $this->id]),
            'created_at'     => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'     => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
