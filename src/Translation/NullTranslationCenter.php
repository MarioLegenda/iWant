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
        $this->throwException();
    }
    /**
     * @param string $text
     * @return TranslatedEntryInterface
     */
    public function detectLanguage(string $text): TranslatedEntryInterface
    {
        $this->throwException();
    }
    /**
     * @param array $item
     * @param array $keysToTranslate
     * @return array
     */
    public function translateArray(array $item, array $keysToTranslate): array
    {
        $this->throwException();
    }
    /**
     * @param string $value
     * @param string $locale
     * @return TranslatedEntryInterface
     */
    public function translate(string $value, string $locale): TranslatedEntryInterface
    {
        $this->throwException();
    }
    /**
     * @throws \RuntimeException
     */
    private function throwException()
    {
        $message = sprintf(
            'NullTranslationCenter called. There has to be a valid translation center present'
        );

        throw new \RuntimeException($message);
    }
}