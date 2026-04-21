<?php

use App\Models\Attestation;
use App\Models\Declaration;
use App\Models\Entreprise;
use App\Models\Gerant;
use App\Models\User;
use Database\Seeders\RoleSeeder;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

// ── Helpers ───────────────────────────────────────────────────────────────────

function gerantAvecAttestation(): array
{
    $user        = User::factory()->create();
    $user->assignRole('GERANT');
    $gerant      = Gerant::factory()->create(['user_id' => $user->id]);
    $entreprise  = Entreprise::factory()->create(['gerant_id' => $gerant->id]);
    $declaration = Declaration::factory()->valide()->create(['entreprise_id' => $entreprise->id]);
    $attestation = Attestation::factory()->create(['declaration_id' => $declaration->id]);

    return compact('user', 'gerant', 'attestation');
}

// ── Index (gérant) ────────────────────────────────────────────────────────────

describe('AttestationController@index', function () {

    it('affiche les attestations du gérant connecté', function () {
        ['user' => $user, 'attestation' => $attestation] = gerantAvecAttestation();

        $this->actingAs($user)
            ->get(route('attestations.index'))
            ->assertOk()
            ->assertViewIs('attestations.index')
            ->assertViewHas('attestations');
    });

    it('n\'affiche pas les attestations d\'un autre gérant', function () {
        // Gérant connecté — sans attestation
        $user = User::factory()->create();
        $user->assignRole('GERANT');
        Gerant::factory()->create(['user_id' => $user->id]);

        // Autre gérant avec une attestation
        gerantAvecAttestation();

        $response = $this->actingAs($user)->get(route('attestations.index'));

        $response->assertViewHas('attestations', fn ($a) => $a->total() === 0);
    });

    it('redirige si le profil gérant est manquant', function () {
        $user = User::factory()->create();
        $user->assignRole('GERANT');

        $this->actingAs($user)
            ->get(route('attestations.index'))
            ->assertRedirect(route('gerant.edit'));
    });
});

// ── adminIndex ────────────────────────────────────────────────────────────────

describe('AttestationController@adminIndex', function () {

    it('affiche toutes les attestations pour un agent', function () {
        gerantAvecAttestation();
        gerantAvecAttestation();

        $agent = User::factory()->create();
        $agent->assignRole('AGENT');

        $this->actingAs($agent)
            ->get(route('agent.attestations'))
            ->assertOk()
            ->assertViewIs('agent.attestations')
            ->assertViewHas('total', fn ($t) => $t >= 2);
    });

    it('filtre les attestations par recherche', function () {
        ['attestation' => $attestation] = gerantAvecAttestation();
        gerantAvecAttestation(); // une autre sans lien avec la recherche

        $agent = User::factory()->create();
        $agent->assignRole('AGENT');

        $this->actingAs($agent)
            ->get(route('agent.attestations', ['search' => $attestation->reference]))
            ->assertOk()
            ->assertViewHas('attestations', fn ($a) =>
                $a->every(fn ($att) => str_contains($att->reference, $attestation->reference))
            );
    });

    it('refuse l\'accès à un gérant', function () {
        ['user' => $user] = gerantAvecAttestation();

        $this->actingAs($user)
            ->get(route('agent.attestations'))
            ->assertForbidden();
    });
});
