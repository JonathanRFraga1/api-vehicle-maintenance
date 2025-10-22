<?php

namespace Database\Seeders;

use App\Models\MaintenanceAlert;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class MaintenanceAlertSeeder extends Seeder
{
    public function run()
    {
        $vehicles = Vehicle::all();

        foreach ($vehicles as $vehicle) {
            // Alerta por quilometragem - Troca de óleo
            MaintenanceAlert::create([
                'vehicle_id' => $vehicle->id,
                'description' => 'Troca de óleo - ' . $vehicle->brand . ' ' . $vehicle->model,
                'type' => 'mileage',
                'value_to_alert' => $vehicle->mileage + 5000, // Alerta em 5000km
                'status' => 'waiting_send',
            ]);

            // Alerta por quilometragem - Revisão
            MaintenanceAlert::create([
                'vehicle_id' => $vehicle->id,
                'description' => 'Revisão dos 10.000km - ' . $vehicle->brand . ' ' . $vehicle->model,
                'type' => 'mileage',
                'value_to_alert' => (floor($vehicle->mileage / 10000) + 1) * 10000,
                'status' => 'waiting_send',
            ]);

            // Alerta por tempo - Revisão anual
            MaintenanceAlert::create([
                'vehicle_id' => $vehicle->id,
                'description' => 'Revisão anual - ' . $vehicle->brand . ' ' . $vehicle->model,
                'type' => 'time',
                'value_to_alert' => 365, // 365 dias
                'status' => 'waiting_send',
            ]);

            // Alguns alertas já enviados/completos (variação)
            if (rand(0, 1)) {
                MaintenanceAlert::create([
                    'vehicle_id' => $vehicle->id,
                    'description' => 'Troca de pneus - ' . $vehicle->brand . ' ' . $vehicle->model,
                    'type' => 'mileage',
                    'value_to_alert' => $vehicle->mileage - 1000,
                    'status' => ['sent', 'completed'][rand(0, 1)],
                ]);
            }
        }
    }
}
