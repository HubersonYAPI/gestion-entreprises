<?php

use App\Models\Declaration;
use App\Models\Document;
use App\Models\Entreprise;
use App\Models\Gerant;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ── Helpers ───────────────────────────────────────────────────────────────────

function gerantAvecDeclaration(): array
{
    $user       = User::factory()->create();
    $user->assignRole('GERANT');
    $gerant     = Gerant::factory()->create(['user_id' => $user->id]);
    $entreprise = Entreprise::factory()->create(['gerant_id' => $gerant->id]);
    $declaration = Declaration::factory()->create(['entreprise_id' => $entreprise->id]);

    return compact('user', 'gerant', 'declaration');
}

// ── Setup ─────────────────────────────────────────────────────────────────────

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    Storage::fake('public');
});

// ── Index ─────────────────────────────────────────────────────────────────────

describe('DocumentController@index', function () {

    it('affiche les documents d\'une déclaration', function () {
        ['user' => $user, 'declaration' => $declaration] = gerantAvecDeclaration();

        $this->actingAs($user)
            ->get(route('documents.index', $declaration))
            ->assertOk()
            ->assertViewIs('documents.index')
            ->assertViewHas('declaration')
            ->assertViewHas('typesDejaPresents');
    });
});

// ── Store ─────────────────────────────────────────────────────────────────────

describe('DocumentController@store', function () {

    it('téléverse un document valide', function () {
        ['user' => $user, 'declaration' => $declaration] = gerantAvecDeclaration();

        $file = UploadedFile::fake()->create('rccm.pdf', 100, 'application/pdf');

        $this->actingAs($user)
            ->post(route('documents.store', $declaration), [
                'type' => 'RCCM',
                'file' => $file,
            ])
            ->assertSessionHas('success');

        expect(Document::where('declaration_id', $declaration->id)->where('type', 'RCCM')->count())->toBe(1);
        Storage::disk('public')->assertExists(Document::first()->file_path);
    });

    it('rejette les formats non autorisés', function () {
        ['user' => $user, 'declaration' => $declaration] = gerantAvecDeclaration();

        $file = UploadedFile::fake()->create('script.exe', 100, 'application/octet-stream');

        $this->actingAs($user)
            ->post(route('documents.store', $declaration), [
                'type' => 'RCCM',
                'file' => $file,
            ])
            ->assertSessionHasErrors('file');
    });

    it('empêche l\'ajout d\'un doublon de type', function () {
        ['user' => $user, 'declaration' => $declaration] = gerantAvecDeclaration();

        Document::factory()->create([
            'declaration_id' => $declaration->id,
            'type'           => 'RCCM',
        ]);

        $file = UploadedFile::fake()->create('rccm2.pdf', 100, 'application/pdf');

        $this->actingAs($user)
            ->post(route('documents.store', $declaration), [
                'type' => 'RCCM',
                'file' => $file,
            ])
            ->assertSessionHas('error');

        expect(Document::where('declaration_id', $declaration->id)->where('type', 'RCCM')->count())->toBe(1);
    });

    it('valide les champs requis', function () {
        ['user' => $user, 'declaration' => $declaration] = gerantAvecDeclaration();

        $this->actingAs($user)
            ->post(route('documents.store', $declaration), [])
            ->assertSessionHasErrors(['type', 'file']);
    });
});

// ── Destroy ───────────────────────────────────────────────────────────────────

describe('DocumentController@destroy', function () {

    it('supprime un document appartenant au gérant', function () {
        ['user' => $user, 'declaration' => $declaration] = gerantAvecDeclaration();

        $fakePath = 'documents/test.pdf';
        Storage::disk('public')->put($fakePath, 'contenu');

        $document = Document::factory()->create([
            'declaration_id' => $declaration->id,
            'type'           => 'CC',
            'file_path'      => $fakePath,
        ]);

        $this->actingAs($user)
            ->delete(route('documents.destroy', $document))
            ->assertSessionHas('success');

        expect(Document::find($document->id))->toBeNull();
        Storage::disk('public')->assertMissing($fakePath);
    });

    it('interdit la suppression d\'un document d\'un autre gérant', function () {
        ['user' => $user]                       = gerantAvecDeclaration();
        ['declaration' => $autreDeclaration]    = gerantAvecDeclaration();

        $document = Document::factory()->create([
            'declaration_id' => $autreDeclaration->id,
            'type'           => 'CC',
        ]);

        $this->actingAs($user)
            ->delete(route('documents.destroy', $document))
            ->assertForbidden();
    });
});
