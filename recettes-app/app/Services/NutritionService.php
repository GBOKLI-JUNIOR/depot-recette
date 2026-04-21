<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NutritionService
{
    public function getCalories(string $ingredient): array
    {
        return Cache::remember('nutrition_' . md5($ingredient), 1800, function () use ($ingredient) {
            $appId = env('EDAMAM_APP_ID');
            $appKey = env('EDAMAM_APP_KEY');

            if (!$appId || !$appKey) {
                return $this->fallback($ingredient);
            }

            try {
                $response = Http::get('https://api.edamam.com/api/food-database/v2/parser', [
                    'ingr' => $ingredient,
                    'app_id' => $appId,
                    'app_key' => $appKey
                ]);

                if ($response->successful()) {
                    $nutrients = $response->json()['hints'][0]['food']['nutrients'] ?? null;
                    if ($nutrients) {
                        return [
                            'calories' => $nutrients['ENERC_KCAL'] ?? 0,
                            'protein' => $nutrients['PROCNT'] ?? 0,
                            'fat' => $nutrients['FAT'] ?? 0,
                            'carbs' => $nutrients['CHOCDF'] ?? 0,
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Silently fallback
            }

            return $this->fallback($ingredient);
        });
    }

    private function fallback(string $ingredient): array
    {
        try {
            $response = Http::get('https://world.openfoodfacts.org/cgi/search.pl', [
                'search_terms' => $ingredient,
                'json' => 'true'
            ]);

            if ($response->successful()) {
                $product = $response->json()['products'][0] ?? null;
                if ($product && isset($product['nutriments'])) {
                    return [
                        'calories' => $product['nutriments']['energy-kcal_100g'] ?? 0,
                        'protein' => $product['nutriments']['proteins_100g'] ?? 0,
                        'fat' => $product['nutriments']['fat_100g'] ?? 0,
                        'carbs' => $product['nutriments']['carbohydrates_100g'] ?? 0,
                    ];
                }
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return [
            'calories' => 0,
            'protein' => 0,
            'fat' => 0,
            'carbs' => 0,
        ];
    }
}
