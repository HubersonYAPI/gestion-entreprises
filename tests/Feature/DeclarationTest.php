<?php

/*
|--------------------------------------------------------------------------
| tests/Feature/DeclarationTest.php
|--------------------------------------------------------------------------
| Routes testées (toutes préfixées par le middleware auth) :
|
|   GET  /dashboard                          → DeclarationController@dashboard
|   GET  /declarations                       → declarations.index
|   POST /declarations                       → declarations.store
|   GET  /declarations/{declaration}         → declarations.show
|   PUT  /declarations/{declaration}         → declarations.update
|   DELETE /declarations/{declaration}       → declarations.destroy
|   POST /declarations/{declaration}/submit  → declarations.submit
|--------------------------------------------------------------------------
*/

use App\Models\User;
use App\Models\Gerant;
use App\Models\Entreprise;
use App\Models\Declaration;
use App\Models\Document;

beforeEach(function () {
    $this->user       = User::factory()->create();
    $this->gerant     = Gerant::factory()->create(['user_id' => $this->user->id]);
    $this->entreprise = Entreprise::factory()->create(['gerant_id' => $this->gerant->id]);
    $this->actingAs($this->user);
});


// ══════════════════════════════════════════════════════════════
// GET /dashboard  →  DeclarationController@dashboard
// ══════════════════════════════════════════════════════════════

test('GET /dashboard retourne 200 et passe $declarations à la vue', function () {

    Declaration::factory()->count(3)->create([
        'entreprise_id' => $this->entreprise->id,
        'statut'        => 'brouillon',
    ]);

    $response = $this->get('/dashboard');

    $response->assertOk();
    $response->assertViewHas('declarations');
});

test('GET /dashboard?statut=soumis ne retourne que les déclarations soumises', function () {

    Declaration::factory()->create(['entreprise_id' => $this->entreprise->id, 'statut' => 'soumis']);
    Declaration::factory()->create(['entreprise_id' => $this->entreprise->id, 'statut' => 'brouillon']);

    $response = $this->get('/dashboard?statut=soumis');

    $response->assertOk();

    $declarations = $response->viewData('declarations');
    expect($declarations)->toHaveCount(1);
    expect($declarations->first()->statut)->toBe('soumis');
});


// ══════════════════════════════════════════════════════════════
// GET /declarations  →  declarations.index
// ══════════════════════════════════════════════════════════════

test('GET /declarations affiche la liste des déclarations du gérant', function () {

    Declaration::factory()->count(2)->create(['entreprise_id' => $this->entreprise->id]);

    $response = $this->get(route('declarations.index'));

    $response->assertOk();
    $response->assertViewHas('declarations');
});


// ══════════════════════════════════════════════════════════════
// POST /declarations  →  declarations.store
// ══════════════════════════════════════════════════════════════

test('POST /declarations crée une déclaration en brouillon (phase 1)', function () {

    $response = $this->post(route('declarations.store'), [
        'entreprise_id'   => $this->entreprise->id,
        'nature_activite' => 'Commerce de détail',
        'secteur_activite'=> 'Commerce',
        'produits'        => 'Électronique',
        'effectifs'       => 5,
    ]);

    $response->assertRedirect(route('declarations.index'));

    $this->assertDatabaseHas('declarations', [
        'entreprise_id'   => $this->entreprise->id,
        'nature_activite' => 'Commerce de détail',
        'statut'          => 'brouillon',
        'phase'           => 1,
    ]);
});

test('POST /declarations valide les champs obligatoires', function () {

    $response = $this->post(route('declarations.store'), []);

    $response->assertSessionHasErrors([
        'entreprise_id',
        'nature_activite',
        'secteur_activite',
        'produits',
        'effectifs',
    ]);
});

test('POST /declarations génère automatiquement une référence DECL-', function () {

    $this->post(route('declarations.store'), [
        'entreprise_id'   => $this->entreprise->id,
        'nature_activite' => 'Test',
        'secteur_activite'=> 'Test',
        'produits'        => 'Test',
        'effectifs'       => 1,
    ]);

    $declaration = Declaration::latest()->first();

    expect($declaration->reference)->toStartWith('DECL-');
    expect($declaration->reference)->not->toBeEmpty();
});


// ══════════════════════════════════════════════════════════════
// GET /declarations/{declaration}  →  declarations.show
// ══════════════════════════════════════════════════════════════

test('GET /declarations/{id} affiche le détail de la déclaration', function () {

    $declaration = Declaration::factory()->create(['entreprise_id' => $this->entreprise->id]);

    $response = $this->get(route('declarations.show', $declaration));

    $response->assertOk();
    $response->assertViewHas('declaration');
});

test('GET /declarations/{id} retourne 403 si appartient à un autre gérant', function () {

    $autreUser    = User::factory()->create();
    $autreGerant  = Gerant::factory()->create(['user_id' => $autreUser->id]);
    $autreEntreprise = Entreprise::factory()->create(['gerant_id' => $autreGerant->id]);
    $autreDecl    = Declaration::factory()->create(['entreprise_id' => $autreEntreprise->id]);

    $response = $this->get(route('declarations.show', $autreDecl));

    $response->assertForbidden();
});


// ══════════════════════════════════════════════════════════════
// PUT /declarations/{declaration}  →  declarations.update
// ══════════════════════════════════════════════════════════════

test('PUT /declarations/{id} met à jour une déclaration en brouillon', function () {

    $declaration = Declaration::factory()->create([
        'entreprise_id'   => $this->entreprise->id,
        'statut'          => 'brouillon',
        'nature_activite' => 'Ancien texte',
    ]);

    $response = $this->put(route('declarations.update', $declaration), [
        'entreprise_id'   => $this->entreprise->id,
        'nature_activite' => 'Nouveau texte',
        'secteur_activite'=> 'Commerce',
        'produits'        => 'Produits divers',
        'effectifs'       => 10,
    ]);

    $response->assertRedirect(route('declarations.index'));

    $this->assertDatabaseHas('declarations', [
        'id'              => $declaration->id,
        'nature_activite' => 'Nouveau texte',
    ]);
});

test('PUT /declarations/{id} retourne 403 pour la déclaration d\'un autre gérant', function () {

    $autreUser       = User::factory()->create();
    $autreGerant     = Gerant::factory()->create(['user_id' => $autreUser->id]);
    $autreEntreprise = Entreprise::factory()->create(['gerant_id' => $autreGerant->id]);
    $autreDecl       = Declaration::factory()->create(['entreprise_id' => $autreEntreprise->id]);

    $response = $this->put(route('declarations.update', $autreDecl), [
        'entreprise_id'   => $this->entreprise->id,
        'nature_activite' => 'Hack',
        'secteur_activite'=> 'Hack',
        'produits'        => 'Hack',
        'effectifs'       => 1,
    ]);

    $response->assertForbidden();
});


// ══════════════════════════════════════════════════════════════
// DELETE /declarations/{declaration}  →  declarations.destroy
// ══════════════════════════════════════════════════════════════

test('DELETE /declarations/{id} supprime la déclaration', function () {

    $declaration = Declaration::factory()->create([
        'entreprise_id' => $this->entreprise->id,
        'statut'        => 'brouillon',
    ]);

    $this->delete(route('declarations.destroy', $declaration));

    $this->assertDatabaseMissing('declarations', ['id' => $declaration->id]);
});

test('DELETE /declarations/{id} retourne 403 pour la déclaration d\'un autre gérant', function () {

    $autreUser       = User::factory()->create();
    $autreGerant     = Gerant::factory()->create(['user_id' => $autreUser->id]);
    $autreEntreprise = Entreprise::factory()->create(['gerant_id' => $autreGerant->id]);
    $autreDecl       = Declaration::factory()->create(['entreprise_id' => $autreEntreprise->id]);

    $this->delete(route('declarations.destroy', $autreDecl))->assertForbidden();

    $this->assertDatabaseHas('declarations', ['id' => $autreDecl->id]);
});


// ══════════════════════════════════════════════════════════════
// POST /declarations/{declaration}/submit  →  declarations.submit
// ══════════════════════════════════════════════════════════════

test('POST /declarations/{id}/submit soumet la déclaration si tous les documents sont présents', function () {

    $declaration = Declaration::factory()->create([
        'entreprise_id' => $this->entreprise->id,
        'statut'        => 'brouillon',
        'phase'         => 1,
    ]);

    foreach (['RCCM', 'CC', 'produits', 'appareils', 'formulaire'] as $type) {
        Document::factory()->create(['declaration_id' => $declaration->id, 'type' => $type]);
    }

    $response = $this->post(route('declarations.submit', $declaration));

    $this->assertDatabaseHas('declarations', [
        'id'     => $declaration->id,
        'statut' => 'soumis',
        'phase'  => 2,
    ]);
});

test('POST /declarations/{id}/submit échoue si des documents sont manquants', function () {

    $declaration = Declaration::factory()->create([
        'entreprise_id' => $this->entreprise->id,
        'statut'        => 'brouillon',
    ]);

    // Seulement 2 documents sur 5 requis
    Document::factory()->create(['declaration_id' => $declaration->id, 'type' => 'RCCM']);
    Document::factory()->create(['declaration_id' => $declaration->id, 'type' => 'CC']);

    $response = $this->post(route('declarations.submit', $declaration));

    // Reste en brouillon
    $this->assertDatabaseHas('declarations', ['id' => $declaration->id, 'statut' => 'brouillon']);
    $response->assertSessionHas('error');
});

test('POST /declarations/{id}/submit retourne 403 pour la déclaration d\'un autre gérant', function () {

    $autreUser       = User::factory()->create();
    $autreGerant     = Gerant::factory()->create(['user_id' => $autreUser->id]);
    $autreEntreprise = Entreprise::factory()->create(['gerant_id' => $autreGerant->id]);
    $autreDecl       = Declaration::factory()->create(['entreprise_id' => $autreEntreprise->id]);

    $this->post(route('declarations.submit', $autreDecl))->assertForbidden();
});
