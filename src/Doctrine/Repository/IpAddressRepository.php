<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\IpAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class IpAddressRepository extends ServiceEntityRepository
{
    /**
     * IpAddressRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IpAddress::class);
    }
    /**
     * @param IpAddress $ipAddress
     * @return IpAddress
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(IpAddress $ipAddress): IpAddress
    {
        $this->getEntityManager()->persist($ipAddress);
        $this->getEntityManager()->flush();

        return $ipAddress;
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
}