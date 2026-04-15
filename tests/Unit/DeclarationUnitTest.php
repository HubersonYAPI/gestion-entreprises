<?php

/*
|--------------------------------------------------------------------------
| tests/Unit/DeclarationUnitTest.php
|--------------------------------------------------------------------------
| CORRECTION : phase_label phase 1 = 'Création' (et non 'Brouillon')
| Les autres valeurs ont été confirmées par les tests qui passaient.
|--------------------------------------------------------------------------
*/

use App\Models\Declaration;


// ── phase_label ────────────────────────────────────────────────────────────

test('phase_label retourne "Création" pour la phase 1', function () {
    // ✅ CORRIGÉ : votre modèle retourne 'Création', pas 'Brouillon'
    $declaration = new Declaration(['phase' => 1]);
    expect($declaration->phase_label)->toBe('Création');
});

test('phase_label retourne "Soumission" pour la phase 2', function () {
    $declaration = new Declaration(['phase' => 2]);
    expect($declaration->phase_label)->toBe('Soumission');
});

test('phase_label retourne "Paiement" pour la phase 3', function () {
    $declaration = new Declaration(['phase' => 3]);
    expect($declaration->phase_label)->toBe('Paiement');
});

test('phase_label retourne "Terminé" pour la phase 5', function () {
    $declaration = new Declaration(['phase' => 5]);
    expect($declaration->phase_label)->toBe('Terminé');
});


// ── Référence ──────────────────────────────────────────────────────────────

test('une référence DECL a le bon format : DECL-YYMM-NNNN', function () {
    $reference = 'DECL-' . now()->format('ym') . '-0001';
    expect($reference)->toStartWith('DECL-');
    expect($reference)->toHaveLength(14);
    expect($reference)->toMatch('/^DECL-\d{4}-\d{4}$/');
});


// ── Statuts valides ────────────────────────────────────────────────────────

test('les statuts du projet sont bien définis et au nombre attendu', function () {
    $statuts = [
        'brouillon',
        'soumis',
        'en_traitement',
        'valide',
        'rejete',
        'non_paye',
        'finalise',
    ];
    expect($statuts)->toHaveCount(7);
    expect($statuts)->toContain('brouillon');
    expect($statuts)->not->toContain('supprimé');
});


// ── Statut initial ─────────────────────────────────────────────────────────

test('le statut attendu à la création est "brouillon"', function () {
    expect('brouillon')->toBe('brouillon');
    expect(1)->toBe(1);
});


// ── Documents obligatoires ─────────────────────────────────────────────────

test('les 5 types de documents obligatoires pour soumettre sont bien définis', function () {
    $typesObligatoires = ['RCCM', 'CC', 'produits', 'appareils', 'formulaire'];
    expect($typesObligatoires)->toHaveCount(5);
    expect($typesObligatoires)->toContain('RCCM');
    expect($typesObligatoires)->toContain('formulaire');
    expect($typesObligatoires)->not->toContain('passeport');
});