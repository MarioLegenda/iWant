<?php

namespace App\Ebay\Business;

use App\Cache\Implementation\RequestCacheImplementation;
use App\Ebay\Business\Request\FindItemsAdvanced;
use App\Ebay\Business\Request\FindItemsByKeywords;
use App\Ebay\Business\Request\FindItemsInEbayStores;
use App\Ebay\Business\Request\GetUserProfile;
use App\Ebay\Library\Processor\ShoppingApiRequestBaseProcessor;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\ShoppingApi\GetUserProfileResponse;
use App\Ebay\Library\Response\ShoppingApi\GetUserProfileResponseInterface;
use App\Library\Http\Request;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Processor\RequestBaseProcessor;
use App\Ebay\Source\FinderSource;
use App\Library\Response;

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
     * @var RequestCacheImplementation $cacheImplementation
     */
    private $cacheImplementation;
    /**
     * @var ShoppingApiRequestBaseProcessor $shoppingApiRequestBaseProcessor
     */
    private $shoppingApiRequestBaseProcessor;
    /**
     * Finder constructor.
     * @param FinderSource $finderSource
     * @param RequestBaseProcessor $requestBase
     * @param RequestCacheImplementation $cacheImplementation
     * @param ShoppingApiRequestBaseProcessor $shoppingApiRequestBaseProcessor
     */
    public function __construct(
        FinderSource $finderSource,
        RequestBaseProcessor $requestBase,
        RequestCacheImplementation $cacheImplementation,
        ShoppingApiRequestBaseProcessor $shoppingApiRequestBaseProcessor
    ) {
        $this->finderSource = $finderSource;
        $this->requestBase = $requestBase;
        $this->cacheImplementation = $cacheImplementation;
        $this->shoppingApiRequestBaseProcessor = $shoppingApiRequestBaseProcessor;
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
            /** @var Response $resource */
            $resource = $this->finderSource->getApiResource($request);

            $stringResource = $this->cacheImplementation->store($request, $resource->getResponseString());

            return $this->createModelResponse($stringResource);
        }

        return $this->createModelResponse(
            $this->cacheImplementation->getFromStoreByRequest($request)
        );
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return FindingApiResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findItemsAdvanced(FindingApiRequestModelInterface $model): FindingApiResponseModelInterface
    {
        $findItemsAdvanced = new FindItemsAdvanced($model, $this->requestBase);

        /** @var Request $request */
        $request = $findItemsAdvanced->getRequest();

        if (!$this->cacheImplementation->isRequestStored($request)) {
            /** @var Response $resource */
            $resource = $this->finderSource->getApiResource($request);

            $stringResource = $this->cacheImplementation->store($request, $resource->getResponseString());

            return $this->createModelResponse($stringResource);
        }

        return $this->createModelResponse(
            $this->cacheImplementation->getFromStoreByRequest($request)
        );
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return ResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getUserProfile(FindingApiRequestModelInterface $model): ResponseModelInterface
    {
        $getUserProfile = new GetUserProfile($model, $this->shoppingApiRequestBaseProcessor);

        /** @var Request $request */
        $request = $getUserProfile->getRequest();

        if (!$this->cacheImplementation->isRequestStored($request)) {
            $resource = $this->finderSource->getApiResource($request);

            $stringResource = $this->cacheImplementation->store($request, $resource->getResponseString());

            return $this->createUserProfileResponse($stringResource);
        }

        return $this->createUserProfileResponse(
            $this->cacheImplementation->getFromStoreByRequest($request)
        );
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return FindingApiResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findItemsInEbayStores(FindingApiRequestModelInterface $model): FindingApiResponseModelInterface
    {
        $findItemsInEbayStores = new FindItemsInEbayStores($model, $this->requestBase);

        $request = $findItemsInEbayStores->getRequest();

        if (!$this->cacheImplementation->isRequestStored($request)) {
            /** @var Response $resource */
            $resource = $this->finderSource->getApiResource($request);

            $stringResource = $this->cacheImplementation->store($request, $resource->getResponseString());

            return $this->createModelResponse($stringResource);
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
    /**
     * @param string $resource
     * @return GetUserProfileResponse
     */
    private function createUserProfileResponse(string $resource): GetUserProfileResponse
    {
        return new GetUserProfileResponse($resource);
    }
}