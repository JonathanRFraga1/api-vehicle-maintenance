<?php

namespace App\Policies;

use App\Models\MaintenanceAlert;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaintenanceAlertPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MaintenanceAlert $maintenanceAlert): bool
    {
        return $user->id === $maintenanceAlert->vehicle->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaintenanceAlert $maintenanceAlert): bool
    {
        return $user->id === $maintenanceAlert->vehicle->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MaintenanceAlert $maintenanceAlert): bool
    {
        return $user->id === $maintenanceAlert->vehicle->user_id;
    }
}
