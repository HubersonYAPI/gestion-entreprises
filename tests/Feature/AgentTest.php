<?php

/*
|--------------------------------------------------------------------------
| tests/Feature/AgentTest.php
|--------------------------------------------------------------------------
| Routes testées (préfixe /agent, middleware role:AGENT|CONTROLEUR|SUPER_ADMIN) :
|
|   GET  /agent/dashboard                           → agent.dashboard
|   GET  /agent/declarations/soumis                 → agent.declarations.soumis
|   GET  /agent/declarations/non-paye               → agent.declarations.non-paye
|   GET  /agent/declarations/en-traitement          → agent.declarations.en-traitement
|   GET  /agent/declarations/valider-liste          → agent.declarations.valider
|   GET  /agent/declarations/rejeter-liste          → agent.declarations.rejeter
|   GET  /agent/declarations/{id}                   → agent.declarations.show
|   GET  /agent/declarations/{id}/documents         → agent.declaration.documents
|   POST /agent/declarations/{id}/valider           → agent.valider
|   POST /agent/declarations/{id}/rejeter           → agent.rejeter
|   POST /agent/documents/{id}/valider              → agent.documents.valider
|   POST /agent/documents/{id}/rejeter              → agent.documents.rejeter
|--------------------------------------------------------------------------
*/

use App\Models\User;
use App\Models\Gerant;
use App\Models\Entreprise;
use App\Models\Declaration;
use App\Models\Document;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'AGENT',       'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'CONTROLEUR',  'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'SUPER_ADMIN', 'guard_name' => 'web']);

    // Agent connecté
    $this->agent = User::factory()->create();
    $this->agent->assignRole('AGENT');

    // Gérant avec une déclaration soumise (phase > 1)
    $gerantUser        = User::factory()->create();
    $gerant            = Gerant::factory()->create(['user_id' => $gerantUser->id]);
    $this->entreprise  = Entreprise::factory()->create(['gerant_id' => $gerant->id]);
    $this->declaration = Declaration::factory()->create([
        'entreprise_id' => $this->entreprise->id,
        'statut'        => 'soumis',
        'phase'         => 2,
    ]);

    $this->actingAs($this->agent);
});


// ══════════════════════════════════════════════════════════════
// GET /agent/dashboard  →  agent.dashboard
// ══════════════════════════════════════════════════════════════

test('GET /agent/dashboard retourne 200 pour un agent', function () {

    $response = $this->get(route('agent.dashboard'));

    $response->assertOk();
    $response->assertViewHas('declarations');
});

test('GET /agent/dashboard est interdit aux gérants (sans rôle)', function () {

    $gerant = User::factory()->create(); // pas de rôle admin

    $response = $this->actingAs($gerant)->get(route('agent.dashboard'));

    expect($response->status())->toBeIn([302, 403]);
});

test('GET /agent/dashboard est accessible à un SUPER_ADMIN', function () {

    $admin = User::factory()->create();
    $admin->assignRole('SUPER_ADMIN');

    $this->actingAs($admin)->get(route('agent.dashboard'))->assertOk();
});


// ══════════════════════════════════════════════════════════════
// Filtres statuts  →  agent.declarations.*
// ══════════════════════════════════════════════════════════════

test('GET /agent/declarations/soumis ne retourne que les déclarations soumises', function () {

    // Déclaration validée — ne doit PAS apparaître
    Declaration::factory()->create([
        'entreprise_id' => $this->entreprise->id,
        'statut'        => 'valide',
        'phase'         => 3,
    ]);

    $response = $this->get(route('agent.declarations.soumis'));

    $response->assertOk();

    foreach ($response->viewData('declarations') as $d) {
        expect($d->statut)->toBe('soumis');
    }
});

test('GET /agent/declarations/non-paye ne retourne que les déclarations non payées', function () {

    Declaration::factory()->create([
        'entreprise_id' => $this->entreprise->id,
        'statut'        => 'non_paye',
        'phase'         => 3,
    ]);

    $response = $this->get(route('agent.declarations.non-paye'));

    $response->assertOk();

    foreach ($response->viewData('declarations') as $d) {
        expect($d->statut)->toBe('non_paye');
    }
});

test('GET /agent/declarations/en-traitement ne retourne que les déclarations en traitement', function () {

    Declaration::factory()->create([
        'entreprise_id' => $this->entreprise->id,
        'statut'        => 'en_traitement',
        'phase'         => 2,
    ]);

    $response = $this->get(route('agent.declarations.en-traitement'));

    $response->assertOk();

    foreach ($response->viewData('declarations') as $d) {
        expect($d->statut)->toBe('en_traitement');
    }
});


// ══════════════════════════════════════════════════════════════
// GET /agent/declarations/{id}  →  agent.declarations.show
// ══════════════════════════════════════════════════════════════

test('GET /agent/declarations/{id} affiche le détail d\'une déclaration', function () {

    $response = $this->get(route('agent.declarations.show', $this->declaration));

    $response->assertOk();
    $response->assertViewHas('declaration');
});


// ══════════════════════════════════════════════════════════════
// GET /agent/declarations/{id}/documents  →  agent.declaration.documents
// ══════════════════════════════════════════════════════════════

test('GET /agent/declarations/{id}/documents affiche les documents', function () {

    Document::factory()->count(2)->create(['declaration_id' => $this->declaration->id]);

    $response = $this->get(route('agent.declaration.documents', $this->declaration));

    $response->assertOk();
    $response->assertViewHas('documents');
});


// ══════════════════════════════════════════════════════════════
// POST /agent/documents/{id}/valider  →  agent.documents.valider
// ══════════════════════════════════════════════════════════════

test('POST /agent/documents/{id}/valider passe le document à "validé"', function () {

    $document = Document::factory()->create([
        'declaration_id' => $this->declaration->id,
        'statut'         => 'en_attente',
    ]);

    $this->post(route('agent.documents.valider', $document));

    $this->assertDatabaseHas('documents', [
        'id'     => $document->id,
        'statut' => 'validé',
    ]);
});


// ══════════════════════════════════════════════════════════════
// POST /agent/documents/{id}/rejeter  →  agent.documents.rejeter
// ══════════════════════════════════════════════════════════════

test('POST /agent/documents/{id}/rejeter passe le document à "rejeté"', function () {

    $document = Document::factory()->create([
        'declaration_id' => $this->declaration->id,
        'statut'         => 'en_attente',
    ]);

    $this->post(route('agent.documents.rejeter', $document));

    $this->assertDatabaseHas('documents', [
        'id'     => $document->id,
        'statut' => 'rejeté',
    ]);
});


// ══════════════════════════════════════════════════════════════
// POST /agent/declarations/{id}/valider  →  agent.valider
// ══════════════════════════════════════════════════════════════

test('POST /agent/declarations/{id}/valider valide la déclaration si tous les documents sont validés', function () {

    Document::factory()->count(3)->create([
        'declaration_id' => $this->declaration->id,
        'statut'         => 'validé',
    ]);

    $response = $this->post(route('agent.valider', $this->declaration));

    $response->assertRedirect(route('agent.dashboard'));

    $this->assertDatabaseHas('declarations', [
        'id'     => $this->declaration->id,
        'statut' => 'validé',
        'phase'  => 3,
    ]);
});

test('POST /agent/declarations/{id}/valider échoue si un document n\'est pas validé', function () {

    Document::factory()->create([
        'declaration_id' => $this->declaration->id,
        'statut'         => 'en_attente', // ← pas validé
    ]);

    $response = $this->post(route('agent.valider', $this->declaration));

    $response->assertSessionHas('error');

    $this->assertDatabaseHas('declarations', [
        'id'     => $this->declaration->id,
        'statut' => 'soumis', // inchangé
    ]);
});


// ══════════════════════════════════════════════════════════════
// POST /agent/declarations/{id}/rejeter  →  agent.rejeter
// ══════════════════════════════════════════════════════════════

test('POST /agent/declarations/{id}/rejeter rejette la déclaration avec un commentaire', function () {

    $response = $this->post(route('agent.rejeter', $this->declaration), [
        'commentaire' => 'Document RCCM illisible.',
    ]);

    $response->assertRedirect(route('agent.dashboard'));

    $this->assertDatabaseHas('declarations', [
        'id'     => $this->declaration->id,
        'statut' => 'rejeté',
        'phase'  => 5,
    ]);
});

test('POST /agent/declarations/{id}/rejeter échoue sans commentaire', function () {

    $response = $this->post(route('agent.rejeter', $this->declaration), [
        'commentaire' => '', // vide
    ]);

    $response->assertSessionHasErrors(['commentaire']);

    // Déclaration non modifiée
    $this->assertDatabaseHas('declarations', [
        'id'     => $this->declaration->id,
        'statut' => 'soumis',
    ]);
});
