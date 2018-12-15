<?php

namespace App\Ebay\Business;

use App\Ebay\Business\Request\FindItemsAdvanced;
use App\Ebay\Business\Request\FindItemsByKeywords;
use App\Ebay\Business\Request\FindItemsInEbayStores;
use App\Ebay\Business\Request\GetCategoryInfo;
use App\Ebay\Business\Request\GetSingleItem;
use App\Ebay\Business\Request\GetUserProfile;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Library\Processor\ShoppingApiRequestBaseProcessor;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\ShoppingApi\GetCategoryInfoResponse;
use App\Ebay\Library\Response\ShoppingApi\GetSingleItemResponse;
use App\Ebay\Library\Response\ShoppingApi\GetUserProfileResponse;
use App\Library\Http\Request;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Processor\FindingApiRequestBaseProcessor;
use App\Ebay\Source\FinderSource;
use App\Symfony\Exception\HttpException;

class Finder
{
    /**
     * @var FinderSource $finderSource
     */
    private $finderSource;
    /**
     * @var FindingApiRequestBaseProcessor $requestBase
     */
    private $requestBase;
    /**
     * @var ShoppingApiRequestBaseProcessor $shoppingApiRequestBaseProcessor
     */
    private $shoppingApiRequestBaseProcessor;
    /**
     * Finder constructor.
     * @param FinderSource $finderSource
     * @param FindingApiRequestBaseProcessor $requestBase
     * @param ShoppingApiRequestBaseProcessor $shoppingApiRequestBaseProcessor
     */
    public function __construct(
        FinderSource $finderSource,
        FindingApiRequestBaseProcessor $requestBase,
        ShoppingApiRequestBaseProcessor $shoppingApiRequestBaseProcessor
    ) {
        $this->finderSource = $finderSource;
        $this->requestBase = $requestBase;
        $this->shoppingApiRequestBaseProcessor = $shoppingApiRequestBaseProcessor;
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return FindingApiResponseModelInterface
     * @throws HttpException
     */
    public function findItemsByKeywords(FindingApiRequestModelInterface $model): FindingApiResponseModelInterface
    {
        $findItemsByKeywords = new FindItemsByKeywords(
            $model,
            $this->requestBase
        );

        /** @var Request $request */
        $request = $findItemsByKeywords->getRequest();

        /** @var ResponseModelInterface $resource */
        $response = $this->finderSource->getApiResource($request);

        /** @var FindingApiResponseModelInterface $responseModel */
        $responseModel = $this->createKeywordsModelResponse($response->getBody());

        if (!$responseModel->getRoot()->isSuccess()) {
            throw new HttpException($response->getBody());
        }

        return $responseModel;
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return FindingApiResponseModelInterface
     * @throws HttpException
     */
    public function findItemsAdvanced(FindingApiRequestModelInterface $model): FindingApiResponseModelInterface
    {
        $findItemsAdvanced = new FindItemsAdvanced($model, $this->requestBase);

        /** @var Request $request */
        $request = $findItemsAdvanced->getRequest();

        /** @var ResponseModelInterface $resource */
        $response = $this->finderSource->getApiResource($request);
        /** @var FindingApiResponseModelInterface $responseModel */
        $responseModel = $this->createKeywordsModelResponse($response->getBody());

        if (!$responseModel->getRoot()->isSuccess()) {
            throw new HttpException($response->getBody());
        }

        return $responseModel;
    }
    /**
     * @param ShoppingApiRequestModelInterface $model
     * @return ResponseModelInterface
     * @throws HttpException
     */
    public function getUserProfile(ShoppingApiRequestModelInterface $model): ResponseModelInterface
    {
        $getUserProfile = new GetUserProfile($model, $this->shoppingApiRequestBaseProcessor);

        /** @var Request $request */
        $request = $getUserProfile->getRequest();

        /** @var ResponseModelInterface $resource */
        $response = $this->finderSource->getApiResource($request);
        /** @var GetUserProfileResponse $responseModel */
        $responseModel = $this->createUserProfileResponse($response->getBody());

        if (!$responseModel->getRoot()->isSuccess()) {
            throw new HttpException($responseModel->getRawResponse());
        }

        return $responseModel;
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return FindingApiResponseModelInterface
     * @throws HttpException
     */
    public function findItemsInEbayStores(FindingApiRequestModelInterface $model): FindingApiResponseModelInterface
    {
        $findItemsInEbayStores = new FindItemsInEbayStores($model, $this->requestBase);

        /** @var Request $request */
        $request = $findItemsInEbayStores->getRequest();

        /** @var ResponseModelInterface $resource */
        $response = $this->finderSource->getApiResource($request);
        /** @var FindingApiResponseModelInterface $responseModel */
        $responseModel = $this->createKeywordsModelResponse($response->getBody());

        if (!$responseModel->getRoot()->isSuccess()) {
            throw new HttpException($response->getBody());
        }

        return $responseModel;
    }
    /**
     * @param ShoppingApiRequestModelInterface $model
     * @return ResponseModelInterface
     * @throws HttpException
     */
    public function getCategoryInfo(ShoppingApiRequestModelInterface $model): ResponseModelInterface
    {
        $getCategoryInfo = new GetCategoryInfo($model, $this->shoppingApiRequestBaseProcessor);

        /** @var Request $request */
        $request = $getCategoryInfo->getRequest();

        /** @var ResponseModelInterface $resource */
        $response = $this->finderSource->getApiResource($request);
        /** @var GetCategoryInfoResponse $responseModel */
        $responseModel = $this->createCategoryInfoResponse($response->getBody());

        if (!$responseModel->getRoot()->isSuccess()) {
            throw new HttpException($response->getBody());
        }

        return $responseModel;
    }
    /**
     * @param ShoppingApiRequestModelInterface $model
     * @return ResponseModelInterface
     * @throws HttpException
     */
    public function getSingleItem(ShoppingApiRequestModelInterface $model): ResponseModelInterface
    {
        $getSingleItem = new GetSingleItem($model, $this->shoppingApiRequestBaseProcessor);

        /** @var Request $request */
        $request = $getSingleItem->getRequest();

        /** @var ResponseModelInterface $resource */
        $response = $this->finderSource->getApiResource($request);
        /** @var GetSingleItemResponse $responseModel */
        $responseModel = $this->createSingleItemResponse($response->getBody());

        if (!$responseModel->getRoot()->isSuccess()) {
            throw new HttpException($response->getBody());
        }

        return $responseModel;
    }
    /**
     * @param string $resource
     * @return FindingApiResponseModelInterface
     */
    private function createKeywordsModelResponse(string $resource): FindingApiResponseModelInterface
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
    /**
     * @param string $resource
     * @return ResponseModelInterface
     */
    private function createCategoryInfoResponse(string $resource): ResponseModelInterface
    {
        return new GetCategoryInfoResponse($resource);
    }
    /**
     * @param string $resource
     * @return GetSingleItemResponse
     */
    private function createSingleItemResponse(string $resource): GetSingleItemResponse
    {
        return new GetSingleItemResponse($resource);
    }
}