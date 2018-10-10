<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\SearchCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class SearchCacheRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchCache::class);
    }
    /**
     * @param SearchCache $searchCache
     * @return SearchCache
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(SearchCache $searchCache): SearchCache
    {
        $this->getEntityManager()->persist($searchCache);
        $this->getEntityManager()->flush();

        return $searchCache;
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
}