<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Recipe;
use App\Services\NutritionCalculator;

$recipes = Recipe::with('ingredients')->get();

foreach ($recipes as $r) {
    $cal = app(NutritionCalculator::class)->calculateForRecipe($r);
    echo $r->title . ' => ' . round($cal) . ' kcal' . PHP_EOL;
}

echo PHP_EOL . 'Done! ' . count($recipes) . ' recettes recalculées.' . PHP_EOL;
