<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\SearchQueryFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class SearchQueryFilterRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchQueryFilter::class);
    }
    /**
     * @param SearchQueryFilter $searchQueryFilter
     * @return SearchQueryFilter
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(SearchQueryFilter $searchQueryFilter): SearchQueryFilter
    {
        $this->getEntityManager()->persist($searchQueryFilter);
        $this->getEntityManager()->flush();

        return $searchQueryFilter;
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
}