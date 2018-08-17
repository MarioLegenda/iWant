<?php

namespace App\Etsy\Business;

use App\Cache\CacheImplementation;
use App\Etsy\Business\Request\FindAllListingActive;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Library\Http\Request;
use App\Library\Tools\LockedImmutableGenericHashSet;
use App\Etsy\Business\ItemFilter\ItemFilterFactory;
use App\Etsy\Library\MethodProcessor\MethodProcessorFactory;
use App\Etsy\Library\Processor\ApiKeyProcessor;
use App\Etsy\Library\Processor\ItemFiltersProcessor;
use App\Etsy\Library\Processor\RequestBaseProcessor;
use App\Etsy\Library\RequestProducer;
use App\Etsy\Library\Response\EtsyApiResponseModel;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Etsy\Source\FinderSource;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;

class Finder
{
    /**
     * @var RequestBaseProcessor $requestBaseProcessor
     */
    private $requestBaseProcessor;
    /**
     * @var ApiKeyProcessor $apiKeyProcessor
     */
    private $apiKeyProcessor;
    /**
     * @var FinderSource $finderSource
     */
    private $finderSource;
    /**
     * @var CacheImplementation $cacheImplementation
     */
    private $cacheImplementation;
    /**
     * Finder constructor.
     * @param RequestBaseProcessor $requestBaseProcessor
     * @param ApiKeyProcessor $apiKeyProcessor
     * @param FinderSource $finderSource
     * @param CacheImplementation $cacheImplementation
     */
    public function __construct(
        RequestBaseProcessor $requestBaseProcessor,
        ApiKeyProcessor $apiKeyProcessor,
        FinderSource $finderSource,
        CacheImplementation $cacheImplementation
    ) {
        $this->requestBaseProcessor = $requestBaseProcessor;
        $this->apiKeyProcessor = $apiKeyProcessor;
        $this->finderSource = $finderSource;
        $this->cacheImplementation = $cacheImplementation;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function search(EtsyApiModel $model): EtsyApiResponseModelInterface
    {
        $findAllListingActive = new FindAllListingActive(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $findAllListingActive->getRequest();

        if (!$this->cacheImplementation->isRequestStored($request)) {
            $resource = $this->finderSource->getResource($request);

            $resource = $this->cacheImplementation->store($request, $resource);

            return $this->createResponseModel($resource);
        }

        return $this->createResponseModel(
            $this->cacheImplementation->getFromStoreByRequest($request)
        );
    }

    public function getShippingInfoByListingId(EtsyApiModel $model)
    {

    }
    /**
     * @param string $responseString
     * @return EtsyApiResponseModel
     */
    private function createResponseModel(string $responseString)
    {
        $responseData = json_decode($responseString, true);

        return new EtsyApiResponseModel(LockedImmutableGenericHashSet::create($responseData));
    }
}