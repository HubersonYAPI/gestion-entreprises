<?php

use App\Models\Attestation;
use App\Models\Declaration;
use App\Models\Document;
use App\Models\Entreprise;
use App\Models\Gerant;
use App\Models\Paiement;
use App\Models\User;
use Database\Factories\DocumentFactory;
use Database\Seeders\RoleSeeder;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

// ── UserFactory ───────────────────────────────────────────────────────────────

describe('UserFactory', function () {

    it('crée un utilisateur valide', function () {
        $user = User::factory()->create();
        expect($user->name)->not->toBeEmpty();
        expect($user->email)->toContain('@');
        expect($user->email_verified_at)->not->toBeNull();
    });

    it('état unverified — email_verified_at est null', function () {
        $user = User::factory()->unverified()->create();
        expect($user->email_verified_at)->toBeNull();
    });
});

// ── GerantFactory ─────────────────────────────────────────────────────────────

describe('GerantFactory', function () {

    it('crée un gérant avec un user_id valide', function () {
        $gerant = Gerant::factory()->create();
        expect($gerant->user_id)->not->toBeNull();
        expect($gerant->nom)->not->toBeEmpty();
        expect($gerant->contact)->toStartWith('+225');
    });
});

// ── EntrepriseFactory ─────────────────────────────────────────────────────────

describe('EntrepriseFactory', function () {

    it('crée une entreprise avec un RCCM au format CI-ABJ-', function () {
        $entreprise = Entreprise::factory()->create();
        expect($entreprise->rccm)->toStartWith('CI-ABJ-');
        expect($entreprise->gerant_id)->not->toBeNull();
    });
});

// ── DeclarationFactory ────────────────────────────────────────────────────────

describe('DeclarationFactory', function () {

    it('crée une déclaration en brouillon par défaut', function () {
        $declaration = Declaration::factory()->create();
        expect($declaration->statut)->toBe('brouillon');
        expect($declaration->phase)->toBe(1);
    });

    it('état soumis — statut et phase corrects', function () {
        $d = Declaration::factory()->soumis()->create();
        expect($d->statut)->toBe('soumis');
        expect($d->phase)->toBe(2);
        expect($d->submitted_at)->not->toBeNull();
    });

    it('état enAttentePaiement — statut approuve et date limite', function () {
        $d = Declaration::factory()->enAttentePaiement()->create();
        expect($d->statut)->toBe('approuve');
        expect($d->phase)->toBe(3);
        expect($d->date_limite_paiement)->not->toBeNull();
    });

    it('état enTraitement — statut en_traitement', function () {
        $d = Declaration::factory()->enTraitement()->create();
        expect($d->statut)->toBe('en_traitement');
        expect($d->phase)->toBe(4);
        expect($d->paid_at)->not->toBeNull();
    });

    it('état valide — toutes les dates renseignées', function () {
        $d = Declaration::factory()->valide()->create();
        expect($d->statut)->toBe('valide');
        expect($d->phase)->toBe(5);
        expect($d->completed_at)->not->toBeNull();
    });

    it('état rejete — statut et commentaire', function () {
        $d = Declaration::factory()->rejete()->create();
        expect($d->statut)->toBe('rejete');
        expect($d->commentaire)->not->toBeNull();
    });

    it('état expire — délai dépassé', function () {
        $d = Declaration::factory()->expire()->create();
        expect($d->statut)->toBe('rejete');
        expect($d->date_limite_paiement)->not->toBeNull();
    });

    it('génère des références uniques', function () {
        $refs = Declaration::factory()->count(10)->create()->pluck('reference');
        expect($refs->unique()->count())->toBe(10);
    });
});

// ── DocumentFactory ───────────────────────────────────────────────────────────

describe('DocumentFactory', function () {

    it('crée un document en_attente par défaut', function () {
        $doc = Document::factory()->create();
        expect($doc->statut)->toBe('en_attente');
        expect($doc->file_path)->toStartWith('documents/');
    });

    it('état valide', function () {
        $doc = Document::factory()->valide()->create();
        expect($doc->statut)->toBe('valide');
    });

    it('état rejete', function () {
        $doc = Document::factory()->rejete()->create();
        expect($doc->statut)->toBe('rejete');
    });

    it('creerDossiersComplets crée les 5 types obligatoires tous validés', function () {
        $declaration = Declaration::factory()->create();
        DocumentFactory::creerDossiersComplets($declaration->id);

        $docs = Document::where('declaration_id', $declaration->id)->get();
        expect($docs->count())->toBe(5);
        expect($docs->every(fn ($d) => $d->statut === 'valide'))->toBeTrue();

        $types = $docs->pluck('type')->sort()->values()->toArray();
        expect($types)->toBe(collect(DocumentFactory::TYPES_OBLIGATOIRES)->sort()->values()->toArray());
    });
});

// ── PaiementFactory ───────────────────────────────────────────────────────────

describe('PaiementFactory', function () {

    it('crée un paiement payé par défaut', function () {
        $paiement = Paiement::factory()->create();
        expect($paiement->statut)->toBe('paye');
        expect($paiement->montant)->toBe(10000);
        expect($paiement->reference)->toStartWith('PAY-');
    });
});

// ── AttestationFactory ────────────────────────────────────────────────────────

describe('AttestationFactory', function () {

    it('crée une attestation avec une référence ATT-', function () {
        $attestation = Attestation::factory()->create();
        expect($attestation->reference)->toStartWith('ATT-');
        expect($attestation->file_path)->toStartWith('attestations/');
        expect($attestation->declaration_id)->not->toBeNull();
    });
});
