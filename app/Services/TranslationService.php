<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', '');
    }

    public function translate(string $text, string $targetLang = 'en'): string
    {
        if (empty($text) || empty($this->apiKey)) {
            return $text;
        }

        try {
            // Simple prompt construction
            $prompt = "Translate the following Turkish text to English. Return ONLY the translated text, no explanations, no quotes, no markdown:\n\n" . $text;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}?key={$this->apiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.1,
                        ]
                    ]);

            if ($response->failed()) {
                Log::error('Gemini Translation Failed', ['response' => $response->body()]);
                return $text;
            }

            $data = $response->json();
            $translatedText = $data['candidates'][0]['content']['parts'][0]['text'] ?? $text;

            return trim($translatedText);

        } catch (\Exception $e) {
            Log::error('Gemini Translation Exception: ' . $e->getMessage());
            return $text;
        }
    }
}
