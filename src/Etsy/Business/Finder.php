<?php

namespace App\Etsy\Business;

use App\Cache\Implementation\RequestCacheImplementation;
use App\Etsy\Business\Request\FindAllListingActive;
use App\Etsy\Business\Request\FindAllListingImages;
use App\Etsy\Business\Request\FindAllListingShippingProfileEntries;
use App\Etsy\Business\Request\FindAllShopListingsFeatured;
use App\Etsy\Business\Request\GetCountry;
use App\Etsy\Business\Request\GetListing;
use App\Etsy\Business\Request\GetListingShop;
use App\Etsy\Library\Response\CountryResponseModel;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Response\FindAllListingImagesResponseModel;
use App\Etsy\Library\Response\FindAllShopListingsFeaturedResponseModel;
use App\Etsy\Library\Response\GetListingResponseModel;
use App\Etsy\Library\Response\GetListingShopResponseModel;
use App\Etsy\Library\Response\ShippingProfileEntriesResponseModel;
use App\Library\Http\Request;
use App\Library\Response;
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

        /** @var Response $response */
        $response = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createFindAllListingActiveResponseModel($response->getResponseString());

        return $responseModel;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
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

        /** @var Response $response */
        $response = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createFindAllShopListingsFeaturedResponseModel($response->getResponseString());

        return $responseModel;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
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

        /** @var Response $response */
        $response = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createGetListingResponseModel($response->getResponseString());

        return $responseModel;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
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

        /** @var Response $response */
        $response = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createShippingProfileEntriesResponseModel($response->getResponseString());

        return $responseModel;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
     */
    public function findCountryByCountryId(EtsyApiModel $model): EtsyApiResponseModelInterface
    {
        $getCountry = new GetCountry(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $getCountry->getRequest();

        /** @var Response $response */
        $response = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createCountryResponseModel($response->getResponseString());

        return $responseModel;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
     */
    public function findAllListingImages(EtsyApiModel $model): EtsyApiResponseModelInterface
    {
        $findAllListingImages = new FindAllListingImages(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $findAllListingImages->getRequest();

        /** @var Response $response */
        $response = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createListingImagesResponseModel($response->getResponseString());

        return $responseModel;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
     */
    public function findGetListingShop(EtsyApiModel $model): EtsyApiResponseModelInterface
    {
        $getListingShop = new GetListingShop(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $getListingShop->getRequest();

        /** @var Response $response */
        $response = $this->finderSource->getResource($request);

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $this->createGetListingShopResponseModel($response->getResponseString());

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
    /**
     * @param string $responseString
     * @return EtsyApiResponseModelInterface
     */
    private function createListingImagesResponseModel(string $responseString): EtsyApiResponseModelInterface
    {
        $responseData = json_decode($responseString, true);

        return new FindAllListingImagesResponseModel(LockedImmutableGenericHashSet::create($responseData));
    }
    /**
     * @param string $responseString
     * @return EtsyApiResponseModelInterface
     */
    private function createGetListingShopResponseModel(string $responseString): EtsyApiResponseModelInterface
    {
        $responseData = json_decode($responseString, true);

        return new GetListingShopResponseModel(LockedImmutableGenericHashSet::create($responseData));


    }
}