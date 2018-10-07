<?php

namespace App\Component\Search;

use App\Component\Search\Ebay\Business\Finder;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Library\Infrastructure\Helper\TypedArray;

class SearchComponent
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * SearchComponent constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
    /**
     * @param SearchModel $model
     * @return iterable
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function searchEbay(SearchModel $model): iterable
    {
        /** @var XmlFindingApiResponseModel[]|TypedArray $ebayProducts */
        $ebayProducts = $this->finder->findEbayProducts($model);

        
    }

    public function searchEtsy(SearchModel $model): iterable
    {

    }
}