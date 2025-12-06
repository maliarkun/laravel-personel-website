<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    public function translate(string $text, string $targetLang = 'en'): string
    {
        // Try config first, fallback to env directly just in case
        $apiKey = config('services.gemini.key');
        if (empty($apiKey)) {
            $apiKey = env('GEMINI_API_KEY', '');
        }

        if (empty($apiKey)) {
            Log::warning('TranslationService: GEMINI_API_KEY is missing. Returning original text.');
            return $text;
        }

        if (empty($text)) {
            return '';
        }

        // Using specific version 'gemini-2.0-flash' as requested
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey;

        try {
            // Log the attempt
            Log::info('Gemini Request Initiated to: https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent');

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => "Translate the following Turkish text to English. Return ONLY the translation, no extra commentary: \n\n" . $text]
                                ]
                            ]
                        ]
                    ]);

            if ($response->successful()) {
                $translatedText = $response->json('candidates.0.content.parts.0.text');

                if ($translatedText) {
                    return trim($translatedText);
                }

                Log::warning('Gemini Response was successful but returned no text candidate.', ['response' => $response->json()]);
                return $text;
            }

            Log::error('Gemini Error: ' . $response->body());
            return $text;

        } catch (\Exception $e) {
            Log::error('Gemini Translation Exception: ' . $e->getMessage());
            return $text;
        }
    }
}
