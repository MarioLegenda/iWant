<?php

namespace App\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Doctrine\Entity\ToggleCache;
use Doctrine\ORM\EntityManager;

class ToggleCacheRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToggleCache::class);
    }
    /**
     * @param ToggleCache $toggleCache
     * @return ToggleCache
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(ToggleCache $toggleCache): ToggleCache
    {
        $this->getEntityManager()->persist($toggleCache);
        $this->getEntityManager()->flush();

        return $toggleCache;
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
}