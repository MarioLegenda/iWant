<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\KeywordTranslationCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class KeywordTranslationCacheRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KeywordTranslationCache::class);
    }
    /**
     * @param KeywordTranslationCache $keywordTranslationCache
     * @return KeywordTranslationCache
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(KeywordTranslationCache $keywordTranslationCache): KeywordTranslationCache
    {
        $this->getEntityManager()->persist($keywordTranslationCache);
        $this->getEntityManager()->flush();

        return $keywordTranslationCache;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
}