<?php

namespace App\Library\Representation;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class LanguageTranslationsRepresentation implements ArrayNotationInterface
{
    /**
     * @var array $translationLanguages
     */
    private $translationLanguages;
    /**
     * LanguageTranslationsRepresentation constructor.
     * @param $yandexTranslationLanguages
     */
    public function __construct(
        $yandexTranslationLanguages
    ) {
        $this->translationLanguages = $yandexTranslationLanguages;
    }

    public function isMappedByGlobalId(string $globalId): bool
    {
        return array_key_exists($globalId, $this->translationLanguages);
    }
    /**
     * @param string $globalId
     * @return string
     */
    public function getTranslationLanguageByGlobalId(string $globalId): string
    {
        if (!$this->isMappedByGlobalId($globalId)) {
            $message = sprintf(
                'Global id does not exist and is not mapped'
            );

            throw new \RuntimeException($message);
        }

        return $this->translationLanguages[$globalId];
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return $this->translationLanguages;
    }
}