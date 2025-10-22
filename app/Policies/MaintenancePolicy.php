<?php

namespace App\Policies;

use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaintenancePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Maintenance $maintenance): bool
    {
        return $user->id === $maintenance->vehicle->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Maintenance $maintenance): bool
    {
        return $user->id === $maintenance->vehicle->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Maintenance $maintenance): bool
    {
        return $user->id === $maintenance->vehicle->user_id;
    }
}
