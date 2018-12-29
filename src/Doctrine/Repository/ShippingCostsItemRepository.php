<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\ShippingCostsItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ShippingCostsItemRepository extends ServiceEntityRepository
{
    /**
     * ShippingCostsItemRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingCostsItem::class);
    }
    /**
     * @param ShippingCostsItem $shippingCostsItem
     * @return ShippingCostsItem
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(ShippingCostsItem $shippingCostsItem): ShippingCostsItem
    {
        $this->getEntityManager()->persist($shippingCostsItem);
        $this->getEntityManager()->flush();

        return $shippingCostsItem;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
}