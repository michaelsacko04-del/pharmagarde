<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreeDonnees;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase, CreeDonnees;

    public function test_un_pharmacien_modifie_le_stock_de_sa_pharmacie(): void
    {
        $pharmacien = $this->utilisateur('pharmacien');
        $stock = $this->stock($this->pharmacie([], $pharmacien));

        $this->actingAs($pharmacien)
            ->put(route('pharmacien.stocks.update', $stock), ['disponible' => 1, 'quantite' => 42])
            ->assertSessionHas('succes');

        $this->assertDatabaseHas('stocks', ['id' => $stock->id, 'disponible' => true, 'quantite' => 42]);
    }

    public function test_un_pharmacien_ne_peut_pas_modifier_le_stock_d_une_autre_pharmacie(): void
    {
        $pharmacien = $this->utilisateur('pharmacien');
        $this->pharmacie([], $pharmacien);

        $autre = $this->utilisateur('pharmacien');
        $stockAutre = $this->stock($this->pharmacie([], $autre));

        $this->actingAs($pharmacien)
            ->put(route('pharmacien.stocks.update', $stockAutre), ['disponible' => 0, 'quantite' => 0])
            ->assertForbidden();

        $this->assertDatabaseHas('stocks', ['id' => $stockAutre->id, 'disponible' => true, 'quantite' => 10]);
    }

    public function test_un_medicament_disponible_exige_une_quantite(): void
    {
        $pharmacien = $this->utilisateur('pharmacien');
        $stock = $this->stock($this->pharmacie([], $pharmacien));

        $this->actingAs($pharmacien)
            ->put(route('pharmacien.stocks.update', $stock), ['disponible' => 1, 'quantite' => 0])
            ->assertSessionHasErrors('quantite');
    }

    public function test_un_usager_ne_peut_pas_modifier_un_stock(): void
    {
        $stock = $this->stock($this->pharmacie());

        $this->actingAs($this->utilisateur('usager'))
            ->put(route('pharmacien.stocks.update', $stock), ['disponible' => 0, 'quantite' => 0])
            ->assertForbidden();
    }
}
