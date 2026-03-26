<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GerantController;
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

    Route::get('/profile-gerant', [GerantController::class, 'edit'])->name('gerant.edit');
    Route::post('/profile-gerant', [GerantController::class, 'update'])->name('gerant.update');
});

require __DIR__.'/auth.php';
