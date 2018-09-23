<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\ApplicationShop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class ApplicationShopRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApplicationShop::class);
    }
    /**
     * @param ApplicationShop $applicationShop
     * @return ApplicationShop
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(ApplicationShop $applicationShop): ApplicationShop
    {
        $this->getEntityManager()->persist($applicationShop);
        $this->getEntityManager()->flush();

        return $applicationShop;
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
}