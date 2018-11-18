<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\ResponseFetcher\ResponseFetcher;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Doctrine\Entity\SearchCache;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;

class ResultsFetcher
{
    /**
     * @var ResponseFetcher $responseFetcher
     */
    private $responseFetcher;
    /**
     * @var SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    private $searchResponseCacheImplementation;
    /**
     * ResultsFetcher constructor.
     * @param ResponseFetcher $responseFetcher
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    public function __construct(
        ResponseFetcher $responseFetcher,
        SearchResponseCacheImplementation $searchResponseCacheImplementation
    ) {
        $this->responseFetcher = $responseFetcher;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
    }

    public function getResults(SearchModel $model): iterable
    {
        $identifier = $model->getUniqueName();

        if ($this->searchResponseCacheImplementation->isStored($identifier)) {
            /** @var SearchCache $presentationResults */
            $presentationResults = $this->searchResponseCacheImplementation->getStored($identifier);

            return json_decode($presentationResults->getProductsResponse(), true);
        }

        /** @var TypedArray $presentationResults */
        $presentationResults = $this->responseFetcher->getResponse($model);

        $presentationResultsArray = $presentationResults->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION);

        $this->searchResponseCacheImplementation->store(
            $model->getUniqueName(),
            $model->getInternalPagination()->getPage(),
            jsonEncodeWithFix($presentationResultsArray)
        );

        return $presentationResultsArray;
    }
}