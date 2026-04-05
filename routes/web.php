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

Route::prefix('agent')->middleware(['auth', 'role:AGENT|CONTROLEUR|SUPER_ADMIN'])->group(function(){
    Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('agent.dashboard');

    // Documents
    Route::post('/documents/{document}/valider', [AgentController::class, 'validerDocument'])
        ->name('documents.valider');

    Route::post('/documents/{document}/rejeter', [AgentController::class, 'rejeterDocument'])
        ->name('documents.rejeter');

    // Déclaration
    Route::get('/declarations/{declaration}', [AgentController::class, 'show'])
        ->name('agent.declarations.show');

    Route::get('/declarations/{declaration}/documents', [AgentController::class, 'documents'])
        ->name('agent.declaration.documents');

    Route::post('declarations/{declaration}/valider', [AgentController::class, 'valider'])
        ->name('agent.valider');

    Route::post('declarations/{declaration}/rejeter', [AgentController::class, 'rejeter'])
        ->name('agent.rejeter');
});

require __DIR__.'/auth.php';