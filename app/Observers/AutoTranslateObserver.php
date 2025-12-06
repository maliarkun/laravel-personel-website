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
        // Ensure the model acts as Translatable
        if (!property_exists($model, 'translatable')) {
            return;
        }

        foreach ($model->translatable as $field) {
            $translations = $model->getTranslations($field);

            // Logic: If TR exists but EN is missing, translate TR -> EN
            if (isset($translations['tr']) && !empty($translations['tr']) && !isset($translations['en'])) {
                try {
                    $translated = $this->translationService->translate($translations['tr'], 'en');

                    if (!empty($translated)) {
                        $model->setTranslation($field, 'en', $translated);
                    }
                } catch (\Exception $e) {
                    Log::error("AutoTranslateObserver Error on field {$field}: " . $e->getMessage());
                }
            }
        }
    }
}
