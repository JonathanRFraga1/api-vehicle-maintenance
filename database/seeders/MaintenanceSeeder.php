<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use App\Models\Vehicle;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $civic = Vehicle::where('plate', 'ABC1D34')->first();
        $corolla = Vehicle::where('plate', 'DEF5G78')->first();
        $gol = Vehicle::where('plate', 'GHI9J12')->first();
        $argo = Vehicle::where('plate', 'KLM3N45')->first();

        $oilChange = ServiceType::where('identifier', 'oil_change')->first();
        $brakes = ServiceType::where('identifier', 'brakes')->first();
        $tireChange = ServiceType::where('identifier', 'tire_change')->first();

        if ($civic) {
            Maintenance::create([
                'vehicle_id' => $civic->id,
                'description' => 'Troca de óleo e filtro de óleo',
                'cost' => 350.50,
                'service_date' => Carbon::now()->subMonths(3)->toDateString(),
                'mileage' => 40000,
                'service_type_id' => $oilChange?->id,
            ]);
            Maintenance::create([
                'vehicle_id' => $civic->id,
                'description' => 'Troca das pastilhas de freio dianteiras',
                'cost' => 580.00,
                'service_date' => Carbon::now()->subMonths(1)->toDateString(),
                'mileage' => 44500,
                'service_type_id' => $brakes?->id,
            ]);
        }

        if ($corolla) {
            Maintenance::create([
                'vehicle_id' => $corolla->id,
                'description' => 'Troca de 2 pneus',
                'cost' => 950.00,
                'service_date' => Carbon::now()->subDays(45)->toDateString(),
                'mileage' => 60000,
                'service_type_id' => $tireChange?->id,
            ]);
        }

        if ($gol) {
            Maintenance::create([
                'vehicle_id' => $gol->id,
                'description' => 'Primeira troca de óleo',
                'cost' => 280.00,
                'service_date' => Carbon::now()->subMonths(6)->toDateString(),
                'mileage' => 10000,
                'service_type_id' => $oilChange?->id,
            ]);
        }
    }
}
