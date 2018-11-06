<?php

namespace App\Web\Controller;

use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\App\Presentation\Model\Request\SingleItemOptionsModel;
use App\App\Presentation\Model\Response\SingleItemOptionsResponse;
use App\Web\Library\ApiResponseDataFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class SingleItemController
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
     * @param SingleItemEntryPoint $singleItemEntryPoint
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function optionsCheckSingleItem(
        SingleItemOptionsModel $model,
        SingleItemEntryPoint $singleItemEntryPoint
    ) {
        /** @var SingleItemOptionsResponse $optionsResponseModel */
        $optionsResponseModel = $singleItemEntryPoint->optionsCheckSingleItem($model);

        $responseData = $this->apiResponseDataFactory->createOptionsResponseData($optionsResponseModel->toArray());

        return new JsonResponse(
            $responseData->toArray(),
            $responseData->getStatusCode()
        );
    }
}