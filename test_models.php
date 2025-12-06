<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

$apiKey = Config::get('services.gemini.key');

if (empty($apiKey)) {
    echo "API Key not found in config.\n";
    exit(1);
}

echo "Using API Key: " . substr($apiKey, 0, 5) . "...\n";

$response = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");

if ($response->failed()) {
    echo "Failed to list models: " . $response->body() . "\n";
    exit(1);
}

$models = $response->json('models');
if (!$models) {
    echo "No models found in response.\n";
    print_r($response->json());
    exit(1);
}

echo "Available Models:\n";
foreach ($models as $model) {
    if (str_contains($model['name'], 'generateContent')) { // naive filter, actually checking supportedGenerationMethods is better
        echo " - " . $model['name'] . "\n";
    } else {
        echo " - " . $model['name'] . " (Supported methods: " . implode(', ', $model['supportedGenerationMethods'] ?? []) . ")\n";
    }
}
