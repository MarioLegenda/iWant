<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\TodayKeyword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class TodaysKeywordRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodayKeyword::class);
    }
    /**
     * @param TodayKeyword $todayKeyword
     * @return TodayKeyword
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(TodayKeyword $todayKeyword): TodayKeyword
    {
        $this->getEntityManager()->persist($todayKeyword);
        $this->getEntityManager()->flush();

        return $todayKeyword;
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
}