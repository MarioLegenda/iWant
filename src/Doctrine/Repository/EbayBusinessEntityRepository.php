<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\EbayBusinessEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class EbayBusinessEntityRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EbayBusinessEntity::class);
    }
    /**
     * @param EbayBusinessEntity $ebayBusinessEntity
     * @return EbayBusinessEntity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(EbayBusinessEntity $ebayBusinessEntity): EbayBusinessEntity
    {
        $this->getEntityManager()->persist($ebayBusinessEntity);
        $this->getEntityManager()->flush();

        return $ebayBusinessEntity;
    }
    /**
     * @param string $globalId
     * @return array
     */
    public function findBusinessesByGlobalId(string $globalId): array
    {
        return $this->findBy([
            'globalId' => $globalId,
        ]);
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
}