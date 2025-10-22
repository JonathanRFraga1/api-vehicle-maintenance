<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maintenance extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'vehicle_id',
        'description',
        'cost',
        'service_date',
        'mileage',
        'service_type_id'
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }

    public function maintanceAttachments(): HasMany
    {
        return $this->hasMany(MaintenanceAttachment::class, 'maintenance_id', 'id');
    }
}
