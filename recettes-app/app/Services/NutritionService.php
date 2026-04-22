<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NutritionService
{
    private string $edamamHost = 'api.edamam.com';

    public function getCalories(string $ingredient): array
    {
        return Cache::remember('nutrition_' . md5($ingredient), 3600, function () use ($ingredient) {
            $appId = env('EDAMAM_APP_ID');
            $appKey = env('EDAMAM_APP_KEY');

            if (!$appId || !$appKey) {
                Log::warning('Clés Edamam manquantes. Configurez EDAMAM_APP_ID et EDAMAM_APP_KEY dans .env');
                return $this->emptyNutrition();
            }

            // Résolution DNS manuelle pour contourner le bug cURL/WAMP
            $curlOpts = $this->buildCurlOptions($this->edamamHost);

            try {
                $response = Http::withoutVerifying()
                    ->timeout(15)
                    ->withOptions(['curl' => $curlOpts])
                    ->withHeaders([
                        'Edamam-Account-User' => $appId,
                    ])
                    ->get("https://{$this->edamamHost}/api/recipes/v2", [
                        'type' => 'public',
                        'q' => $ingredient,
                        'app_id' => $appId,
                        'app_key' => $appKey,
                    ]);

                if ($response->successful()) {
                    $hits = $response->json()['hits'] ?? [];

                    if (!empty($hits)) {
                        $recipe = $hits[0]['recipe'] ?? null;

                        if ($recipe) {
                            $totalWeight = $recipe['totalWeight'] ?? 1;
                            $servings = $recipe['yield'] ?? 1;

                            // Extraire les nutriments totaux de la recette
                            $totalNutrients = $recipe['totalNutrients'] ?? [];

                            // Calculer les valeurs nutritionnelles pour 100g
                            $calories = ($totalNutrients['ENERC_KCAL']['quantity'] ?? 0);
                            $protein = ($totalNutrients['PROCNT']['quantity'] ?? 0);
                            $fat = ($totalNutrients['FAT']['quantity'] ?? 0);
                            $carbs = ($totalNutrients['CHOCDF']['quantity'] ?? 0);

                            // Convertir en valeur par 100g
                            if ($totalWeight > 0) {
                                $factor = 100 / $totalWeight;
                                return [
                                    'calories' => round($calories * $factor, 1),
                                    'protein' => round($protein * $factor, 1),
                                    'fat' => round($fat * $factor, 1),
                                    'carbs' => round($carbs * $factor, 1),
                                ];
                            }

                            // Si pas de poids total, retourner par portion
                            if ($servings > 0) {
                                return [
                                    'calories' => round($calories / $servings, 1),
                                    'protein' => round($protein / $servings, 1),
                                    'fat' => round($fat / $servings, 1),
                                    'carbs' => round($carbs / $servings, 1),
                                ];
                            }
                        }
                    }

                    Log::info("Edamam: aucun résultat pour '$ingredient'");
                    return $this->emptyNutrition();
                }

                // Gérer les erreurs HTTP spécifiques
                $status = $response->status();
                if ($status === 401 || $status === 403) {
                    Log::error("Edamam API: Clé API invalide (HTTP $status). Vérifiez vos identifiants sur https://developer.edamam.com");
                } elseif ($status === 429) {
                    Log::warning('Edamam API: Limite de requêtes atteinte. Réessayez plus tard.');
                } else {
                    Log::error("Edamam API erreur HTTP $status: " . $response->body());
                }

                return $this->emptyNutrition();

            } catch (\Exception $e) {
                Log::error('Edamam API erreur de connexion: ' . $e->getMessage());
                return $this->emptyNutrition();
            }
        });
    }

    /**
     * Construit les options cURL avec résolution DNS manuelle
     * pour contourner le bug cURL sur WAMP/Windows
     */
    private function buildCurlOptions(string $host): array
    {
        $opts = [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4];

        $ip = gethostbyname($host);
        if ($ip !== $host) {
            $opts[CURLOPT_RESOLVE] = ["$host:443:$ip"];
        }

        return $opts;
    }

    /**
     * Retourne un tableau vide de nutrition
     */
    private function emptyNutrition(): array
    {
        return [
            'calories' => 0,
            'protein' => 0,
            'fat' => 0,
            'carbs' => 0,
        ];
    }
}
