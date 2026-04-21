<?php

use App\Models\Attestation;
use App\Models\Declaration;
use App\Models\Entreprise;
use App\Models\Gerant;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ── Helpers ───────────────────────────────────────────────────────────────────

function agentConnecte(): User
{
    $agent = User::factory()->create();
    $agent->assignRole('AGENT');
    return $agent;
}

function declarationEnTraitement(): Declaration
{
    $user       = User::factory()->create();
    $gerant     = Gerant::factory()->create(['user_id' => $user->id]);
    $entreprise = Entreprise::factory()->create(['gerant_id' => $gerant->id]);

    return Declaration::factory()->enTraitement()->create(['entreprise_id' => $entreprise->id]);
}

// ── Setup ─────────────────────────────────────────────────────────────────────

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    Storage::fake('public');
});

// ── Traiter ───────────────────────────────────────────────────────────────────

describe('TraitementController@traiter', function () {

    it('passe la déclaration en en_traitement', function () {
        $agent       = agentConnecte();
        $declaration = declarationEnTraitement();

        $this->actingAs($agent)
            ->post(route('agent.traiter', $declaration))
            ->assertRedirect();

        expect($declaration->fresh()->statut)->toBe('en_traitement');
        expect($declaration->fresh()->processed_at)->not->toBeNull();
    });
});

// ── Terminer ──────────────────────────────────────────────────────────────────

describe('TraitementController@terminer', function () {

    it('génère une attestation et valide la déclaration', function () {
        $agent       = agentConnecte();
        $declaration = declarationEnTraitement();

        $this->actingAs($agent)
            ->post(route('agent.terminer', $declaration))
            ->assertRedirect();

        $attestation = Attestation::where('declaration_id', $declaration->id)->first();
        expect($attestation)->not->toBeNull();
        expect($attestation->reference)->toStartWith('ATT-');
        expect($attestation->file_path)->toContain('attestations/');

        expect($declaration->fresh()->statut)->toBe('valide');
        expect($declaration->fresh()->phase)->toBe(5);
        expect($declaration->fresh()->completed_at)->not->toBeNull();
    });

    it('stocke le PDF dans le disque public', function () {
        $agent       = agentConnecte();
        $declaration = declarationEnTraitement();

        $this->actingAs($agent)
            ->post(route('agent.terminer', $declaration));

        $filePath = 'attestations/attestation_' . $declaration->id . '.pdf';
        Storage::disk('public')->assertExists($filePath);
    });

    it('empêche la génération d\'une deuxième attestation', function () {
        $agent       = agentConnecte();
        $declaration = declarationEnTraitement();

        Attestation::factory()->create(['declaration_id' => $declaration->id]);

        $this->actingAs($agent)
            ->post(route('agent.terminer', $declaration))
            ->assertSessionHas('error');

        expect(Attestation::where('declaration_id', $declaration->id)->count())->toBe(1);
    });
});
