<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\RequestCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RequestCacheRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestCache::class);
    }
    /**
     * @param RequestCache $language
     * @return RequestCache
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(RequestCache $language): RequestCache
    {
        $this->getEntityManager()->persist($language);
        $this->getEntityManager()->flush();

        return $language;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
}