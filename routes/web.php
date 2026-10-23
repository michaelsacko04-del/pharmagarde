<?php

use App\Http\Controllers\RechercheController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RechercheController::class, 'index'])->name('recherche');
