<?php

use App\Models\Declaration;
use App\Models\Entreprise;
use App\Models\Gerant;
use App\Models\Paiement;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RoleSeeder;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ── Helpers ───────────────────────────────────────────────────────────────────

function gerantEtDeclarationApprouvee(): array
{
    $user       = User::factory()->create();
    $user->assignRole('GERANT');
    $gerant     = Gerant::factory()->create(['user_id' => $user->id]);
    $entreprise = Entreprise::factory()->create(['gerant_id' => $gerant->id]);

    $declaration = Declaration::factory()->enAttentePaiement()->create([
        'entreprise_id'        => $entreprise->id,
        'date_limite_paiement' => Carbon::now()->addHours(48),
    ]);

    return compact('user', 'declaration');
}

// ── Setup ─────────────────────────────────────────────────────────────────────

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

// ── Show ──────────────────────────────────────────────────────────────────────

describe('PaiementController@show', function () {

    it('affiche la page de paiement', function () {
        ['user' => $user, 'declaration' => $declaration] = gerantEtDeclarationApprouvee();

        $this->actingAs($user)
            ->get(route('paiement.show', $declaration))
            ->assertOk()
            ->assertViewIs('paiements.show');
    });

    it('interdit l\'accès à un autre gérant', function () {
        ['declaration' => $declaration]    = gerantEtDeclarationApprouvee();
        ['user' => $autreUser]             = gerantEtDeclarationApprouvee();

        $this->actingAs($autreUser)
            ->get(route('paiement.show', $declaration))
            ->assertForbidden();
    });
});

// ── Payer ─────────────────────────────────────────────────────────────────────

describe('PaiementController@payer', function () {

    it('enregistre le paiement et passe la déclaration en traitement', function () {
        ['user' => $user, 'declaration' => $declaration] = gerantEtDeclarationApprouvee();

        $this->actingAs($user)
            ->post(route('paiement.payer', $declaration))
            ->assertRedirect(route('declarations.index'));

        $paiement = Paiement::where('declaration_id', $declaration->id)->first();
        expect($paiement)->not->toBeNull();
        expect($paiement->statut)->toBe('paye');
        expect($paiement->montant)->toBe(10000);
        expect($paiement->reference)->toStartWith('PAY-');

        expect($declaration->fresh()->statut)->toBe('paye');
        expect($declaration->fresh()->phase)->toBe(4);
        expect($declaration->fresh()->paid_at)->not->toBeNull();
    });

    it('empêche un double paiement', function () {
        ['user' => $user, 'declaration' => $declaration] = gerantEtDeclarationApprouvee();

        Paiement::factory()->create(['declaration_id' => $declaration->id]);

        $this->actingAs($user)
            ->post(route('paiement.payer', $declaration))
            ->assertSessionHas('error');

        expect(Paiement::where('declaration_id', $declaration->id)->count())->toBe(1);
    });

    it('expire la déclaration si la date limite est dépassée', function () {
        ['user' => $user, 'declaration' => $declaration] = gerantEtDeclarationApprouvee();

        $declaration->update(['date_limite_paiement' => Carbon::now()->subHour()]);

        $this->actingAs($user)
            ->post(route('paiement.payer', $declaration))
            ->assertRedirect(route('declarations.index'))
            ->assertSessionHas('error');

        expect($declaration->fresh()->statut)->toBe('rejete');
    });

    it('interdit le paiement d\'une déclaration d\'un autre gérant', function () {
        ['declaration' => $declaration] = gerantEtDeclarationApprouvee();
        ['user' => $autreUser]          = gerantEtDeclarationApprouvee();

        $this->actingAs($autreUser)
            ->post(route('paiement.payer', $declaration))
            ->assertForbidden();
    });
});
