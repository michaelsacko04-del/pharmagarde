<?php

namespace Tests\Feature;

use App\Models\Pharmacie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreeDonnees;
use Tests\TestCase;

class GardeTest extends TestCase
{
    use RefreshDatabase, CreeDonnees;

    public function test_un_admin_cree_une_garde(): void
    {
        $pharmacie = $this->pharmacie([], null, false);

        $this->actingAs($this->utilisateur('admin'))
            ->post(route('admin.gardes.store'), [
                'pharmacie_id' => $pharmacie->id,
                'debut' => today()->toDateString(),
                'fin' => today()->addDays(3)->toDateString(),
            ])
            ->assertSessionHas('succes');

        $this->assertDatabaseCount('gardes', 1);
    }

    public function test_deux_gardes_qui_se_chevauchent_sont_refusees(): void
    {
        // La pharmacie a déjà une garde de la veille à dans deux jours.
        $pharmacie = $this->pharmacie();

        $this->actingAs($this->utilisateur('admin'))
            ->post(route('admin.gardes.store'), [
                'pharmacie_id' => $pharmacie->id,
                'debut' => today()->addDay()->toDateString(),
                'fin' => today()->addDays(5)->toDateString(),
            ])
            ->assertSessionHasErrors('debut');

        $this->assertDatabaseCount('gardes', 1);
    }

    public function test_une_fin_avant_le_debut_est_refusee(): void
    {
        $pharmacie = $this->pharmacie([], null, false);

        $this->actingAs($this->utilisateur('admin'))
            ->post(route('admin.gardes.store'), [
                'pharmacie_id' => $pharmacie->id,
                'debut' => today()->addDays(5)->toDateString(),
                'fin' => today()->toDateString(),
            ])
            ->assertSessionHasErrors('fin');
    }

    public function test_une_pharmacie_non_validee_ne_peut_pas_recevoir_de_garde(): void
    {
        $pharmacie = $this->pharmacie(['validee' => false], null, false);

        $this->actingAs($this->utilisateur('admin'))
            ->post(route('admin.gardes.store'), [
                'pharmacie_id' => $pharmacie->id,
                'debut' => today()->toDateString(),
                'fin' => today()->addDays(2)->toDateString(),
            ])
            ->assertSessionHasErrors('pharmacie_id');
    }

    public function test_un_pharmacien_ne_peut_pas_creer_de_garde(): void
    {
        $pharmacie = $this->pharmacie([], null, false);

        $this->actingAs($this->utilisateur('pharmacien'))
            ->post(route('admin.gardes.store'), [
                'pharmacie_id' => $pharmacie->id,
                'debut' => today()->toDateString(),
                'fin' => today()->addDays(2)->toDateString(),
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('gardes', 0);
    }

    public function test_le_scope_de_garde_utilise_la_date_du_jour(): void
    {
        $enCours = $this->pharmacie();
        $passee = $this->pharmacie([], null, false);
        $passee->gardes()->create([
            'debut' => today()->subDays(10)->toDateString(),
            'fin' => today()->subDays(5)->toDateString(),
        ]);

        $ids = Pharmacie::deGarde()->pluck('id');

        $this->assertTrue($ids->contains($enCours->id));
        $this->assertFalse($ids->contains($passee->id));
    }
}
