<?php

namespace App\Translation;

use App\Translation\Model\Language;
use App\Translation\Model\TranslatedEntryInterface;
use App\Translation\Model\Translation;

class GoogleTranslationCenter implements TranslationCenterInterface
{
    public function detectLanguage(string $text): TranslatedEntryInterface
    {
    }

    public function translateArray(array $item, array $keysToTranslate): array
    {
    }

    public function translate(string $value, string $locale): TranslatedEntryInterface
    {
    }

    public function translateFromTo(Language $from, Language $to, string $text): Translation
    {
    }
}