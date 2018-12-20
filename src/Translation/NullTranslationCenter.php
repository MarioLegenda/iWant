<?php

namespace App\Translation;

use App\Translation\Model\Language;
use App\Translation\Model\TranslatedEntryInterface;
use App\Translation\Model\Translation;

class NullTranslationCenter implements TranslationCenterInterface
{
    /**
     * @param Language $from
     * @param Language $to
     * @param string $text
     * @return Translation
     */
    public function translateFromTo(Language $from, Language $to, string $text): Translation
    {
        return new Translation($text);
    }
    /**
     * @param string $text
     * @return TranslatedEntryInterface
     */
    public function detectLanguage(string $text): TranslatedEntryInterface
    {
        return new Language('en');
    }
    /**
     * @param array $item
     * @param array $keysToTranslate
     * @return array
     */
    public function translateArray(array $item, array $keysToTranslate): array
    {
        return $item;
    }
    /**
     * @param string $value
     * @param string $locale
     * @return TranslatedEntryInterface
     */
    public function translate(string $value, string $locale): TranslatedEntryInterface
    {
        return new Translation($value);
    }
}