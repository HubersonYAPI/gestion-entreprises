<?php

use App\Models\Declaration;
use App\Models\Document;
use App\Models\Entreprise;
use App\Models\Gerant;
use App\Models\User;
use Database\Seeders\RoleSeeder;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ── Helpers ─────────────────────────────────────────────────────────────────

function gerantAvecEntreprise(): array
{
    $user = User::factory()->create();
    $user->assignRole('GERANT');

    $gerant    = Gerant::factory()->create(['user_id' => $user->id]);
    $entreprise = Entreprise::factory()->create(['gerant_id' => $gerant->id]);

    return compact('user', 'gerant', 'entreprise');
}

// ── Setup ────────────────────────────────────────────────────────────────────

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

// ── Dashboard ────────────────────────────────────────────────────────────────

describe('DeclarationController@dashboard', function () {

    it('affiche le dashboard au gérant connecté', function () {
        ['user' => $user] = gerantAvecEntreprise();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('dashboard');
    });

    it('redirige vers le profil si pas de gérant', function () {
        $user = User::factory()->create();
        $user->assignRole('GERANT');

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('gerant.edit'));
    });

    it('filtre les déclarations par statut', function () {
        ['user' => $user, 'entreprise' => $entreprise] = gerantAvecEntreprise();

        Declaration::factory()->soumis()->create(['entreprise_id' => $entreprise->id]);
        Declaration::factory()->create(['entreprise_id' => $entreprise->id]); // brouillon

        $this->actingAs($user)
            ->get(route('dashboard', ['statut' => 'soumis']))
            ->assertOk()
            ->assertViewHas('declarations', fn ($d) =>
                $d->every(fn ($decl) => $decl->statut === 'soumis')
            );
    });
});

// ── Store ─────────────────────────────────────────────────────────────────────

describe('DeclarationController@store', function () {

    it('crée une déclaration en brouillon', function () {
        ['user' => $user, 'entreprise' => $entreprise] = gerantAvecEntreprise();

        $this->actingAs($user)
            ->post(route('declarations.store'), [
                'entreprise_id'    => $entreprise->id,
                'nature_activite'  => 'Commerce général',
                'secteur_activite' => 'Commerce',
                'produits'         => 'Riz, huile',
                'effectifs'        => 5,
            ])
            ->assertRedirect(route('declarations.index'));

        expect(Declaration::where('entreprise_id', $entreprise->id)->count())->toBe(1);
        expect(Declaration::first()->statut)->toBe('brouillon');
        expect(Declaration::first()->phase)->toBe(1);
    });

    it('génère une référence unique', function () {
        ['user' => $user, 'entreprise' => $entreprise] = gerantAvecEntreprise();

        $payload = [
            'entreprise_id'    => $entreprise->id,
            'nature_activite'  => 'Commerce',
            'secteur_activite' => 'Commerce',
            'produits'         => 'Textiles',
            'effectifs'        => 10,
        ];

        $this->actingAs($user)->post(route('declarations.store'), $payload);
        $this->actingAs($user)->post(route('declarations.store'), $payload);

        $refs = Declaration::pluck('reference');
        expect($refs->unique()->count())->toBe(2);
    });

    it('valide les champs requis', function () {
        ['user' => $user] = gerantAvecEntreprise();

        $this->actingAs($user)
            ->post(route('declarations.store'), [])
            ->assertSessionHasErrors(['entreprise_id', 'nature_activite', 'secteur_activite', 'produits', 'effectifs']);
    });

    it('interdit de créer une déclaration pour une entreprise d\'un autre gérant', function () {
        ['user' => $user]                  = gerantAvecEntreprise();
        ['entreprise' => $autreEntreprise] = gerantAvecEntreprise();

        $this->actingAs($user)
            ->post(route('declarations.store'), [
                'entreprise_id'    => $autreEntreprise->id,
                'nature_activite'  => 'Commerce',
                'secteur_activite' => 'Commerce',
                'produits'         => 'Riz',
                'effectifs'        => 3,
            ])
            ->assertStatus(404); // findOrFail
    });
});

// ── Submit ────────────────────────────────────────────────────────────────────

describe('DeclarationController@submit', function () {

    it('soumet une déclaration avec tous les documents obligatoires', function () {
        ['user' => $user, 'entreprise' => $entreprise] = gerantAvecEntreprise();

        $declaration = Declaration::factory()->create(['entreprise_id' => $entreprise->id]);

        foreach (['RCCM', 'CC', 'produits', 'appareils', 'formulaire'] as $type) {
            Document::factory()->create([
                'declaration_id' => $declaration->id,
                'type'           => $type,
            ]);
        }

        $this->actingAs($user)
            ->post(route('declarations.submit', $declaration))
            ->assertRedirect();

        expect($declaration->fresh()->statut)->toBe('soumis');
        expect($declaration->fresh()->phase)->toBe(2);
        expect($declaration->fresh()->submitted_at)->not->toBeNull();
    });

    it('refuse la soumission si des documents sont manquants', function () {
        ['user' => $user, 'entreprise' => $entreprise] = gerantAvecEntreprise();

        $declaration = Declaration::factory()->create(['entreprise_id' => $entreprise->id]);

        Document::factory()->create([
            'declaration_id' => $declaration->id,
            'type'           => 'RCCM',
        ]);

        $this->actingAs($user)
            ->post(route('declarations.submit', $declaration))
            ->assertSessionHas('error');

        expect($declaration->fresh()->statut)->toBe('brouillon');
    });

    it('interdit la soumission d\'une déclaration d\'un autre gérant', function () {
        ['user' => $user]                      = gerantAvecEntreprise();
        ['entreprise' => $autreEntreprise]     = gerantAvecEntreprise();

        $declaration = Declaration::factory()->create(['entreprise_id' => $autreEntreprise->id]);

        $this->actingAs($user)
            ->post(route('declarations.submit', $declaration))
            ->assertForbidden();
    });
});

// ── Destroy ───────────────────────────────────────────────────────────────────

describe('DeclarationController@destroy', function () {

    it('supprime une déclaration appartenant au gérant', function () {
        ['user' => $user, 'entreprise' => $entreprise] = gerantAvecEntreprise();

        $declaration = Declaration::factory()->create(['entreprise_id' => $entreprise->id]);

        $this->actingAs($user)
            ->delete(route('declarations.destroy', $declaration))
            ->assertRedirect();

        expect(Declaration::find($declaration->id))->toBeNull();
    });

    it('interdit la suppression d\'une déclaration d\'un autre gérant', function () {
        ['user' => $user]                  = gerantAvecEntreprise();
        ['entreprise' => $autreEntreprise] = gerantAvecEntreprise();

        $declaration = Declaration::factory()->create(['entreprise_id' => $autreEntreprise->id]);

        $this->actingAs($user)
            ->delete(route('declarations.destroy', $declaration))
            ->assertForbidden();
    });
});
