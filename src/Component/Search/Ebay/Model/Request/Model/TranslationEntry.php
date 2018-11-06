<?php

namespace App\Component\Search\Ebay\Model\Request\Model;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class TranslationEntry implements ArrayNotationInterface
{
    /**
     * @var string $locale
     */
    private $locale;
    /**
     * @var string $translation
     */
    private $translation;
    /**
     * @var string $original
     */
    private $original;
    /**
     * TranslationEntry constructor.
     * @param string $locale
     * @param string $original
     * @param string $translation
     */
    public function __construct(
        string $locale,
        string $original,
        string $translation
    ) {
        $this->locale = $locale;
        $this->translation = $translation;
        $this->original = $original;
    }
    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
    /**
     * @param string $locale
     * @return bool
     */
    public function isLocale(string $locale): bool
    {
        return $this->locale === $locale;
    }
    /**
     * @return string
     */
    public function getOriginal(): string
    {
        return $this->original;
    }
    /**
     * @return string
     */
    public function getTranslation(): string
    {
        return $this->translation;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'locale' => $this->getLocale(),
            'original' => $this->getOriginal(),
            'translated' => $this->getTranslation(),
        ];
    }
}