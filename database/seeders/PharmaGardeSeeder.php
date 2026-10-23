<?php

namespace Database\Seeders;

use App\Models\Medicament;
use App\Models\Pharmacie;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class PharmaGardeSeeder extends Seeder
{
    public function run(): void
    {
        $medicaments = [
            ['Paracétamol 500 mg', 'Antalgique et antipyrétique'],
            ['Amoxicilline 500 mg', 'Antibiotique'],
            ['Ibuprofène 400 mg', 'Anti-inflammatoire'],
            ['Artéméther-luméfantrine', 'Traitement du paludisme'],
            ['Sels de réhydratation orale', 'Traitement de la déshydratation'],
            ['Métronidazole 500 mg', 'Antibiotique et antiparasitaire'],
            ['Oméprazole 20 mg', 'Protecteur gastrique'],
            ['Vitamine C 500 mg', 'Complément vitaminique'],
        ];

        foreach ($medicaments as [$nom, $description]) {
            Medicament::updateOrCreate(['nom' => $nom], ['description' => $description]);
        }

        // Données de test : noms, numéros et coordonnées fictifs ou approximatifs.
        $pharmacies = [
            ['Pharmacie Test Tanghin', 'Rue de test, Tanghin', 'Tanghin', '70 00 00 01', 12.4000, -1.5070],
            ['Pharmacie Test Zogona', 'Rue de test, Zogona', 'Zogona', '70 00 00 02', 12.3920, -1.4960],
            ['Pharmacie Test Gounghin', 'Rue de test, Gounghin', 'Gounghin', '70 00 00 03', 12.3720, -1.5340],
            ['Pharmacie Test Ouaga 2000', 'Rue de test, Ouaga 2000', 'Ouaga 2000', '70 00 00 04', 12.3070, -1.4830],
            ['Pharmacie Test Dapoya', 'Rue de test, Dapoya', 'Dapoya', '70 00 00 05', 12.3650, -1.5160],
            ['Pharmacie Test Patte d\'Oie', 'Rue de test, Patte d\'Oie', 'Patte d\'Oie', '70 00 00 06', 12.3500, -1.4900],
            ['Pharmacie Test Pissy', 'Rue de test, Pissy', 'Pissy', '70 00 00 07', 12.3400, -1.5700],
            ['Pharmacie Test Cissin', 'Rue de test, Cissin', 'Cissin', '70 00 00 08', 12.3800, -1.5500],
        ];

        foreach ($pharmacies as [$nom, $adresse, $quartier, $telephone, $latitude, $longitude]) {
            Pharmacie::updateOrCreate(['nom' => $nom], [
                'adresse' => $adresse,
                'quartier' => $quartier,
                'telephone' => $telephone,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'validee' => true,
            ]);
        }

        $tousLesMedicaments = Medicament::all();

        foreach (Pharmacie::all() as $i => $pharmacie) {
            foreach ($tousLesMedicaments as $j => $medicament) {
                $disponible = (($i + $j) % 3) !== 0;

                Stock::updateOrCreate(
                    ['pharmacie_id' => $pharmacie->id, 'medicament_id' => $medicament->id],
                    [
                        'disponible' => $disponible,
                        'quantite' => $disponible ? 5 + (($i * 7 + $j * 3) % 40) : 0,
                    ]
                );
            }
        }
    }
}
