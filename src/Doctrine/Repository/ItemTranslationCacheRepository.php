<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\ItemTranslationCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ItemTranslationCacheRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemTranslationCache::class);
    }
    /**
     * @param ItemTranslationCache $itemTranslationCache
     * @return ItemTranslationCache
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(ItemTranslationCache $itemTranslationCache): ItemTranslationCache
    {
        $this->getEntityManager()->persist($itemTranslationCache);
        $this->getEntityManager()->flush();

        return $itemTranslationCache;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
}