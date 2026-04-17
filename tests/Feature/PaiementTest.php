<?php

use App\Models\User;
use App\Models\Gerant;
use App\Models\Entreprise;
use App\Models\Declaration;
use App\Models\Paiement;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {

    $this->user        = User::factory()->create();
    $this->gerant      = Gerant::factory()->create(['user_id' => $this->user->id]);
    $this->entreprise  = Entreprise::factory()->create(['gerant_id' => $this->gerant->id]);

    $this->declaration = Declaration::factory()->create([
        'entreprise_id'          => $this->entreprise->id,
        'statut'                 => 'validé',
        'phase'                  => 4,
        'date_limite_paiement'   => now()->addHours(48),
    ]);

    $this->actingAs($this->user);
});


// ─────────────────────────────────────────────
// GET /paiement/{declaration} → show
// ─────────────────────────────────────────────

test('GET /paiement/{id} affiche la page de paiement', function () {

    $response = $this->get(route('paiement.show', $this->declaration));

    $response->assertOk();
    $response->assertViewIs('paiements.show');
    $response->assertViewHas('declaration');
});

test('GET /paiement/{id} interdit accès à un autre gerant', function () {

    $autreUser       = User::factory()->create();
    $autreGerant     = Gerant::factory()->create(['user_id' => $autreUser->id]);
    $autreEntreprise = Entreprise::factory()->create(['gerant_id' => $autreGerant->id]);
    $autreDecl       = Declaration::factory()->create([
        'entreprise_id' => $autreEntreprise->id,
        'statut' => 'validé'
    ]);

    $response = $this->get(route('paiement.show', $autreDecl));

    $response->assertForbidden();
});


// ─────────────────────────────────────────────
// POST /paiement/{declaration} → payer
// ─────────────────────────────────────────────

test('POST /paiement effectue un paiement avec succès', function () {

    $response = $this->post(route('paiement.payer', $this->declaration));

    $response->assertRedirect(route('declarations.index'));

    // Paiement créé
    $this->assertDatabaseHas('paiements', [
        'declaration_id' => $this->declaration->id,
        'statut'         => 'payé',
    ]);

    // Déclaration mise à jour
    $this->assertDatabaseHas('declarations', [
        'id'     => $this->declaration->id,
        'statut' => 'validé',
        'phase'  => 4,
    ]);
});

test('POST /paiement empêche double paiement', function () {

    Paiement::factory()->create([
        'declaration_id' => $this->declaration->id,
    ]);

    $response = $this->post(route('paiement.payer', $this->declaration));

    $response->assertSessionHas('error');

    // Toujours un seul paiement
    $this->assertEquals(1, Paiement::count());
});

test('POST /paiement expire si date dépassée', function () {

    $this->declaration->update([
        'date_limite_paiement' => now()->subHour(),
    ]);

    $response = $this->post(route('paiement.payer', $this->declaration));

    $response->assertRedirect(route('declarations.index'));

    $this->assertDatabaseHas('declarations', [
        'id'     => $this->declaration->id,
        'statut' => 'expiré',
    ]);
});

test('POST /paiement interdit accès à un autre gerant', function () {

    $autreUser       = User::factory()->create();
    $autreGerant     = Gerant::factory()->create(['user_id' => $autreUser->id]);
    $autreEntreprise = Entreprise::factory()->create(['gerant_id' => $autreGerant->id]);
    $autreDecl       = Declaration::factory()->create([
        'entreprise_id' => $autreEntreprise->id,
        'statut' => 'validé'
    ]);

    $response = $this->post(route('paiement.payer', $autreDecl));

    $response->assertForbidden();
});

test('POST /paiement redirige vers login si non connecté', function () {

    auth()->logout();

    $response = $this->post(route('paiement.payer', $this->declaration));

    $response->assertRedirect('/login');
});