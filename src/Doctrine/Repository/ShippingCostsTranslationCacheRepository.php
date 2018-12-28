<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\ShippingCostsTranslationCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ShippingCostsTranslationCacheRepository extends ServiceEntityRepository
{
    /**
     * ShippingCostsTranslationCacheRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingCostsTranslationCache::class);
    }
    /**
     * @param ShippingCostsTranslationCache $shippingCostsTranslationCache
     * @return ShippingCostsTranslationCache
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(ShippingCostsTranslationCache $shippingCostsTranslationCache): ShippingCostsTranslationCache
    {
        $this->getEntityManager()->persist($shippingCostsTranslationCache);
        $this->getEntityManager()->flush();

        return $shippingCostsTranslationCache;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
}