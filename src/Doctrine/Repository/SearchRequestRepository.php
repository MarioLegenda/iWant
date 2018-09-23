<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\SearchRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class SearchRequestRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchRequest::class);
    }
    /**
     * @param SearchRequest $searchRequest
     * @return SearchRequest
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(SearchRequest $searchRequest): SearchRequest
    {
        $this->getEntityManager()->persist($searchRequest);
        $this->getEntityManager()->flush();

        return $searchRequest;
    }
    /**
     * @param string $uniqueName
     * @return SearchRequest
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveIfNotExists(string $uniqueName)
    {
        /** @var SearchRequest $searchRequest */
        $searchRequest = $this->findOneBy([
            'name' => $uniqueName,
        ]);

        if (!$searchRequest instanceof SearchRequest) {
            $newSearchRequest = new SearchRequest($uniqueName);

            return $this->persistAndFlush($newSearchRequest);
        }

        return $searchRequest;
    }
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        $this->getEntityManager();
    }
}