<?php

use App\Models\Declaration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);


// ─────────────────────────────────────────────
// TEST : expiration appliquée correctement
// ─────────────────────────────────────────────

test('la commande expire les paiements dont la date est dépassée', function () {

    // Déclaration expirée
    $expired = Declaration::factory()->create([
        'statut' => 'validé',
        'date_limite_paiement' => now()->subHours(2),
    ]);

    // Déclaration encore valide
    $valid = Declaration::factory()->create([
        'statut' => 'validé',
        'date_limite_paiement' => now()->addHours(2),
    ]);

    // Exécuter la commande
    Artisan::call('paiement:expire');

    // Vérifie que la première est expirée
    $this->assertDatabaseHas('declarations', [
        'id' => $expired->id,
        'statut' => 'expiré',
    ]);

    // Vérifie que l'autre n'a pas changé
    $this->assertDatabaseHas('declarations', [
        'id' => $valid->id,
        'statut' => 'validé',
    ]);
});


// ─────────────────────────────────────────────
// TEST : ignore si pas de date limite
// ─────────────────────────────────────────────

test('la commande ignore les declarations sans date limite', function () {

    $declaration = Declaration::factory()->create([
        'statut' => 'validé',
        'date_limite_paiement' => null,
    ]);

    Artisan::call('paiement:expire');

    $this->assertDatabaseHas('declarations', [
        'id' => $declaration->id,
        'statut' => 'validé',
    ]);
});


// ─────────────────────────────────────────────
// TEST : ignore les statuts déjà traités
// ─────────────────────────────────────────────

test('la commande ne modifie pas les declarations deja payees', function () {

    $declaration = Declaration::factory()->create([
        'statut' => 'payé',
        'date_limite_paiement' => now()->subHours(5),
    ]);

    Artisan::call('paiement:expire');

    $this->assertDatabaseHas('declarations', [
        'id' => $declaration->id,
        'statut' => 'payé',
    ]);
});


// ─────────────────────────────────────────────
// TEST : message console
// ─────────────────────────────────────────────

test('la commande affiche un message de succès', function () {

    Artisan::call('paiement:expire');

    $this->assertStringContainsString(
        'Paiements expirés mis à jour',
        Artisan::output()
    );
});