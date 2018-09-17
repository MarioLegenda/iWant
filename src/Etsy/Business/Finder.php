<?php

namespace App\Etsy\Business;

use App\Cache\Implementation\RequestCacheImplementation;
use App\Etsy\Business\Request\FindAllListingActive;
use App\Etsy\Business\Request\FindAllShopListingsFeatured;
use App\Etsy\Business\Request\GetListing;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Response\FindAllShopListingsFeaturedResponseModel;
use App\Etsy\Library\Response\GetListingResponseModel;
use App\Library\Http\Request;
use App\Library\Tools\LockedImmutableGenericHashSet;
use App\Etsy\Library\Processor\ApiKeyProcessor;
use App\Etsy\Library\Processor\RequestBaseProcessor;
use App\Etsy\Library\Response\FindAllListingActiveResponseModel;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Etsy\Source\FinderSource;

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
     * @var RequestCacheImplementation $cacheImplementation
     */
    private $cacheImplementation;
    /**
     * Finder constructor.
     * @param RequestBaseProcessor $requestBaseProcessor
     * @param ApiKeyProcessor $apiKeyProcessor
     * @param FinderSource $finderSource
     * @param RequestCacheImplementation $cacheImplementation
     */
    public function __construct(
        RequestBaseProcessor $requestBaseProcessor,
        ApiKeyProcessor $apiKeyProcessor,
        FinderSource $finderSource,
        RequestCacheImplementation $cacheImplementation
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
    public function findAllListingActive(EtsyApiModel $model): EtsyApiResponseModelInterface
    {
        $findAllListingActive = new FindAllListingActive(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $findAllListingActive->getRequest();

        if ($this->cacheImplementation->isRequestStored($request)) {
            return $this->createFindAllListingActiveResponseModel(
                $this->cacheImplementation->getFromStoreByRequest($request)
            );
        }

        $resource = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createFindAllListingActiveResponseModel($resource);

        $this->cacheImplementation->store($request, $resource);

        return $responseModel;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findAllShopListingsFeatured(EtsyApiModel $model)
    {
        $findAllShopListingFeatured = new FindAllShopListingsFeatured(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $findAllShopListingFeatured->getRequest();

        if ($this->cacheImplementation->isRequestStored($request)) {
            return $this->createFindAllShopListingsFeaturedResponseModel(
                $this->cacheImplementation->getFromStoreByRequest($request)
            );
        }

        $resource = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createFindAllShopListingsFeaturedResponseModel($resource);

        $this->cacheImplementation->store($request, $resource);

        return $responseModel;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getListing(EtsyApiModel $model)
    {
        $getListing = new GetListing(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $getListing->getRequest();

        if ($this->cacheImplementation->isRequestStored($request)) {
            return $this->createGetListingResponseModel(
                $this->cacheImplementation->getFromStoreByRequest($request)
            );
        }

        $resource = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createGetListingResponseModel($resource);

        $this->cacheImplementation->store($request, $resource);

        return $responseModel;
    }
    /**
     * @param string $responseString
     * @return FindAllListingActiveResponseModel
     */
    private function createFindAllListingActiveResponseModel(string $responseString): EtsyApiResponseModelInterface
    {
        $responseData = json_decode($responseString, true);

        return new FindAllListingActiveResponseModel(LockedImmutableGenericHashSet::create($responseData));
    }
    /**
     * @param string $responseString
     * @return EtsyApiResponseModelInterface
     */
    private function createFindAllShopListingsFeaturedResponseModel(string $responseString): EtsyApiResponseModelInterface
    {
        $responseData = json_decode($responseString, true);

        return new FindAllShopListingsFeaturedResponseModel(LockedImmutableGenericHashSet::create($responseData));
    }
    /**
     * @param string $responseString
     * @return EtsyApiResponseModelInterface
     */
    private function createGetListingResponseModel(string $responseString): EtsyApiResponseModelInterface
    {
        $responseData = json_decode($responseString, true);

        return new GetListingResponseModel(LockedImmutableGenericHashSet::create($responseData));
    }
}