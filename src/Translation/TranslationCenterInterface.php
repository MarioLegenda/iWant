<?php

namespace App\Translation;

use App\Translation\Model\TranslatedEntryInterface;

interface TranslationCenterInterface
{
    /**
     * @param array $item
     * @param array $keysToTranslate
     * @return array
     */
    public function translateArray(array $item, array $keysToTranslate): array;
    /**
     * @param string $value
     * @param string $locale
     * @return TranslatedEntryInterface
     */
    public function translate(string $value, string $locale): TranslatedEntryInterface;
    /**
     * @param string $text
     * @return TranslatedEntryInterface
     */
    public function detectLanguage(string $text): TranslatedEntryInterface;
}