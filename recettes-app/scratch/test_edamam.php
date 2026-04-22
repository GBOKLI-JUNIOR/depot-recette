<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test API Edamam (recipes/v2) ===" . PHP_EOL;
echo "APP_ID: " . env('EDAMAM_APP_ID') . PHP_EOL;
echo "APP_KEY: " . substr(env('EDAMAM_APP_KEY'), 0, 10) . "..." . PHP_EOL;

$service = app(App\Services\NutritionService::class);

$tests = ['poulet', 'riz', 'tomate'];

foreach ($tests as $ingredient) {
    echo PHP_EOL . "--- $ingredient ---" . PHP_EOL;
    $result = $service->getCalories($ingredient);
    echo "Calories: " . $result['calories'] . " kcal/100g" . PHP_EOL;
    echo "Protéines: " . $result['protein'] . "g | Lipides: " . $result['fat'] . "g | Glucides: " . $result['carbs'] . "g" . PHP_EOL;
}

// Vérifier les logs pour les erreurs éventuelles
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lastLines = array_slice(file($logFile), -5);
    echo PHP_EOL . "=== Log récent ===" . PHP_EOL;
    foreach ($lastLines as $line) {
        if (str_contains($line, 'Edamam')) {
            echo trim($line) . PHP_EOL;
        }
    }
}
