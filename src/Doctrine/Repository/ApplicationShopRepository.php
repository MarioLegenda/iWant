<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\ApplicationShop;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class ApplicationShopRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApplicationShop::class);
    }
    /**
     * @param ApplicationShop $applicationShop
     * @return ApplicationShop
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(ApplicationShop $applicationShop): ApplicationShop
    {
        $this->getEntityManager()->persist($applicationShop);
        $this->getEntityManager()->flush();

        return $applicationShop;
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
    /**
     * @param MarketplaceType|TypeInterface $marketplace
     * @return array
     */
    public function findEssentialApplicationShopInformation(MarketplaceType $marketplace): array
    {
        $qb = $this->createQueryBuilder('asq');

        return $qb
            ->select('asq.applicationName', 'asq.globalId', 'asq.marketplace')
            ->where('asq.marketplace = :marketplace')
            ->setParameter(':marketplace', (string) $marketplace)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }
    /**
     * @param MarketplaceType|TypeInterface $marketplace
     * @return mixed
     */
    public function findGlobalsIdsByMarketplace(MarketplaceType $marketplace)
    {
        $qb = $this->createQueryBuilder('asq');

        $result = $qb
            ->select('asq.globalId')
            ->where('asq.marketplace = :marketplace')
            ->setParameter(':marketplace', (string) $marketplace)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        return apply_on_iterable($result, function($data) {
            return $data['globalId'];
        });
    }
}