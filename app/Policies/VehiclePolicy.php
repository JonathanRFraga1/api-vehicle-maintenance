<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    /**
     * Validação de acesso para a visualização do veículo
     *
     * @param User $user
     * @param Vehicle $vehicle
     * @return boolean
     */
    public function view(User $user, Vehicle $vehicle): bool
    {
        if ($user->id !== $vehicle->user_id) {
            abort(404);
        }

        return true;
    }

    /**
     * Validação de acesso para a atualização do veículo
     *
     * @param User $user
     * @param Vehicle $vehicle
     * @return boolean
     */
    public function update(User $user, Vehicle $vehicle): bool
    {
        if ($user->id !== $vehicle->user_id) {
            abort(404);
        }

        return true;
    }

    /**
     * Validação de acesso para a remoção do veículo
     *
     * @param User $user
     * @param Vehicle $vehicle
     * @return boolean
     */
    public function delete(User $user, Vehicle $vehicle): bool
    {
        if ($user->id !== $vehicle->user_id) {
            abort(404);
        }

        return true;
    }

    /**
     * Validação de acesso para vincular um veículo a uma manutenção
     *
     * @param User $user
     * @param Vehicle $vehicle
     * @return boolean
     */
    public function addMaintenance(User $user, Vehicle $vehicle): bool
    {
        if ($user->id !== $vehicle->user_id) {
            abort(404);
        }

        return true;
    }
}
