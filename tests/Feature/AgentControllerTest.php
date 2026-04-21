<?php

use App\Models\Declaration;
use App\Models\Document;
use App\Models\Entreprise;
use App\Models\Gerant;
use App\Models\User;
use Database\Seeders\RoleSeeder;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ── Helpers ───────────────────────────────────────────────────────────────────

function agentUser(): User
{
    $user = User::factory()->create();
    $user->assignRole('AGENT');
    return $user;
}

function declarationSoumise(): Declaration
{
    $gerant     = Gerant::factory()->create(['user_id' => User::factory()->create()->id]);
    $entreprise = Entreprise::factory()->create(['gerant_id' => $gerant->id]);

    return Declaration::factory()->soumis()->create(['entreprise_id' => $entreprise->id]);
}

// ── Setup ─────────────────────────────────────────────────────────────────────

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

// ── Dashboard ─────────────────────────────────────────────────────────────────

describe('AgentController@dashboard', function () {

    it('affiche le dashboard agent', function () {
        $agent = agentUser();

        $this->actingAs($agent)
            ->get(route('agent.dashboard'))
            ->assertOk()
            ->assertViewIs('agent.dashboard')
            ->assertViewHas('stats');
    });

    it('refuse l\'accès à un gérant', function () {
        $user = User::factory()->create();
        $user->assignRole('GERANT');

        $this->actingAs($user)
            ->get(route('agent.dashboard'))
            ->assertForbidden();
    });

    it('refuse l\'accès à un visiteur non connecté', function () {
        $this->get(route('agent.dashboard'))
            ->assertRedirect(route('login'));
    });

    it('ne retourne que les déclarations de phase > 1', function () {
        $agent = agentUser();

        // brouillon = phase 1, ne doit pas apparaître
        $gerant     = Gerant::factory()->create(['user_id' => User::factory()->create()->id]);
        $entreprise = Entreprise::factory()->create(['gerant_id' => $gerant->id]);
        Declaration::factory()->create(['entreprise_id' => $entreprise->id]); // brouillon

        declarationSoumise(); // phase 2

        $this->actingAs($agent)
            ->get(route('agent.dashboard'))
            ->assertViewHas('declarations', fn ($d) =>
                $d->every(fn ($decl) => $decl->phase > 1)
            );
    });
});

// ── Valider document ──────────────────────────────────────────────────────────

describe('AgentController@validerDocument', function () {

    it('valide un document', function () {
        $agent       = agentUser();
        $declaration = declarationSoumise();
        $document    = Document::factory()->create([
            'declaration_id' => $declaration->id,
            'type'           => 'RCCM',
            'statut'         => 'en_attente',
        ]);

        $this->actingAs($agent)
            ->post(route('agent.documents.valider', $document))
            ->assertRedirect();

        expect($document->fresh()->statut)->toBe('valide');
    });

    it('met à jour updated_at de la déclaration après validation', function () {
        $agent       = agentUser();
        $declaration = declarationSoumise();
        $document    = Document::factory()->create([
            'declaration_id' => $declaration->id,
            'type'           => 'CC',
            'statut'         => 'en_attente',
        ]);

        $avant = $declaration->updated_at;
        sleep(1);

        $this->actingAs($agent)
            ->post(route('agent.documents.valider', $document));

        expect($declaration->fresh()->updated_at->gt($avant))->toBeTrue();
    });
});

// ── Rejeter document ──────────────────────────────────────────────────────────

describe('AgentController@rejeterDocument', function () {

    it('rejette un document', function () {
        $agent       = agentUser();
        $declaration = declarationSoumise();
        $document    = Document::factory()->create([
            'declaration_id' => $declaration->id,
            'type'           => 'produits',
            'statut'         => 'en_attente',
        ]);

        $this->actingAs($agent)
            ->post(route('agent.documents.rejeter', $document))
            ->assertRedirect();

        expect($document->fresh()->statut)->toBe('rejete');
    });
});

// ── Valider déclaration ───────────────────────────────────────────────────────

describe('AgentController@valider', function () {

    it('approuve une déclaration dont tous les documents sont valides', function () {
        $agent       = agentUser();
        $declaration = declarationSoumise();

        foreach (['RCCM', 'CC', 'produits', 'appareils', 'formulaire'] as $type) {
            Document::factory()->valide()->create([
                'declaration_id' => $declaration->id,
                'type'           => $type,
            ]);
        }

        $this->actingAs($agent)
            ->post(route('agent.valider', $declaration))
            ->assertRedirect(route('agent.dashboard'));

        expect($declaration->fresh()->statut)->toBe('en_attente_paiement');
        expect($declaration->fresh()->phase)->toBe(3);
        expect($declaration->fresh()->date_limite_paiement)->not->toBeNull();
    });

    it('refuse la validation si un document n\'est pas validé', function () {
        $agent       = agentUser();
        $declaration = declarationSoumise();

        Document::factory()->create([
            'declaration_id' => $declaration->id,
            'type'           => 'RCCM',
            'statut'         => 'en_attente',
        ]);

        $this->actingAs($agent)
            ->post(route('agent.valider', $declaration))
            ->assertSessionHas('error');

        expect($declaration->fresh()->statut)->toBe('soumis');
    });
});

// ── Rejeter déclaration ───────────────────────────────────────────────────────

describe('AgentController@rejeter', function () {

    it('rejette une déclaration avec un commentaire', function () {
        $agent       = agentUser();
        $declaration = declarationSoumise();

        $this->actingAs($agent)
            ->post(route('agent.rejeter', $declaration), [
                'commentaire' => 'Documents non conformes.',
            ])
            ->assertRedirect(route('agent.dashboard'));

        expect($declaration->fresh()->statut)->toBe('rejete');
        expect($declaration->fresh()->commentaire)->toBe('Documents non conformes.');
    });

    it('refuse le rejet sans commentaire', function () {
        $agent       = agentUser();
        $declaration = declarationSoumise();

        $this->actingAs($agent)
            ->post(route('agent.rejeter', $declaration), [])
            ->assertSessionHasErrors('commentaire');
    });
});
