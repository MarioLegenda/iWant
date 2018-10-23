<?php

namespace App\Component\Search;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Finder as EbayFinder;
use App\Component\Search\Etsy\Business\Finder as EtsyFinder;
use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;
use App\Component\Search\Etsy\Model\Request\SearchModel as EtsySearchModel;
use App\Component\Search\Ebay\Model\Response\SearchResponseModel;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;

class SearchComponent
{
    /**
     * @var EbayFinder $ebayFinder
     */
    private $ebayFinder;
    /**
     * @var EtsyFinder $etsyFinder
     */
    private $etsyFinder;
    /**
     * @var SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    private $searchResponseCacheImplementation;
    /**
     * SearchComponent constructor.
     * @param EbayFinder $ebayFinder
     * @param EtsyFinder $etsyFinder
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    public function __construct(
        EbayFinder $ebayFinder,
        EtsyFinder $etsyFinder,
        SearchResponseCacheImplementation $searchResponseCacheImplementation
    ) {
        $this->ebayFinder = $ebayFinder;
        $this->etsyFinder = $etsyFinder;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
    }
    /**
     * @param EbaySearchModel $model
     * @return iterable
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Http\Client\Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function searchEbay(EbaySearchModel $model): iterable
    {
        return $this->ebayFinder->findEbayProducts($model);
    }

    /**
     * @param EtsySearchModel $model
     * @return iterable
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function searchEtsy(EtsySearchModel $model): iterable
    {
        $uniqueName = md5(serialize($model));
        /** @var SearchResponseModel[] $ebayProducts */
        if ($this->searchResponseCacheImplementation->isStored($uniqueName)) {
            $products = $this->searchResponseCacheImplementation->getStored($uniqueName);

            return json_decode($products, true);
        }

        /** @var TypedArray $products */
        $products = $this->etsyFinder->findEtsyProducts($model);
        $productsArray = $products->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION);

        $this->searchResponseCacheImplementation->store(
            $uniqueName,
            $model->getPagination()->getPage(),
            json_encode($productsArray)
        );

        return $productsArray;
    }
}