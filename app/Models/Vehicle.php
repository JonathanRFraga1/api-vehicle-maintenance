<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'year',
        'plate',
        'mileage'
    ];

    public function alerts(): HasMany
    {
        return $this->hasMany(MaintenanceAlert::class, 'vehicle_id', 'id');
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'vehicle_id', 'id');
    }
}
