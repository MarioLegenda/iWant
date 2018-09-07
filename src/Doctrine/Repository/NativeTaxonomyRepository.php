<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\NativeTaxonomy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class NativeTaxonomyRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NativeTaxonomy::class);
    }
    /**
     * @param NativeTaxonomy $normalizedCategory
     * @return NativeTaxonomy
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(NativeTaxonomy $normalizedCategory): NativeTaxonomy
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