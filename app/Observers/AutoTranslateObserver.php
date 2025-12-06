<?php

namespace App\Observers;

use App\Services\TranslationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AutoTranslateObserver
{
    protected TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Handle the Model "saving" event.
     */
    public function saving(Model $model): void
    {
        if (!property_exists($model, 'translatable')) {
            Log::info('AutoTranslateObserver: Model not translatable', ['model' => get_class($model)]);
            return;
        }

        foreach ($model->translatable as $field) {
            $translations = $model->getTranslations($field);

            // Check if TR exists and EN is missing
            if (isset($translations['tr']) && !empty($translations['tr']) && !isset($translations['en'])) {
                Log::info("AutoTranslateObserver: Translating field '{$field}' from TR to EN", ['id' => $model->id]);

                try {
                    $translated = $this->translationService->translate($translations['tr'], 'en');

                    if (!empty($translated) && $translated !== $translations['tr']) {
                        $model->setTranslation($field, 'en', $translated);
                        Log::info("AutoTranslateObserver: Translated '{$field}' successfully.");
                    } else {
                        Log::warning("AutoTranslateObserver: Translation returned empty or identical text.");
                    }
                } catch (\Exception $e) {
                    Log::error("AutoTranslateObserver Error on field {$field}: " . $e->getMessage());
                }
            }
        }
    }
}
