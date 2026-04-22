<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceVisionService
{
    private string $apiKey;
    private string $model = 'meta-llama/Llama-3.2-11B-Vision-Instruct';

    public function __construct()
    {
        $this->apiKey = env('HUGGINGFACE_API_KEY');
    }

    public function analyzeFood(string $imagePath): array
    {
        if (!$this->apiKey) {
            return ['error' => 'La clé API Hugging Face est manquante. Veuillez la configurer dans le fichier .env (HUGGINGFACE_API_KEY).'];
        }

        $fullPath = storage_path('app/public/' . $imagePath);
        if (!file_exists($fullPath)) {
            return ['error' => 'Image introuvable'];
        }

        try {
            $imageData = file_get_contents($fullPath);
            $host = 'api-inference.huggingface.co';
            $ip = gethostbyname($host);
            
            // Si la résolution DNS échoue, on tente une IP connue de CloudFront (Hugging Face)
            if ($ip === $host) {
                $ip = '108.139.200.3'; 
            }

            // Étape 1 : Identifier le plat
            $modelId = 'google/vit-base-patch16-224'; 
            
            $imageResponse = Http::withoutVerifying()
                ->timeout(30)
                ->withOptions([
                    'curl' => [
                        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                        CURLOPT_RESOLVE => ["$host:443:$ip"]
                    ]
                ])
                ->withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->post("https://$host/models/" . $modelId, [
                    'inputs' => base64_encode($imageData)
                ]);

            if ($imageResponse->failed()) {
                // Tentative de repli
                $modelId = 'Kaludi/food-category-classification-v2.0';
                $imageResponse = Http::withoutVerifying()
                    ->timeout(30)
                    ->withOptions([
                        'curl' => [
                            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                            CURLOPT_RESOLVE => ["$host:443:$ip"]
                        ]
                    ])
                    ->withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                    ->post("https://$host/models/" . $modelId, [
                        'inputs' => base64_encode($imageData)
                    ]);
            }

            if ($imageResponse->failed()) {
                $errorMsg = $imageResponse->json()['error'] ?? $imageResponse->body();
                return ['error' => "L'IA n'a pas pu identifier l'image. Détails : " . $errorMsg];
            }

            $predictions = $imageResponse->json();
            
            if (empty($predictions) || isset($predictions['error'])) {
                return ['error' => $predictions['error'] ?? 'Modèle en cours de chargement... Réessayez dans un instant.'];
            }

            $bestPrediction = $predictions[0];
            $dishName = ucfirst(str_replace('_', ' ', $bestPrediction['label']));
            $confidence = $bestPrediction['score'];

            // Étape 2 : Générer les détails (Zephyr)
            $textModel = 'HuggingFaceH4/zephyr-7b-beta';
            $prompt = "<|system|>\nTu es un expert en nutrition. Réponds UNIQUEMENT avec un objet JSON valide.\n"
                    . "<|user|>\nDonne-moi les ingrédients et calories pour : \"$dishName\".\n"
                    . 'Format : {"dish_name":"' . $dishName . '","ingredients":[{"name":"ingrédient","estimated_grams":100}],"total_calories":500,"confidence_score":' . $confidence . '}'
                    . "\n<|assistant|>\n{";

            $textResponse = Http::withoutVerifying()
                ->timeout(30)
                ->withOptions([
                    'curl' => [
                        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                        CURLOPT_RESOLVE => ["$host:443:$ip"]
                    ]
                ])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])->post("https://$host/models/" . $textModel, [
                    'inputs' => $prompt,
                    'parameters' => [
                        'max_new_tokens' => 250,
                        'temperature' => 0.1,
                        'return_full_text' => false
                    ]
                ]);

            if ($textResponse->failed()) {
                return [
                    'dish_name' => $dishName,
                    'ingredients' => [['name' => 'Analyse partielle', 'estimated_grams' => 0]],
                    'total_calories' => 0,
                    'confidence_score' => $confidence
                ];
            }

            $generatedText = $textResponse->json()[0]['generated_text'] ?? '';
            $jsonString = "{" . $generatedText;
            
            preg_match('/\{.*\}/s', $jsonString, $matches);
            $cleanJson = $matches[0] ?? '{}';
            $result = json_decode($cleanJson, true);

            return $result ?: [
                'dish_name' => $dishName,
                'ingredients' => [['name' => 'Données indisponibles', 'estimated_grams' => 0]],
                'total_calories' => 0,
                'confidence_score' => $confidence
            ];

        } catch (\Exception $e) {
            Log::error('Analyse IA erreur : ' . $e->getMessage());
            return ['error' => "Problème de connexion aux serveurs d'IA. Vérifiez votre connexion internet ou réessayez plus tard. (Erreur: " . $e->getMessage() . ")"];
        }
    }
}
