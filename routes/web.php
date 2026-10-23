<?php

use App\Http\Controllers\ConnexionController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\TableauDeBordController;
use App\Http\Middleware\VerifieRole;
use Illuminate\Support\Facades\Route;

Route::get('/', [RechercheController::class, 'index'])->name('recherche');

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [ConnexionController::class, 'afficher'])->name('login');
    Route::post('/connexion', [ConnexionController::class, 'connecter'])
        ->middleware('throttle:5,1')
        ->name('connexion.envoyer');
});

Route::post('/deconnexion', [ConnexionController::class, 'deconnecter'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', VerifieRole::class.':pharmacien'])
    ->prefix('pharmacien')->name('pharmacien.')->group(function () {
        Route::get('/', [TableauDeBordController::class, 'pharmacien'])->name('tableau');
    });

Route::middleware(['auth', VerifieRole::class.':admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [TableauDeBordController::class, 'admin'])->name('tableau');
    });
