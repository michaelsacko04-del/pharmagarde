<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreeDonnees;
use Tests\TestCase;

class AccesTest extends TestCase
{
    use RefreshDatabase, CreeDonnees;

    public function test_un_visiteur_est_redirige_vers_la_connexion(): void
    {
        $this->get('/pharmacien')->assertRedirect(route('login'));
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_un_usager_ne_peut_pas_entrer_dans_les_espaces_proteges(): void
    {
        $usager = $this->utilisateur('usager');

        $this->actingAs($usager)->get('/pharmacien')->assertForbidden();
        $this->actingAs($usager)->get('/admin')->assertForbidden();
    }

    public function test_un_pharmacien_ne_peut_pas_entrer_dans_l_espace_admin(): void
    {
        $this->actingAs($this->utilisateur('pharmacien'))->get('/admin')->assertForbidden();
    }

    public function test_un_admin_accede_a_son_tableau_de_bord(): void
    {
        $this->actingAs($this->utilisateur('admin'))->get('/admin')->assertOk();
    }

    public function test_la_connexion_d_un_admin_mene_a_son_tableau_de_bord(): void
    {
        $admin = $this->utilisateur('admin');

        $this->post('/connexion', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.tableau'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_la_connexion_d_un_pharmacien_mene_a_son_espace(): void
    {
        $pharmacien = $this->utilisateur('pharmacien');

        $this->post('/connexion', ['email' => $pharmacien->email, 'password' => 'password'])
            ->assertRedirect(route('pharmacien.tableau'));
    }

    public function test_un_mauvais_mot_de_passe_est_refuse(): void
    {
        $admin = $this->utilisateur('admin');

        $this->post('/connexion', ['email' => $admin->email, 'password' => 'faux'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_les_tentatives_de_connexion_sont_limitees(): void
    {
        $admin = $this->utilisateur('admin');

        for ($i = 0; $i < 5; $i++) {
            $this->post('/connexion', ['email' => $admin->email, 'password' => 'faux']);
        }

        $this->post('/connexion', ['email' => $admin->email, 'password' => 'faux'])
            ->assertStatus(429);
    }

    public function test_la_deconnexion_fonctionne(): void
    {
        $this->actingAs($this->utilisateur('pharmacien'))
            ->post('/deconnexion')
            ->assertRedirect(route('recherche'));

        $this->assertGuest();
    }
}
