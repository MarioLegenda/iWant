<?php

namespace App\Etsy\Business;

use App\Cache\Implementation\RequestCacheImplementation;
use App\Etsy\Business\Request\FindAllListingActive;
use App\Etsy\Business\Request\FindAllListingShippingProfileEntries;
use App\Etsy\Business\Request\FindAllShopListingsFeatured;
use App\Etsy\Business\Request\GetCountry;
use App\Etsy\Business\Request\GetListing;
use App\Etsy\Library\Response\CountryResponseModel;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Response\FindAllShopListingsFeaturedResponseModel;
use App\Etsy\Library\Response\GetListingResponseModel;
use App\Etsy\Library\Response\ShippingProfileEntriesResponseModel;
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
    public function findAllShopListingsFeatured(EtsyApiModel $model): EtsyApiResponseModelInterface
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
    public function getListing(EtsyApiModel $model): EtsyApiResponseModelInterface
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
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findAllListingShippingProfileEntries(EtsyApiModel $model): EtsyApiResponseModelInterface
    {
        $findAllShippingEntries = new FindAllListingShippingProfileEntries(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $findAllShippingEntries->getRequest();

        if ($this->cacheImplementation->isRequestStored($request)) {
            return $this->createShippingProfileEntriesResponseModel(
                $this->cacheImplementation->getFromStoreByRequest($request)
            );
        }

        $resource = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createShippingProfileEntriesResponseModel($resource);

        $this->cacheImplementation->store($request, $resource);

        return $responseModel;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface|ShippingProfileEntriesResponseModel
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findCountryByCountryId(EtsyApiModel $model)
    {
        $getCountry = new GetCountry(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $getCountry->getRequest();

        if ($this->cacheImplementation->isRequestStored($request)) {
            return $this->createCountryResponseModel(
                $this->cacheImplementation->getFromStoreByRequest($request)
            );
        }

        $resource = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createCountryResponseModel($resource);

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
    /**
     * @param string $responseString
     * @return ShippingProfileEntriesResponseModel
     */
    private function createShippingProfileEntriesResponseModel(string $responseString): EtsyApiResponseModelInterface
    {
        $responseData = json_decode($responseString, true);

        return new ShippingProfileEntriesResponseModel(LockedImmutableGenericHashSet::create($responseData));
    }
    /**
     * @param string $responseString
     * @return EtsyApiResponseModelInterface
     */
    private function createCountryResponseModel(string $responseString): EtsyApiResponseModelInterface
    {
        $responseData = json_decode($responseString, true);

        return new CountryResponseModel(LockedImmutableGenericHashSet::create($responseData));
    }
}