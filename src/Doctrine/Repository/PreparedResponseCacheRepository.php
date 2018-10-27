<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\PreparedResponseCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class PreparedResponseCacheRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PreparedResponseCache::class);
    }
    /**
     * @param PreparedResponseCache $preparedResponseCache
     * @return PreparedResponseCache
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(PreparedResponseCache $preparedResponseCache): PreparedResponseCache
    {
        $this->getEntityManager()->persist($preparedResponseCache);
        $this->getEntityManager()->flush();

        return $preparedResponseCache;
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
}