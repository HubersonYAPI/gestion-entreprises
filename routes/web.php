<?php

use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GerantController;
use App\Http\Controllers\DeclarationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\TraitementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AttestationController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Auth;
use App\Models\Declaration;
use App\Models\Gerant;
use App\Models\Document;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DeclarationController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/gerant',          [GerantController::class,'show'])->name('gerant.show');
    Route::get('/profile-gerant',  [GerantController::class, 'edit'])->name('gerant.edit');
    Route::post('/profile-gerant', [GerantController::class, 'update'])->name('gerant.update');

    Route::resource('entreprises',  EntrepriseController::class);
    Route::resource('declarations', DeclarationController::class);

    //soumission declaration
    Route::post('/declarations/{declaration}/submit',    [DeclarationController::class, 'submit'])->name('declarations.submit');

    Route::get('/declarations/{declaration}/documents',  [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/declarations/{declaration}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{document}',               [DocumentController::class, 'destroy'])->name('documents.destroy');

    Route::get('/paiement/{declaration}',                [PaiementController::class, 'show'])->name('paiement.show');

    Route::post('/paiement/{declaration}',               [PaiementController::class, 'payer'])->name('paiement.payer');

    Route::get('/attestations', [AttestationController::class, 'index'])->name('attestations.index');


    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',               [NotificationController::class, 'index'])->name('index');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::get('/{id}/read',      [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::delete('/{id}',        [NotificationController::class, 'destroy'])->name('destroy');
    });

    Route::get('/notifications/poll', [NotificationController::class, 'poll'])
    ->name('notifications.poll');
});



/**
 * Routes Agent / Admin — UN SEUL groupe
 */
Route::prefix('agent')
    ->name('agent.')
    ->middleware(['auth', 'role:AGENT|CONTROLEUR|SUPER_ADMIN'])
    ->group(function () {
 
    // ── Dashboard ──────────────────────────────────────────────
    Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');
 
    // ── Documents ──────────────────────────────────────────────
    Route::post('/documents/{document}/valider', [AgentController::class, 'validerDocument'])->name('documents.valider');
    Route::post('/documents/{document}/rejeter', [AgentController::class, 'rejeterDocument'])->name('documents.rejeter');
 
    // ── Déclarations — statuts (routes FIXES en premier, avant {declaration}) ──
    Route::get('/declarations/toutes',           [AgentController::class, 'dashboard'])->name('declarations.toutes');
    Route::get('/declarations/soumis',           [AgentController::class, 'dashboard'])->name('declarations.soumis');
    Route::get('/declarations/approuve',         [AgentController::class, 'dashboard'])->name('declarations.approuver');
    Route::get('/declarations/paye',             [AgentController::class, 'dashboard'])->name('declarations.payer');
    Route::get('/declarations/en-traitement',    [AgentController::class, 'dashboard'])->name('declarations.en-traitement');
    Route::get('/declarations/valider-liste',    [AgentController::class, 'dashboard'])->name('declarations.valider');
    Route::get('/declarations/rejeter-liste',    [AgentController::class, 'dashboard'])->name('declarations.rejeter');
 
    // ── Déclarations — détail (route DYNAMIQUE après les fixes) ──
    Route::get('/declarations/{declaration}',           [AgentController::class, 'show'])->name('declarations.show');
    Route::get('/declarations/{declaration}/documents', [AgentController::class, 'documents'])->name('declaration.documents');
    Route::post('/declarations/{declaration}/valider',  [AgentController::class, 'valider'])->name('valider');
    Route::post('/declarations/{declaration}/rejeter',  [AgentController::class, 'rejeter'])->name('rejeter');
 
    // ── Entreprises ────────────────────────────────────────────
    Route::get('/entreprises', [AgentController::class, 'entreprises'])->name('entreprises');
    Route::get('/gerants',     [AgentController::class, 'gerants'])->name('gerants');
 
    // Attestations
    Route::get('/attestations',          [AttestationController::class, 'adminIndex'])->name('attestations');
 
    // ── Analyses ───────────────────────────────────────────────
    Route::get('/analyses/statistiques', [StatistiqueController::class, 'index'])->name('analyses.statistiques');
    Route::get('/analyses/rapports',     [RapportController::class,     'index'])->name('analyses.rapports');


    // ── Log ─────────────────────────────────────────
    Route::get('/admin/logs',         [AuditLogController::class, 'index'])->name('admin.logs');

    // ── Utilisateurs ───────────────────────────────────────────────────────────
    Route::get('/admin/utilisateurs',                   [UtilisateurController::class, 'index'])->name('admin.utilisateurs');
    Route::patch('/admin/utilisateurs/{user}/role',     [UtilisateurController::class, 'updateRole'])->name('admin.utilisateurs.role');
    Route::patch('/admin/utilisateurs/{user}/toggle',   [UtilisateurController::class, 'toggleActive'])->name('admin.utilisateurs.toggle');
    Route::delete('/admin/utilisateurs/{user}',         [UtilisateurController::class, 'destroy'])->name('admin.utilisateurs.destroy');

    // ── Rôles & Permissions ────────────────────────────────────────────────────
    Route::get('/admin/roles',                          [RoleController::class, 'index'])->name('admin.roles');
    Route::post('/admin/roles',                         [RoleController::class, 'store'])->name('admin.roles.store');
    Route::patch('/admin/roles/{role}/permissions',     [RoleController::class, 'updatePermissions'])->name('admin.roles.permissions');
    Route::delete('/admin/roles/{role}',                [RoleController::class, 'destroy'])->name('admin.roles.destroy');

    // Traitement + finaliser  declaration
    Route::post('/declarations/{declaration}/traiter',   [TraitementController::class, 'traiter'])->name('traiter');
    Route::post('/declarations/{declaration}/terminer',  [TraitementController::class, 'terminer'])->name('terminer');

    // Historique d'une déclaration (espace agent)
    Route::get('/declarations/{declaration}/historique', [AgentController::class, 'historique'])->name('declarations.historique');
    
});
 


require __DIR__.'/auth.php';