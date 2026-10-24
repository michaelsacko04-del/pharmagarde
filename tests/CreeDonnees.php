<?php

namespace Tests;

use App\Models\Medicament;
use App\Models\Pharmacie;
use App\Models\Stock;
use App\Models\User;

trait CreeDonnees
{
    protected function utilisateur(string $role): User
    {
        $user = User::factory()->create();
        // forceFill : le rôle n'est jamais remplissable en masse.
        $user->forceFill(['role' => $role])->save();

        return $user;
    }

    protected function pharmacie(array $attributs = [], ?User $proprietaire = null, bool $deGarde = true): Pharmacie
    {
        $pharmacie = Pharmacie::create(array_merge([
            'nom' => 'Pharmacie Exemple '.uniqid(),
            'adresse' => 'Rue de test',
            'quartier' => 'Tanghin',
            'telephone' => '70 00 00 00',
            'latitude' => 12.37,
            'longitude' => -1.52,
            'validee' => true,
        ], $attributs));

        if ($proprietaire) {
            $pharmacie->user()->associate($proprietaire)->save();
        }

        if ($deGarde) {
            $pharmacie->gardes()->create([
                'debut' => today()->subDay()->toDateString(),
                'fin' => today()->addDays(2)->toDateString(),
            ]);
        }

        return $pharmacie;
    }

    protected function stock(Pharmacie $pharmacie, string $nomMedicament = 'Paracétamol 500 mg', bool $disponible = true, int $quantite = 10): Stock
    {
        $medicament = Medicament::firstOrCreate(['nom' => $nomMedicament]);

        return Stock::create([
            'pharmacie_id' => $pharmacie->id,
            'medicament_id' => $medicament->id,
            'disponible' => $disponible,
            'quantite' => $quantite,
        ]);
    }
}
