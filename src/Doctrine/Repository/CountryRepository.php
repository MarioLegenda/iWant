<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CountryRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }
    /**
     * @param Country $country
     * @return Country
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(Country $country): Country
    {
        $this->getEntityManager()->persist($country);
        $this->getEntityManager()->flush();

        return $country;
    }
    /**
     * @param Country $country
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlushIfNotExists(Country $country): bool
    {
        $existingCountry = $this->findOneBy([
            'alpha3Code' => $country->getAlpha3Code()
        ]);

        if (!$existingCountry instanceof Country) {
            $this->persistAndFlush($country);

            return true;
        }

        return false;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
}