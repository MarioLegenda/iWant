<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\ModifiedKeyword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ModifiedKeywordRepository extends ServiceEntityRepository
{
    /**
     * ModifiedKeywordRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModifiedKeyword::class);
    }
    /**
     * @param ModifiedKeyword $modifiedKeyword
     * @return ModifiedKeyword
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(ModifiedKeyword $modifiedKeyword): ModifiedKeyword
    {
        $this->getEntityManager()->persist($modifiedKeyword);
        $this->getEntityManager()->flush();

        return $modifiedKeyword;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
}