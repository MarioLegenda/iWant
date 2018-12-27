<?php

namespace App\Translation;

use App\Translation\Model\Language;
use App\Translation\Model\TranslatedEntryInterface;
use App\Translation\Model\Translation;

class TranslationCenter implements TranslationCenterInterface
{
    /**
     * @var TranslationCenterInterface $translationCenter
     */
    private $translationCenter;
    /**
     * TranslationCenter constructor.
     * @param TranslationCenterInterface $translationCenter
     */
    public function __construct(
        TranslationCenterInterface $translationCenter
    ) {
        $this->translationCenter = $translationCenter;
    }
    /**
     * @param string $value
     * @param string $locale
     * @param string|null $entryId
     * @param string|null $identifier
     * @return TranslatedEntryInterface
     */
    public function translate(
        string $value,
        string $locale,
        string $entryId = null,
        string $identifier = null
    ): TranslatedEntryInterface {
        return $this->translationCenter->translate($value, $locale);
    }
    /**
     * @param array $item
     * @param array $keysToTranslate
     * @param string|null $locale
     * @param string|null $identifier
     * @return array
     */
    public function translateArray(
        array $item,
        array $keysToTranslate,
        string $locale = null,
        string $identifier = null
    ): array {
        return $this->translationCenter->translateArray(
            $item,
            $keysToTranslate,
            $locale,
            $identifier
        );
    }
    /**
     * @param string $text
     * @param string|null $possibleLocale
     * @return TranslatedEntryInterface
     */
    public function detectLanguage(string $text, string $possibleLocale = null): TranslatedEntryInterface
    {
        return $this->translationCenter->detectLanguage($text, $possibleLocale);
    }
    /**
     * @param Language $from
     * @param Language $to
     * @param string $text
     * @return Translation
     */
    public function translateFromTo(Language $from, Language $to, string $text): Translation
    {
        return $this->translationCenter->translateFromTo($from, $to, $text);
    }
}