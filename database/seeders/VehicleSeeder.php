<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run()
    {
        $user1 = User::where('email', 'joao@email.com')->first();
        $user2 = User::where('email', 'maria@email.com')->first();

        // Veículos do João
        Vehicle::create([
            'user_id' => $user1->id,
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => 2020,
            'plate' => 'ABC1D34',
            'mileage' => 45000,
        ]);

        Vehicle::create([
            'user_id' => $user1->id,
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2019,
            'plate' => 'DEF5G78',
            'mileage' => 62000,
        ]);

        // Veículos da Maria
        Vehicle::create([
            'user_id' => $user2->id,
            'brand' => 'Volkswagen',
            'model' => 'Gol',
            'year' => 2021,
            'plate' => 'GHI9J12',
            'mileage' => 28000,
        ]);

        Vehicle::create([
            'user_id' => $user2->id,
            'brand' => 'Fiat',
            'model' => 'Argo',
            'year' => 2022,
            'plate' => 'KLM3N45',
            'mileage' => 15000,
        ]);
    }
}
