<?php

namespace App\Web\Controller;

use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Library\Http\Response\ApiResponseData;
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

    public function getSingleItem(
        SingleItemRequestModel $model,
        SingleItemEntryPoint $singleItemEntryPoint
    ) {
        $singleItemArray = $singleItemEntryPoint->getSingleItem($model);

        if (is_null($singleItemArray)) {
            /** @var ApiResponseData $response404 */
            $response404 = $this->apiResponseDataFactory->create404Response();

            return new JsonResponse(
                $response404->getData(),
                $response404->getStatusCode()
            );
        }

        /** @var ApiResponseData $singleItemResponseData */
        $singleItemResponseData = $this->apiResponseDataFactory->createSingleItemGetResponseData($singleItemArray);

        return new JsonResponse(
            $singleItemResponseData->toArray(),
            $singleItemResponseData->getStatusCode()
        );
    }
}