<?php

namespace Database\Seeders;

use App\Models\Pharmacie;
use Illuminate\Database\Seeder;

class GardesSeeder extends Seeder
{
    public function run(): void
    {
        // Données de test : les pharmacies marquées de garde reçoivent une garde de 7 jours.
        foreach (Pharmacie::where('de_garde', true)->get() as $pharmacie) {
            if (! $pharmacie->gardes()->exists()) {
                $pharmacie->gardes()->create([
                    'debut' => today()->subDay()->toDateString(),
                    'fin' => today()->addDays(6)->toDateString(),
                ]);
            }
        }
    }
}
