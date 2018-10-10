<?php

namespace App\Component\Search;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Finder;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Response\SearchResponseModel;

class SearchComponent
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * @var SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    private $searchResponseCacheImplementation;
    /**
     * SearchComponent constructor.
     * @param Finder $finder
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    public function __construct(
        Finder $finder,
        SearchResponseCacheImplementation $searchResponseCacheImplementation
    ) {
        $this->finder = $finder;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
    }
    /**
     * @param SearchModel $model
     * @return iterable
     * @throws \App\Cache\Exception\CacheException
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function searchEbay(SearchModel $model): iterable
    {
        $uniqueName = md5(serialize($model));
        /** @var SearchResponseModel[] $ebayProducts */
        if ($this->searchResponseCacheImplementation->isStored($uniqueName)) {
            $products = $this->searchResponseCacheImplementation->getStored($uniqueName);

            return json_decode($products);
        }

        $products = apply_on_iterable($this->finder->findEbayProducts($model), function(array $responseData) {
            $normalizedItems = [];

            /** @var SearchResponseModel $searchResponseModel */
            foreach ($responseData['items'] as $searchResponseModel) {
                $normalizedItems[] = $searchResponseModel->toArray();
            }

            $responseData['items'] = $normalizedItems;

            return $responseData;
        });

        $this->searchResponseCacheImplementation->store($uniqueName, json_encode($products));

        return $products;
    }

    public function searchEtsy(SearchModel $model): iterable
    {

    }
}