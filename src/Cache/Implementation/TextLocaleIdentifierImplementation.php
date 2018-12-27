<?php

namespace App\Cache\Implementation;

use App\Cache\Cache\TextLocaleIdentifierCache;
use App\Doctrine\Entity\TextLocaleIdentifier;

class TextLocaleIdentifierImplementation
{
    /**
     * @var TextLocaleIdentifierCache $textLocaleIdentifierCache
     */
    private $textLocaleIdentifierCache;
    /**
     * TextLocaleIdentifierImplementation constructor.
     * @param TextLocaleIdentifierCache $textLocaleIdentifierCache
     */
    public function __construct(TextLocaleIdentifierCache $textLocaleIdentifierCache)
    {
        $this->textLocaleIdentifierCache = $textLocaleIdentifierCache;
    }
    /**
     * @param string $text
     * @param string $locale
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function isStored(
        string $text,
        string $locale
    ): bool {
        /** @var TextLocaleIdentifier $itemTranslationCache */
        $textLocaleIdentifier = $this->textLocaleIdentifierCache->get(
            $text,
            $locale
        );

        return $textLocaleIdentifier instanceof TextLocaleIdentifier;
    }
    /**
     * @param string $text
     * @param string $locale
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(
        string $text,
        string $locale
    ): void {
        $this->textLocaleIdentifierCache->set($text, $locale);
    }
    /**
     * @param string $text
     * @param string $locale
     * @return TextLocaleIdentifier
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getStored(string $text, string $locale): TextLocaleIdentifier
    {
        /** @var TextLocaleIdentifier $textLocaleIdentifier */
        $textLocaleIdentifier = $this->textLocaleIdentifierCache->get(
            $text,
            $locale
        );

        if (!$textLocaleIdentifier instanceof TextLocaleIdentifier) {
            $message = sprintf(
                'Invalid usage of %s::getStored(). This method should always return a %s. Check if a resource is stored with %s::isStored()',
                get_class($this),
                TextLocaleIdentifier::class,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return $textLocaleIdentifier;
    }
}