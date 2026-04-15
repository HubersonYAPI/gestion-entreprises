<?php

/*
|--------------------------------------------------------------------------
| tests/Feature/EntrepriseTest.php
|--------------------------------------------------------------------------
| Routes testées (Resource controller, middleware auth) :
|
|   GET    /entreprises              → entreprises.index
|   GET    /entreprises/create       → entreprises.create
|   POST   /entreprises              → entreprises.store
|   GET    /entreprises/{id}/edit    → entreprises.edit
|   PUT    /entreprises/{id}         → entreprises.update
|   DELETE /entreprises/{id}         → entreprises.destroy
|--------------------------------------------------------------------------
*/

use App\Models\User;
use App\Models\Gerant;
use App\Models\Entreprise;

beforeEach(function () {
    $this->user   = User::factory()->create();
    $this->gerant = Gerant::factory()->create(['user_id' => $this->user->id]);
    $this->actingAs($this->user);
});


// ── GET /entreprises ──────────────────────────────────────────────────────
test('GET /entreprises affiche la liste des entreprises du gérant', function () {

    Entreprise::factory()->count(2)->create(['gerant_id' => $this->gerant->id]);

    $response = $this->get(route('entreprises.index'));

    $response->assertOk();
    $response->assertViewHas('entreprises');
});


// ── GET /entreprises/create ───────────────────────────────────────────────
test('GET /entreprises/create affiche le formulaire de création', function () {

    $response = $this->get(route('entreprises.create'));

    $response->assertOk();
});


// ── POST /entreprises ─────────────────────────────────────────────────────
test('POST /entreprises crée une entreprise et redirige', function () {

    $response = $this->post(route('entreprises.store'), [
        'nom'             => 'Ma Société SARL',
        'rccm'            => 'CI-ABJ-2024-B-0001',
        'adresse'         => 'Plateau, Abidjan',
        'type_entreprise' => 'SARL',
        'secteur_activite'=> 'Commerce',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('entreprises', [
        'nom'  => 'Ma Société SARL',
        'rccm' => 'CI-ABJ-2024-B-0001',
    ]);
});

test('POST /entreprises valide les champs obligatoires', function () {

    $response = $this->post(route('entreprises.store'), []);

    $response->assertSessionHasErrors([
        'nom',
        'rccm',
        'adresse',
        'type_entreprise',
        'secteur_activite',
    ]);
});


// ── GET /entreprises/{id}/edit ────────────────────────────────────────────
test('GET /entreprises/{id}/edit affiche le formulaire de modification', function () {

    $entreprise = Entreprise::factory()->create(['gerant_id' => $this->gerant->id]);

    $response = $this->get(route('entreprises.edit', $entreprise));

    $response->assertOk();
    $response->assertViewHas('entreprise');
});


// ── PUT /entreprises/{id} ─────────────────────────────────────────────────
test('PUT /entreprises/{id} met à jour l\'entreprise', function () {

    $entreprise = Entreprise::factory()->create([
        'gerant_id' => $this->gerant->id,
        'nom'       => 'Ancien nom',
    ]);

    $response = $this->put(route('entreprises.update', $entreprise), [
        'nom'             => 'Nouveau nom',
        'rccm'            => $entreprise->rccm,
        'adresse'         => $entreprise->adresse,
        'type_entreprise' => $entreprise->type_entreprise,
        'secteur_activite'=> $entreprise->secteur_activite,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('entreprises', [
        'id'  => $entreprise->id,
        'nom' => 'Nouveau nom',
    ]);
});

test('PUT /entreprises/{id} retourne 403 pour l\'entreprise d\'un autre gérant', function () {

    $autreUser       = User::factory()->create();
    $autreGerant     = Gerant::factory()->create(['user_id' => $autreUser->id]);
    $autreEntreprise = Entreprise::factory()->create(['gerant_id' => $autreGerant->id]);

    $response = $this->put(route('entreprises.update', $autreEntreprise), [
        'nom'             => 'Tentative hack',
        'rccm'            => 'CI-XXX',
        'adresse'         => 'Nulle part',
        'type_entreprise' => 'SARL',
        'secteur_activite'=> 'Piratage',
    ]);

    $response->assertForbidden();

    $this->assertDatabaseMissing('entreprises', ['nom' => 'Tentative hack']);
});


// ── DELETE /entreprises/{id} ──────────────────────────────────────────────
test('DELETE /entreprises/{id} supprime l\'entreprise', function () {

    $entreprise = Entreprise::factory()->create(['gerant_id' => $this->gerant->id]);

    $this->delete(route('entreprises.destroy', $entreprise));

    $this->assertDatabaseMissing('entreprises', ['id' => $entreprise->id]);
});

test('DELETE /entreprises/{id} retourne 403 pour l\'entreprise d\'un autre gérant', function () {

    $autreUser       = User::factory()->create();
    $autreGerant     = Gerant::factory()->create(['user_id' => $autreUser->id]);
    $autreEntreprise = Entreprise::factory()->create(['gerant_id' => $autreGerant->id]);

    $this->delete(route('entreprises.destroy', $autreEntreprise))->assertForbidden();

    $this->assertDatabaseHas('entreprises', ['id' => $autreEntreprise->id]);
});
