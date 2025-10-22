<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['description' => 'Troca de Óleo', 'identifier' => 'oil_change'],
            ['description' => 'Revisão Preventiva', 'identifier' => 'preventive_maintenance'],
            ['description' => 'Alinhamento e Balanceamento', 'identifier' => 'alignment_balancing'],
            ['description' => 'Troca de Pneus', 'identifier' => 'tire_change'],
            ['description' => 'Freios', 'identifier' => 'brakes'],
            ['description' => 'Suspensão', 'identifier' => 'suspension'],
            ['description' => 'Sistema Elétrico', 'identifier' => 'electrical_system'],
            ['description' => 'Ar Condicionado', 'identifier' => 'air_conditioning'],
            ['description' => 'Filtros', 'identifier' => 'filters'],
            ['description' => 'Bateria', 'identifier' => 'battery'],
        ];

        foreach ($types as $type) {
            ServiceType::create($type);
        }
    }
}
