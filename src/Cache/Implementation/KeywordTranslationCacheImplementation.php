<?php

namespace App\Cache\Implementation;

use App\Cache\Cache\KeywordTranslationCache;
use App\Doctrine\Entity\KeywordTranslationCache as KeywordTranslationCacheEntity;

class KeywordTranslationCacheImplementation
{
    /**
     * @var KeywordTranslationCache $keywordTranslationCache
     */
    private $keywordTranslationCache;
    /**
     * KeywordTranslationCacheImplementation constructor.
     * @param KeywordTranslationCache $keywordTranslationCache
     */
    public function __construct(
        KeywordTranslationCache $keywordTranslationCache
    ) {
        $this->keywordTranslationCache = $keywordTranslationCache;
    }
    /**
     * @param string $original
     * @return bool
     */
    public function isStored(
        string $original
    ): bool {
        /** @var KeywordTranslationCache $keywordTranslationCache */
        $keywordTranslationCache = $this->keywordTranslationCache->get(
            $original
        );

        return $keywordTranslationCache instanceof KeywordTranslationCacheEntity;
    }
    /**
     * @param string $original
     * @param string $translation
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function upsert(string $original, string $translation): void
    {
        $keywordTranslationEntity = $this->keywordTranslationCache->getWithoutExpireTime($original);

        if ($keywordTranslationEntity instanceof KeywordTranslationCacheEntity) {
            $this->update($original, $translation);
        }

        $this->keywordTranslationCache->set($original, $translation);
    }
    /**
     * @param string $original
     * @param string $translation
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(
        string $original,
        string $translation
    ): bool {
        $this->keywordTranslationCache->set(
            $original,
            $translation
        );

        return true;
    }
    /**
     * @param string $original
     * @return KeywordTranslationCacheEntity
     */
    public function getStored(string $original): KeywordTranslationCacheEntity
    {
        /** @var KeywordTranslationCacheEntity $keywordTranslationCache */
        $keywordTranslationCache = $this->keywordTranslationCache->get(
            $original
        );

        if (!$keywordTranslationCache instanceof KeywordTranslationCacheEntity) {
            $message = sprintf(
                'Invalid usage of %s::getStored(). This method should always return a %s. Check if a resource is stored with %s::isStored()',
                get_class($this),
                KeywordTranslationCacheEntity::class,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return $keywordTranslationCache;
    }
    /**
     * @param string $original
     * @param string $translation
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(
        string $original,
        string $translation
    ) {
        $this->keywordTranslationCache->update(
            $original,
            $translation
        );
    }
}