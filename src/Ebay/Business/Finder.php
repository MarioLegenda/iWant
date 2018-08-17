<?php

namespace App\Ebay\Business;

use App\Cache\CacheImplementation;
use App\Ebay\Business\Request\FindItemsByKeywords;
use App\Library\Http\Request;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Processor\RequestBaseProcessor;
use App\Ebay\Source\FinderSource;

class Finder
{
    /**
     * @var FinderSource $finderSource
     */
    private $finderSource;
    /**
     * @var RequestBaseProcessor $requestBase
     */
    private $requestBase;
    /**
     * @var CacheImplementation $cacheImplementation
     */
    private $cacheImplementation;
    /**
     * Finder constructor.
     * @param FinderSource $finderSource
     * @param RequestBaseProcessor $requestBase
     * @param CacheImplementation $cacheImplementation
     */
    public function __construct(
        FinderSource $finderSource,
        RequestBaseProcessor $requestBase,
        CacheImplementation $cacheImplementation
    ) {
        $this->finderSource = $finderSource;
        $this->requestBase = $requestBase;
        $this->cacheImplementation = $cacheImplementation;
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return FindingApiResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findItemsByKeywords(FindingApiRequestModelInterface $model): FindingApiResponseModelInterface
    {
        $findItemsByKeywords = new FindItemsByKeywords(
            $model,
            $this->requestBase
        );

        /** @var Request $request */
        $request = $findItemsByKeywords->getRequest();

        if (!$this->cacheImplementation->isRequestStored($request)) {
            $resource = $this->finderSource->getFindingApiListing($request);

            $resource = $this->cacheImplementation->store($request, $resource);

            return $this->createModelResponse($resource);
        }

        return $this->createModelResponse(
            $this->cacheImplementation->getFromStoreByRequest($request)
        );
    }
    /**
     * @param string $resource
     * @return FindingApiResponseModelInterface
     */
    private function createModelResponse(string $resource): FindingApiResponseModelInterface
    {
        return new XmlFindingApiResponseModel($resource);
    }
}