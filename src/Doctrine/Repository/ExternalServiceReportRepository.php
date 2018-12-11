<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\ExternalServiceReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ExternalServiceReportRepository extends ServiceEntityRepository
{
    /**
     * ExternalServiceReportRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalServiceReport::class);
    }
    /**
     * @param ExternalServiceReport $externalServiceReport
     * @return ExternalServiceReport
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(ExternalServiceReport $externalServiceReport): ExternalServiceReport
    {
        $this->getEntityManager()->persist($externalServiceReport);
        $this->getEntityManager()->flush();

        return $externalServiceReport;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
    /**
     * @param string $type
     * @return ExternalServiceReport|null
     */
    public function tryGetByType(string $type): ?ExternalServiceReport
    {
        /** @var ExternalServiceReport $externalServiceType */
        $externalServiceType = $this->findOneBy([
            'externalServiceType' => $type,
        ]);

        return ($externalServiceType instanceof ExternalServiceReport) ? $externalServiceType : null;
    }
    /**
     * @param string $type
     * @return ExternalServiceReport
     */
    public function getByType(string $type): ExternalServiceReport
    {


        return $externalServiceType;
    }
}