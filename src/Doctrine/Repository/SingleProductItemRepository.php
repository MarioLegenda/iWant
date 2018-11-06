<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\SingleProductItem;
use App\Library\MarketplaceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class SingleProductItemRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SingleProductItem::class);
    }
    /**
     * @param SingleProductItem $singleProductItem
     * @return SingleProductItem
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(SingleProductItem $singleProductItem): SingleProductItem
    {
        $this->getEntityManager()->persist($singleProductItem);
        $this->getEntityManager()->flush();

        return $singleProductItem;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
}