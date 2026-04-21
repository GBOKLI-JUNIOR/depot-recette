<?php

namespace App\Services;

class NutritionCalculator
{
    private NutritionService $nutritionService;

    // Table de conversion en grammes (approximative)
    private array $unitToGrams = [
        'g' => 1,
        'kg' => 1000,
        'ml' => 1, // approx 1g pour l'eau/lait
        'cl' => 10,
        'l' => 1000,
        'càs' => 15,
        'càc' => 5,
        'tasse' => 200,
        'u' => 100, // une unité moyenne (ex: 1 pomme = 100g)
        'unité' => 100,
    ];

    public function __construct(NutritionService $nutritionService)
    {
        $this->nutritionService = $nutritionService;
    }

    public function calculateForRecipe(\App\Models\Recipe $recipe): float
    {
        $totalCalories = 0;

        foreach ($recipe->ingredients as $ingredient) {
            $nutrition = $this->nutritionService->getCalories($ingredient->name);
            
            // Mise à jour des calories pour 100g dans l'ingrédient
            if ($nutrition['calories'] > 0) {
                $ingredient->update(['calories_per_100g' => $nutrition['calories']]);
            }

            $caloriesPer100g = $ingredient->calories_per_100g ?? $nutrition['calories'];
            
            $weightInGrams = $this->convertToGrams($ingredient->quantity, $ingredient->unit);
            
            $ingredientCalories = ($caloriesPer100g * $weightInGrams) / 100;
            $totalCalories += $ingredientCalories;
        }

        // Mise à jour du total de la recette
        $recipe->update(['total_calories' => $totalCalories]);

        return $totalCalories;
    }

    private function convertToGrams(float $quantity, string $unit): float
    {
        $unit = strtolower(trim($unit));
        $multiplier = $this->unitToGrams[$unit] ?? 1;
        return $quantity * $multiplier;
    }
}
