<?php

namespace Database\Seeders;

use App\Models\Pharmacie;
use Illuminate\Database\Seeder;

class GardesSeeder extends Seeder
{
    public function run(): void
    {
        // Données de test : ces pharmacies reçoivent une garde de 7 jours autour d'aujourd'hui.
        $noms = [
            'Pharmacie Test Tanghin',
            'Pharmacie Test Gounghin',
            'Pharmacie Test Dapoya',
            'Pharmacie Test Pissy',
        ];

        foreach (Pharmacie::whereIn('nom', $noms)->get() as $pharmacie) {
            if (! $pharmacie->gardes()->exists()) {
                $pharmacie->gardes()->create([
                    'debut' => today()->subDay()->toDateString(),
                    'fin' => today()->addDays(6)->toDateString(),
                ]);
            }
        }
    }
}
