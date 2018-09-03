<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\NormalizedCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class NormalizedCategoryRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NormalizedCategory::class);
    }
    /**
     * @param NormalizedCategory $normalizedCategory
     * @return NormalizedCategory
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(NormalizedCategory $normalizedCategory): NormalizedCategory
    {
        $this->getEntityManager()->persist($normalizedCategory);
        $this->getEntityManager()->flush();

        return $normalizedCategory;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
}