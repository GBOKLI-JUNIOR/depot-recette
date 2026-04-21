<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeVisionService
{
    private string $apiKey;
    private string $model = 'claude-3-opus-20240229';

    public function __construct()
    {
        $this->apiKey = env('ANTHROPIC_API_KEY');
    }

    public function analyzeFood(string $imagePath): array
    {
        if (!$this->apiKey) {
            return ['error' => 'API Key missing'];
        }

        $fullPath = storage_path('app/public/' . $imagePath);
        if (!file_exists($fullPath)) {
            return ['error' => 'Image not found'];
        }

        $imageData = base64_encode(file_get_contents($fullPath));
        $mimeType = mime_content_type($fullPath);

        $prompt = 'Tu es un nutritionniste expert. Analyse l\'image et identifie le plat.\n'
                . 'Réponds UNIQUEMENT en JSON valide:\n'
                . '{"dish_name":"...","ingredients":[{"name":"...","estimated_grams":0}],'
                . '"total_calories":0,"confidence_score":0.0}';

        $response = Http::timeout(30)->withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => $this->model,
            'max_tokens' => 1000,
            'messages' => [[
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'image',
                        'source' => [
                            'type' => 'base64',
                            'media_type' => $mimeType,
                            'data' => $imageData
                        ]
                    ],
                    [
                        'type' => 'text',
                        'text' => $prompt
                    ],
                ]
            ]]
        ]);

        if ($response->failed()) {
            return ['error' => 'API Request failed: ' . $response->body()];
        }

        $content = $response->json()['content'][0]['text'] ?? '{}';
        
        // Remove markdown JSON codeblocks if Claude added them
        $content = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $content);

        return json_decode($content, true) ?? [];
    }
}
