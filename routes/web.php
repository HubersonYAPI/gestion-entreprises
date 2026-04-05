<?php

use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GerantController;
use App\Http\Controllers\DeclarationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AgentController;
use App\Models\Declaration;
use App\Models\Gerant;
use App\Models\Document;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/gerant', [GerantController::class,'show'])->name('gerant.show');
    Route::get('/profile-gerant', [GerantController::class, 'edit'])->name('gerant.edit');
    Route::post('/profile-gerant', [GerantController::class, 'update'])->name('gerant.update');

    Route::resource('entreprises', EntrepriseController::class);
    Route::resource('declarations', DeclarationController::class);

    //soumission declaration
    Route::post('/declarations/{declaration}/submit', [DeclarationController::class, 'submit'])->name('declarations.submit');

    Route::get('/declarations/{declaration}/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/declarations/{declaration}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
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
    Route::get('/declarations/soumis',        [AgentController::class, 'dashboard'])->name('declarations.soumis');
    Route::get('/declarations/non-paye',       [AgentController::class, 'dashboard'])->name('declarations.non-paye');
    Route::get('/declarations/en-traitement',  [AgentController::class, 'dashboard'])->name('declarations.en-traitement');
    Route::get('/declarations/valider-liste',  [AgentController::class, 'dashboard'])->name('declarations.valider');
    Route::get('/declarations/rejeter-liste',  [AgentController::class, 'dashboard'])->name('declarations.rejeter');
 
    // ── Déclarations — détail (route DYNAMIQUE après les fixes) ──
    Route::get('/declarations/{declaration}',           [AgentController::class, 'show'])->name('declarations.show');
    Route::get('/declarations/{declaration}/documents', [AgentController::class, 'documents'])->name('declaration.documents');
    Route::post('/declarations/{declaration}/valider',  [AgentController::class, 'valider'])->name('valider');
    Route::post('/declarations/{declaration}/rejeter',  [AgentController::class, 'rejeter'])->name('rejeter');
 
    // ── Entreprises ────────────────────────────────────────────
    Route::get('/entreprises', [AgentController::class, 'dashboard'])->name('entreprises.index');
    Route::get('/gerants',     [AgentController::class, 'dashboard'])->name('gerants.index');
 
    // ── Attestations ───────────────────────────────────────────
    Route::get('/attestations',          [AgentController::class, 'dashboard'])->name('attestations.index');
    Route::get('/attestations/en-cours', [AgentController::class, 'dashboard'])->name('attestations.en-cours');
 
    // ── Analyses ───────────────────────────────────────────────
    Route::get('/analyses/statistiques', [AgentController::class, 'dashboard'])->name('analyses.statistiques');
    Route::get('/analyses/rapports',     [AgentController::class, 'dashboard'])->name('analyses.rapports');
 
    // ── Administration ─────────────────────────────────────────
    Route::get('/admin/utilisateurs', [AgentController::class, 'dashboard'])->name('admin.utilisateurs');
    Route::get('/admin/roles',        [AgentController::class, 'dashboard'])->name('admin.roles');
    Route::get('/admin/logs',         [AgentController::class, 'dashboard'])->name('admin.logs');
});
 


require __DIR__.'/auth.php';