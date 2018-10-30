<?php

namespace App\Component\Search\Ebay\Business\Factory\SpreadFactory;

use App\Cache\Implementation\PreparedResponseCacheImplementation;
use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Factory\SearchResponseModelFactory;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Response\PreparedEbayResponse;
use App\Doctrine\Entity\SearchCache;
use App\Ebay\Library\Information\GlobalIdInformation;

class LowestPriceCacheFactory
{
    /**
     * @var SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    private $searchResponseCacheImplementation;
    /**
     * @var PreparedResponseCacheImplementation $preparedResponseCacheImplementation
     */
    private $preparedResponseCacheImplementation;
    /**
     * LowestPriceCacheFactory constructor.
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param PreparedResponseCacheImplementation $preparedResponseCacheImplementation
     */
    public function __construct(
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        PreparedResponseCacheImplementation $preparedResponseCacheImplementation
    ) {
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->preparedResponseCacheImplementation = $preparedResponseCacheImplementation;
    }
    /**
     * @param SearchModel $bestMatchModel
     * @param SearchModel $lowestPriceModel
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function storeInCache(
        SearchModel $bestMatchModel,
        SearchModel $lowestPriceModel
    ): void {
        $bestMatchUniqueName = md5(serialize($bestMatchModel));
        $lowestPriceUniqueName = md5(serialize($lowestPriceModel));

        if (
            $this->searchResponseCacheImplementation->isStored($lowestPriceUniqueName) and
            $this->preparedResponseCacheImplementation->isStored($lowestPriceUniqueName)) {

            return;
        }

        /** @var SearchCache $searchCache */
        $searchCache = $this->searchResponseCacheImplementation->getStored($bestMatchUniqueName);
        $preparedCache = $this->preparedResponseCacheImplementation->getStored($bestMatchUniqueName);

        $responseModelsArray = json_decode($searchCache, true);
        $preparedModelArray = json_decode($preparedCache, true);

        usort($responseModelsArray, function(array $item1, array $item2) {
            $price1 = (float) $item1['price']['price'];
            $price2 = (float) $item2['price']['price'];

            return $price1 >= $price2;
        });

        $preparedEbayResponse = new PreparedEbayResponse(
            $lowestPriceUniqueName,
            $preparedModelArray['globalIdInformation'],
            $preparedModelArray['globalId'],
            $preparedModelArray['totalEntries'],
            $preparedModelArray['entriesPerPage'],
            $preparedModelArray['isError']
        );

        $this->preparedResponseCacheImplementation->store(
            $lowestPriceUniqueName,
            json_encode($preparedEbayResponse->toArray())
        );

        $this->searchResponseCacheImplementation->store(
            $lowestPriceUniqueName,
            $lowestPriceModel->getPagination()->getPage(),
            json_encode($responseModelsArray)
        );
    }
}