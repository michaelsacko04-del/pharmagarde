<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreeDonnees;
use Tests\TestCase;

class RechercheTest extends TestCase
{
    use RefreshDatabase, CreeDonnees;

    public function test_la_page_d_accueil_repond(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_la_recherche_trouve_une_pharmacie_de_garde_avec_stock(): void
    {
        $pharmacie = $this->pharmacie(['nom' => 'Pharmacie Alpha']);
        $this->stock($pharmacie);

        $this->get('/?q=para')->assertOk()->assertSee('Pharmacie Alpha');
    }

    public function test_une_pharmacie_non_validee_est_invisible(): void
    {
        $pharmacie = $this->pharmacie(['nom' => 'Pharmacie Beta', 'validee' => false]);
        $this->stock($pharmacie);

        $this->get('/?q=para')->assertOk()->assertDontSee('Pharmacie Beta');
    }

    public function test_une_pharmacie_sans_garde_en_cours_est_invisible(): void
    {
        $pharmacie = $this->pharmacie(['nom' => 'Pharmacie Gamma'], null, false);
        $this->stock($pharmacie);

        $this->get('/?q=para')->assertOk()->assertDontSee('Pharmacie Gamma');
    }

    public function test_un_medicament_indisponible_ne_remonte_pas(): void
    {
        $pharmacie = $this->pharmacie(['nom' => 'Pharmacie Delta']);
        $this->stock($pharmacie, 'Paracétamol 500 mg', false, 0);

        $this->get('/?q=para')->assertOk()->assertDontSee('Pharmacie Delta');
    }

    public function test_une_recherche_trop_courte_est_refusee(): void
    {
        $this->get('/?q=a')->assertSessionHasErrors('q');
    }
}
