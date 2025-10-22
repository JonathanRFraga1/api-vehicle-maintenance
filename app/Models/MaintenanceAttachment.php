<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceAttachment extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'maintenance_id',
        'description',
        'file_name',
        'file_path',
        'mime_type'
    ];

    public function maitenance(): BelongsTo
    {
        return $this->belongsTo(Maintenance::class, 'maintenance_id', 'id');
    }
}
