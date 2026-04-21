<?php

use App\Models\Declaration;
use App\Models\Entreprise;
use App\Models\Gerant;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RoleSeeder;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ── Helpers ───────────────────────────────────────────────────────────────────

function declarationApprouvee(Carbon $dateLimite): Declaration
{
    $user        = User::factory()->create();
    $gerant      = Gerant::factory()->create(['user_id' => $user->id]);
    $entreprise  = Entreprise::factory()->create(['gerant_id' => $gerant->id]);

    return Declaration::factory()->enAttentePaiement()->create([
        'entreprise_id'        => $entreprise->id,
        'date_limite_paiement' => $dateLimite,
    ]);
}

// ── Setup ─────────────────────────────────────────────────────────────────────

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

// ── Tests ─────────────────────────────────────────────────────────────────────

describe('Command: paiement:expire', function () {

    it('expire les déclarations dont la date limite est dépassée', function () {
        $expirée    = declarationApprouvee(Carbon::now()->subHour());
        $nonExpirée = declarationApprouvee(Carbon::now()->addHours(24));

        $this->artisan('paiement:expire')->assertSuccessful();

        expect($expirée->fresh()->statut)->toBe('rejete');
        expect($nonExpirée->fresh()->statut)->toBe('approuve');
    });

    it('n\'affecte pas les déclarations sans date limite', function () {
        $user        = User::factory()->create();
        $gerant      = Gerant::factory()->create(['user_id' => $user->id]);
        $entreprise  = Entreprise::factory()->create(['gerant_id' => $gerant->id]);

        $declaration = Declaration::factory()->valide()->create([
            'entreprise_id'        => $entreprise->id,
            'date_limite_paiement' => null,
        ]);

        $this->artisan('paiement:expire')->assertSuccessful();

        expect($declaration->fresh()->statut)->toBe('valide');
    });

    it('n\'affecte pas les déclarations qui ne sont pas en statut valide', function () {
        $user        = User::factory()->create();
        $gerant      = Gerant::factory()->create(['user_id' => $user->id]);
        $entreprise  = Entreprise::factory()->create(['gerant_id' => $gerant->id]);

        $declaration = Declaration::factory()->soumis()->create([
            'entreprise_id'        => $entreprise->id,
            'date_limite_paiement' => Carbon::now()->subHour(),
        ]);

        $this->artisan('paiement:expire')->assertSuccessful();

        // statut = 'soumis', non concerné par la commande (qui cible 'valide')
        expect($declaration->fresh()->statut)->toBe('soumis');
    });

    it('affiche un message de succès', function () {
        $this->artisan('paiement:expire')
            ->expectsOutput('Paiements expirés mis à jour')
            ->assertSuccessful();
    });

    it('expire plusieurs déclarations en une seule exécution', function () {
        $exp1 = declarationApprouvee(Carbon::now()->subMinutes(10));
        $exp2 = declarationApprouvee(Carbon::now()->subDays(2));
        $ok   = declarationApprouvee(Carbon::now()->addHours(10));

        $this->artisan('paiement:expire');

        expect($exp1->fresh()->statut)->toBe('rejete');
        expect($exp2->fresh()->statut)->toBe('rejete');
        expect($ok->fresh()->statut)->toBe('approuve');
    });
});
