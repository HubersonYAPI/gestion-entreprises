<?php

use App\Models\Entreprise;
use App\Models\Gerant;
use App\Models\User;
use Database\Seeders\RoleSeeder;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ── Helpers ───────────────────────────────────────────────────────────────────

function gerantConnecte(): array
{
    $user   = User::factory()->create();
    $user->assignRole('GERANT');
    $gerant = Gerant::factory()->create(['user_id' => $user->id]);

    return compact('user', 'gerant');
}

function payloadEntreprise(array $override = []): array
{
    return array_merge([
        'nom'              => 'SIMAT CI',
        'rccm'             => 'CI-ABJ-2024-B-00001',
        'adresse'          => 'Plateau, Abidjan',
        'type_entreprise'  => 'SARL',
        'secteur_activite' => 'Commerce',
    ], $override);
}

// ── Setup ─────────────────────────────────────────────────────────────────────

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

// ── Index ─────────────────────────────────────────────────────────────────────

describe('EntrepriseController@index', function () {

    it('affiche les entreprises du gérant', function () {
        ['user' => $user, 'gerant' => $gerant] = gerantConnecte();
        Entreprise::factory()->count(3)->create(['gerant_id' => $gerant->id]);

        $this->actingAs($user)
            ->get(route('entreprises.index'))
            ->assertOk()
            ->assertViewIs('entreprises.index')
            ->assertViewHas('entreprises', fn ($e) => $e->count() === 3);
    });

    it('redirige si le profil gérant est manquant', function () {
        $user = User::factory()->create();
        $user->assignRole('GERANT');

        $this->actingAs($user)
            ->get(route('entreprises.index'))
            ->assertRedirect(route('gerant.edit'));
    });
});

// ── Store ─────────────────────────────────────────────────────────────────────

describe('EntrepriseController@store', function () {

    it('crée une entreprise', function () {
        ['user' => $user, 'gerant' => $gerant] = gerantConnecte();

        $this->actingAs($user)
            ->post(route('entreprises.store'), payloadEntreprise())
            ->assertRedirect(route('entreprises.index'));

        expect(Entreprise::where('gerant_id', $gerant->id)->count())->toBe(1);
        expect(Entreprise::first()->nom)->toBe('SIMAT CI');
    });

    it('valide les champs requis', function () {
        ['user' => $user] = gerantConnecte();

        $this->actingAs($user)
            ->post(route('entreprises.store'), [])
            ->assertSessionHasErrors(['nom', 'rccm', 'adresse', 'type_entreprise', 'secteur_activite']);
    });
});

// ── Update ────────────────────────────────────────────────────────────────────

describe('EntrepriseController@update', function () {

    it('met à jour une entreprise du gérant', function () {
        ['user' => $user, 'gerant' => $gerant] = gerantConnecte();
        $entreprise = Entreprise::factory()->create(['gerant_id' => $gerant->id]);

        $this->actingAs($user)
            ->put(route('entreprises.update', $entreprise), payloadEntreprise(['nom' => 'PRODEX CI']))
            ->assertRedirect(route('entreprises.index'));

        expect($entreprise->fresh()->nom)->toBe('PRODEX CI');
    });

    it('interdit la mise à jour d\'une entreprise d\'un autre gérant', function () {
        ['user' => $user]          = gerantConnecte();
        ['gerant' => $autreGerant] = gerantConnecte();
        $entreprise                = Entreprise::factory()->create(['gerant_id' => $autreGerant->id]);

        $this->actingAs($user)
            ->put(route('entreprises.update', $entreprise), payloadEntreprise())
            ->assertForbidden();
    });
});

// ── Destroy ───────────────────────────────────────────────────────────────────

describe('EntrepriseController@destroy', function () {

    it('supprime une entreprise du gérant', function () {
        ['user' => $user, 'gerant' => $gerant] = gerantConnecte();
        $entreprise = Entreprise::factory()->create(['gerant_id' => $gerant->id]);

        $this->actingAs($user)
            ->delete(route('entreprises.destroy', $entreprise))
            ->assertRedirect(route('entreprises.index'));

        expect(Entreprise::find($entreprise->id))->toBeNull();
    });

    it('interdit la suppression d\'une entreprise d\'un autre gérant', function () {
        ['user' => $user]          = gerantConnecte();
        ['gerant' => $autreGerant] = gerantConnecte();
        $entreprise                = Entreprise::factory()->create(['gerant_id' => $autreGerant->id]);

        $this->actingAs($user)
            ->delete(route('entreprises.destroy', $entreprise))
            ->assertForbidden();
    });
});
