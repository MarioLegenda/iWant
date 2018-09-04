<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\EbayRootCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class EbayRootCategoryRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EbayRootCategory::class);
    }
    /**
     * @param EbayRootCategory $ebayRootCategory
     * @return EbayRootCategory
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(EbayRootCategory $ebayRootCategory): EbayRootCategory
    {
        $this->getEntityManager()->persist($ebayRootCategory);
        $this->getEntityManager()->flush();

        return $ebayRootCategory;
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
}