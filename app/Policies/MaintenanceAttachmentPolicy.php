<?php

namespace App\Policies;

use App\Models\MaintenanceAttachment;
use App\Models\User;

class MaintenanceAttachmentPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MaintenanceAttachment $maintenanceAttachment): bool
    {
        return $user->id === $maintenanceAttachment->maintenance->vehicle->user_id;
    }

    /*
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MaintenanceAttachment $maintenanceAttachment): bool
    {
        return $user->id === $maintenanceAttachment->maintenance->vehicle->user_id;
    }
}
