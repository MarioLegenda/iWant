<?php

namespace App\Cache\Cache;

use App\Cache\Exception\CacheException;
use App\Doctrine\Repository\KeywordTranslationCacheRepository;
use App\Doctrine\Entity\KeywordTranslationCache as KeywordTranslationCacheEntity;
use App\Library\Representation\MainLocaleRepresentation;
use App\Library\Util\Util;

class KeywordTranslationCache
{
    /**
     * @var KeywordTranslationCacheRepository $keywordTranslationCacheRepository
     */
    private $keywordTranslationCacheRepository;
    /**
     * @var int $cacheTtl
     */
    private $cacheTtl;
    /**
     * @var MainLocaleRepresentation $mainLocaleRepresentation
     */
    private $mainLocaleRepresentation;
    /**
     * ItemTranslationCache constructor.
     * @param KeywordTranslationCacheRepository $keywordTranslationCacheRepository
     * @param MainLocaleRepresentation $mainLocaleRepresentation
     * @param int $cacheTtl
     */
    public function __construct(
        KeywordTranslationCacheRepository $keywordTranslationCacheRepository,
        MainLocaleRepresentation $mainLocaleRepresentation,
        int $cacheTtl
    ) {
        $this->keywordTranslationCacheRepository = $keywordTranslationCacheRepository;
        $this->cacheTtl = $cacheTtl;
        $this->mainLocaleRepresentation = $mainLocaleRepresentation;
    }
    /**
     * @param string $original
     * @return KeywordTranslationCacheEntity|null
     */
    public function get(
        string $original
    ): ?KeywordTranslationCacheEntity {
        /** @var KeywordTranslationCacheEntity $itemTranslationCache */
        $keywordTranslationEntity = $this->keywordTranslationCacheRepository->findOneBy([
            'original' => $original,
            'languageDirection' => (string) $this->mainLocaleRepresentation
        ]);

        if ($keywordTranslationEntity instanceof KeywordTranslationCacheEntity) {
            $expiresAt = $keywordTranslationEntity->getExpiresAt();

            $currentTimestamp = Util::toDateTime()->getTimestamp();

            $ttlTimestamp = $currentTimestamp - $expiresAt;

            if ($ttlTimestamp >= 0) {
                return null;
            }
        }

        return ($keywordTranslationEntity instanceof KeywordTranslationCacheEntity) ?
            $keywordTranslationEntity :
            null;
    }
    /**
     * @param string $text
     * @return KeywordTranslationCacheEntity|null
     */
    public function getByTranslation(string $text): ?KeywordTranslationCacheEntity
    {
        /** @var KeywordTranslationCacheEntity $itemTranslationCache */
        $keywordTranslationEntity = $this->keywordTranslationCacheRepository->findOneBy([
            'translation' => $text,
        ]);

        return ($keywordTranslationEntity instanceof KeywordTranslationCacheEntity) ?
            $keywordTranslationEntity :
            null;
    }
    /**
     * @param string $original
     * @return KeywordTranslationCacheEntity|null
     */
    public function getWithoutExpireTime(string $original): ?KeywordTranslationCacheEntity
    {
        /** @var KeywordTranslationCacheEntity $itemTranslationCache */
        $keywordTranslationEntity = $this->keywordTranslationCacheRepository->findOneBy([
            'original' => $original,
            'languageDirection' => (string) $this->mainLocaleRepresentation
        ]);

        return ($keywordTranslationEntity instanceof KeywordTranslationCacheEntity) ?
            $itemTranslationCache :
            null;
    }
    /**
     * @param string $original
     * @param string $translation
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function set(
        string $original,
        string $translation
    ) {
        $cache = $this->createKeywordTranslationEntity(
            $original,
            $translation,
            Util::toDateTime()->getTimestamp() + $this->cacheTtl
        );

        $this->keywordTranslationCacheRepository->persistAndFlush($cache);
    }
    /**
     * @param string $original
     * @param string $translation
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(
        string $original,
        string $translation
    ): void {
        $keywordTranslationEntity = $this->keywordTranslationCacheRepository->findOneBy([
            'original' => $original,
            'languageDirection' => (string) $this->mainLocaleRepresentation
        ]);

        if (!$keywordTranslationEntity instanceof KeywordTranslationCacheEntity) {
            $message = sprintf(
                '%s with original translation %s could not be found',
                KeywordTranslationCacheEntity::class,
                $original
            );

            throw new CacheException($message);
        }

        $keywordTranslationEntity->setOriginal($original);
        $keywordTranslationEntity->setTranslation($translation);
        $keywordTranslationEntity->setExpiresAt($this->cacheTtl);

        $this->keywordTranslationCacheRepository->getManager()->persist($keywordTranslationEntity);
        $this->keywordTranslationCacheRepository->getManager()->flush();
    }
    /**
     * @param string $original
     * @param string $translation
     * @param int $ttl
     * @return KeywordTranslationCacheEntity
     */
    public function createKeywordTranslationEntity(
        string $original,
        string $translation,
        int $ttl
    ): KeywordTranslationCacheEntity {
        return new KeywordTranslationCacheEntity(
            $original,
            $translation,
            (string) $this->mainLocaleRepresentation,
            $ttl
        );
    }
}