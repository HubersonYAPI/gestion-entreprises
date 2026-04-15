<?php

/*
|--------------------------------------------------------------------------
| tests/Feature/DocumentTest.php
|--------------------------------------------------------------------------
| Routes testées :
|
|   GET    /declarations/{declaration}/documents  → documents.index
|   POST   /declarations/{declaration}/documents  → documents.store
|   DELETE /documents/{document}                  → documents.destroy
|--------------------------------------------------------------------------
*/

use App\Models\User;
use App\Models\Gerant;
use App\Models\Entreprise;
use App\Models\Declaration;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

    $this->user        = User::factory()->create();
    $this->gerant      = Gerant::factory()->create(['user_id' => $this->user->id]);
    $this->entreprise  = Entreprise::factory()->create(['gerant_id' => $this->gerant->id]);
    $this->declaration = Declaration::factory()->create([
        'entreprise_id' => $this->entreprise->id,
        'statut'        => 'brouillon',
    ]);
    $this->actingAs($this->user);
});


// ── GET /declarations/{id}/documents  →  documents.index ─────────────────
test('GET /declarations/{id}/documents affiche la liste des documents', function () {

    Document::factory()->count(2)->create(['declaration_id' => $this->declaration->id]);

    $response = $this->get(route('documents.index', $this->declaration));

    $response->assertOk();
    $response->assertViewHas('documents');
});

test('GET /declarations/{id}/documents redirige vers /login si non connecté', function () {

    auth()->logout();

    $this->get(route('documents.index', $this->declaration))->assertRedirect('/login');
});


// ── POST /declarations/{id}/documents  →  documents.store ────────────────
test('POST /declarations/{id}/documents ajoute un document', function () {

    $fichier = UploadedFile::fake()->create('rccm.pdf', 200, 'application/pdf');

    $response = $this->post(route('documents.store', $this->declaration), [
        'type' => 'RCCM',
        'file' => $fichier,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('documents', [
        'declaration_id' => $this->declaration->id,
        'type'           => 'RCCM',
    ]);
});

test('POST /declarations/{id}/documents valide la présence du fichier et du type', function () {

    $response = $this->post(route('documents.store', $this->declaration), []);

    $response->assertSessionHasErrors(['type', 'file']);
});

test('POST /declarations/{id}/documents enregistre le fichier sur le disque', function () {

    $fichier = UploadedFile::fake()->create('cc.pdf', 150, 'application/pdf');

    $this->post(route('documents.store', $this->declaration), [
        'type' => 'CC',
        'file' => $fichier,
    ]);

    $document = Document::where('declaration_id', $this->declaration->id)->first();

    Storage::disk('public')->assertExists($document->file_path);
});


// ── DELETE /documents/{document}  →  documents.destroy ───────────────────
test('DELETE /documents/{id} supprime le document', function () {

    $document = Document::factory()->create(['declaration_id' => $this->declaration->id]);

    $this->delete(route('documents.destroy', $document));

    $this->assertDatabaseMissing('documents', ['id' => $document->id]);
});

test('DELETE /documents/{id} retourne 403 pour le document d\'une autre déclaration', function () {

    // Autre gérant avec son document
    $autreUser       = User::factory()->create();
    $autreGerant     = Gerant::factory()->create(['user_id' => $autreUser->id]);
    $autreEntreprise = Entreprise::factory()->create(['gerant_id' => $autreGerant->id]);
    $autreDecl       = Declaration::factory()->create(['entreprise_id' => $autreEntreprise->id]);
    $autreDocument   = Document::factory()->create(['declaration_id' => $autreDecl->id]);

    $response = $this->delete(route('documents.destroy', $autreDocument));

    $response->assertForbidden();

    $this->assertDatabaseHas('documents', ['id' => $autreDocument->id]);
});
