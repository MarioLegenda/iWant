<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\TextLocaleIdentifier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TextLocaleIdentifierRepository extends ServiceEntityRepository
{
    /**
     * TextLocaleIdentifierRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextLocaleIdentifier::class);
    }
    /**
     * @param TextLocaleIdentifier $textLocaleIdentifier
     * @return TextLocaleIdentifier
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(TextLocaleIdentifier $textLocaleIdentifier): TextLocaleIdentifier
    {
        $this->getEntityManager()->persist($textLocaleIdentifier);
        $this->getEntityManager()->flush();

        return $textLocaleIdentifier;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
}