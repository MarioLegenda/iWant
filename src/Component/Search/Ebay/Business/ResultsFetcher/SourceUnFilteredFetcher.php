<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\ResponseFetcher\ResponseFetcher;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Doctrine\Entity\SearchCache;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;

class SourceUnFilteredFetcher implements FetcherInterface
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
    /**
     * @param SearchModel $model
     * @param array $replacementData
     * @return iterable
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getResults(SearchModel $model, array $replacementData = []): iterable
    {
        $identifier = $model->getUniqueName($replacementData);

        if ($this->searchResponseCacheImplementation->isStored($identifier)) {
            /** @var SearchCache $presentationResults */
            $presentationResults = $this->searchResponseCacheImplementation->getStored($identifier);

            return json_decode($presentationResults->getProductsResponse(), true);
        }

        $presentationResultsArray = $this->getFreshResults($model, $identifier);

        $this->searchResponseCacheImplementation->store(
            $identifier,
            jsonEncodeWithFix($presentationResultsArray)
        );

        return $presentationResultsArray;
    }
    /**
     * @param SearchModel $model
     * @param string|null $identifier
     * @return iterable
     */
    public function getFreshResults(SearchModel $model, string $identifier = null)
    {
        /** @var TypedArray $presentationResults */
        $presentationResults = $this->responseFetcher->getResponse($model, $identifier);

        $presentationResultsArray = $presentationResults->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION);

        return $presentationResultsArray;
    }
}