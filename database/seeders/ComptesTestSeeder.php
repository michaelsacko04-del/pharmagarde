<?php

namespace Database\Seeders;

use App\Models\Pharmacie;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ComptesTestSeeder extends Seeder
{
    private const MOT_DE_PASSE_TEST = 'TestPharma-2026';

    public function run(): void
    {
        // Comptes de démonstration : jamais en production.
        if (app()->environment('production')) {
            $this->command?->warn('Comptes de test ignorés en production.');

            return;
        }

        $this->creerCompte('Administrateur', 'admin@pharmagarde.test', 'admin');

        $pharmacien1 = $this->creerCompte('Pharmacien Tanghin', 'pharmacien1@pharmagarde.test', 'pharmacien');
        Pharmacie::where('nom', 'Pharmacie Test Tanghin')->update(['user_id' => $pharmacien1->id]);

        $pharmacien2 = $this->creerCompte('Pharmacien Dapoya', 'pharmacien2@pharmagarde.test', 'pharmacien');
        Pharmacie::where('nom', 'Pharmacie Test Dapoya')->update(['user_id' => $pharmacien2->id]);
    }

    private function creerCompte(string $nom, string $email, string $role): User
    {
        $user = User::firstOrNew(['email' => $email]);

        // forceFill : le rôle est fixé par notre code, jamais par un formulaire.
        $user->forceFill([
            'name' => $nom,
            'password' => Hash::make(self::MOT_DE_PASSE_TEST),
            'role' => $role,
            'email_verified_at' => now(),
        ])->save();

        return $user;
    }
}
