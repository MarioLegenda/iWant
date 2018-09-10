<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\TodaysProductsCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class TodaysProductsCacheRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodaysProductsCache::class);
    }
    /**
     * @param TodaysProductsCache $todayProductsCache
     * @return TodaysProductsCache
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(TodaysProductsCache $todayProductsCache): TodaysProductsCache
    {
        $this->getEntityManager()->persist($todayProductsCache);
        $this->getEntityManager()->flush();

        return $todayProductsCache;
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
}