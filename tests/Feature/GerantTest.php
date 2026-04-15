<?php

/*
|--------------------------------------------------------------------------
| tests/Feature/GerantTest.php
|--------------------------------------------------------------------------
| Routes testées :
|
|   GET  /gerant          → gerant.show   (GerantController@show)
|   GET  /profile-gerant  → gerant.edit   (GerantController@edit)
|   POST /profile-gerant  → gerant.update (GerantController@update)
|--------------------------------------------------------------------------
*/

use App\Models\User;
use App\Models\Gerant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user   = User::factory()->create();
    $this->gerant = Gerant::factory()->create(['user_id' => $this->user->id]);
    $this->actingAs($this->user);
});


// ── GET /gerant  →  gerant.show ──────────────────────────────────────────
test('GET /gerant affiche le profil gérant', function () {

    $response = $this->get(route('gerant.show'));

    $response->assertOk();
    $response->assertViewHas('gerant');
});

test('GET /gerant redirige vers /login si non connecté', function () {

    auth()->logout();

    $this->get(route('gerant.show'))->assertRedirect('/login');
});


// ── GET /profile-gerant  →  gerant.edit ──────────────────────────────────
test('GET /profile-gerant affiche le formulaire de modification', function () {

    $response = $this->get(route('gerant.edit'));

    $response->assertOk();
});


// ── POST /profile-gerant  →  gerant.update ───────────────────────────────
test('POST /profile-gerant met à jour les informations du gérant', function () {

    $response = $this->post(route('gerant.update'), [
        'nom'     => 'KOUAMÉ',
        'prenoms' => 'Jean-Baptiste',
        'contact' => '+225 07 00 00 00 00',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('gerants', [
        'id'      => $this->gerant->id,
        'nom'     => 'KOUAMÉ',
        'prenoms' => 'Jean-Baptiste',
        'contact' => '+225 07 00 00 00 00',
    ]);
});

test('POST /profile-gerant valide les champs obligatoires', function () {

    $response = $this->post(route('gerant.update'), []);

    $response->assertSessionHasErrors(['nom', 'prenoms', 'contact']);
});

test('POST /profile-gerant enregistre la pièce d\'identité sur le disque', function () {

    Storage::fake('public');

    $fichier = UploadedFile::fake()->create('piece_identite.pdf', 500, 'application/pdf');

    $this->post(route('gerant.update'), [
        'nom'            => 'KOUAMÉ',
        'prenoms'        => 'Jean',
        'contact'        => '+225 07 00 00 00 00',
        'piece_identite' => $fichier,
    ]);

    $gerantMisAJour = $this->gerant->fresh();

    expect($gerantMisAJour->piece_identite)->not->toBeNull();

    Storage::disk('public')->assertExists($gerantMisAJour->piece_identite);
});

test('POST /profile-gerant redirige vers /login si non connecté', function () {

    auth()->logout();

    $this->post(route('gerant.update'), [
        'nom'     => 'Test',
        'prenoms' => 'Test',
        'contact' => '0700000000',
    ])->assertRedirect('/login');
});
