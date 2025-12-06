<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckGeminiModels extends Command
{
    protected $signature = 'gemini:check';
    protected $description = 'API Anahtarı ile kullanılabilen Gemini modellerini listeler';

    public function handle()
    {
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            $this->error('HATA: .env dosyasında GEMINI_API_KEY bulunamadı!');
            return;
        }

        $this->info("API Anahtarı ile Google'a bağlanılıyor...");

        $response = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");

        if ($response->failed()) {
            $this->error('Bağlantı Başarısız! Hata Kodu: ' . $response->status());
            $this->error('Mesaj: ' . $response->body());
            return;
        }

        $models = $response->json('models');

        if (empty($models)) {
            $this->warn('Hiçbir model bulunamadı.');
            return;
        }

        $this->info('--- KULLANILABİLİR MODELLER ---');
        foreach ($models as $model) {
            $this->line(" - <fg=green>{$model['name']}</>");
            $this->line("   (Desteklenen Metodlar: " . implode(', ', $model['supportedGenerationMethods']) . ")");
            $this->newLine();
        }
    }
}