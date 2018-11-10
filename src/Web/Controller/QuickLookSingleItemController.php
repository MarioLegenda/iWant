<?php

namespace App\Web\Controller;

use App\App\Presentation\EntryPoint\QuickLookEntryPoint;
use App\App\Presentation\Model\Request\SingleItemOptionsModel;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\App\Presentation\Model\Response\SingleItemOptionsResponse;
use App\App\Presentation\Model\Response\SingleItemResponseModel;
use App\Web\Library\ApiResponseDataFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class QuickLookSingleItemController
{
    /**
     * @var ApiResponseDataFactory $apiResponseDataFactory
     */
    private $apiResponseDataFactory;
    /**
     * SingleItemController constructor.
     * @param ApiResponseDataFactory $apiResponseDataFactory
     */
    public function __construct(
        ApiResponseDataFactory $apiResponseDataFactory
    ) {
        $this->apiResponseDataFactory = $apiResponseDataFactory;
    }
    /**
     * @param SingleItemOptionsModel $model
     * @param QuickLookEntryPoint $singleItemEntryPoint
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function optionsCheckSingleItem(
        SingleItemOptionsModel $model,
        QuickLookEntryPoint $singleItemEntryPoint
    ) {
        /** @var SingleItemOptionsResponse $optionsResponseModel */
        $optionsResponseModel = $singleItemEntryPoint->optionsCheckSingleItem($model);

        $responseData = $this->apiResponseDataFactory->createOptionsResponseData($optionsResponseModel->toArray());

        return new JsonResponse(
            $responseData->toArray(),
            $responseData->getStatusCode()
        );
    }
    /**
     * @param SingleItemRequestModel $singleItemRequestModel
     * @param QuickLookEntryPoint $singleItemEntryPoint
     * @return JsonResponse
     * @throws \App\Cache\Exception\CacheException
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function putSingleItem(
        SingleItemRequestModel $singleItemRequestModel,
        QuickLookEntryPoint $singleItemEntryPoint
    ) {
        /** @var SingleItemResponseModel $singleItemReponseModel */
        $singleItemResponseModel = $singleItemEntryPoint->putSingleItem($singleItemRequestModel);

        $responseData = $this->apiResponseDataFactory->createSingleItemPutResponseData(
            $singleItemResponseModel->toArray()
        );

        return new JsonResponse(
            $responseData->toArray(),
            $responseData->getStatusCode()
        );
    }
    /**
     * @param SingleItemRequestModel $singleItemRequestModel
     * @param QuickLookEntryPoint $singleItemEntryPoint
     * @return JsonResponse
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getSingleItem(
        SingleItemRequestModel $singleItemRequestModel,
        QuickLookEntryPoint $singleItemEntryPoint
    ) {
        $singleItemResponseModel = $singleItemEntryPoint->getSingleItem($singleItemRequestModel);

        $responseData = $this->apiResponseDataFactory->createSingleItemGetResponseData(
            $singleItemResponseModel->toArray()
        );

        return new JsonResponse(
            $responseData->toArray(),
            $responseData->getStatusCode()
        );
    }
}