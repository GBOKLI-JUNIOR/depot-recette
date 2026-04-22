<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ImageAnalysisController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('recipes.index');
});

// Authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes publiques
Route::get('recipes', [RecipeController::class, 'index'])->name('recipes.index');

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::resource('recipes', RecipeController::class)->except(['index', 'show']);

    Route::get('/search', [SearchController::class, 'index']);
    Route::post('/search', [SearchController::class, 'search']);

    Route::get('/analyze-image', [ImageAnalysisController::class, 'index']);
    Route::post('/analyze-image', [ImageAnalysisController::class, 'analyzeFood']);
});

// Route publique pour afficher une recette (doit être après le resource pour ne pas bloquer /recipes/create)
Route::get('recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
