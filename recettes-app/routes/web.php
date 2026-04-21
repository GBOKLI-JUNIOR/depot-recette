<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ImageAnalysisController;

Route::get('/', function () {
    return redirect()->route('recipes.index');
});

Route::resource('recipes', RecipeController::class);

Route::get('/search', [SearchController::class, 'index']);
Route::post('/search', [SearchController::class, 'search']);

Route::get('/analyze-image', [ImageAnalysisController::class, 'index']);
Route::post('/analyze-image', [ImageAnalysisController::class, 'analyzeFood']);
