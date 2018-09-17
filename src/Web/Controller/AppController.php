<?php

namespace App\Web\Controller;

use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\App\Presentation\Model\SingleItemRequestModel;
use App\Doctrine\Entity\SingleProductItem;
use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppController
{
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * AppController constructor.
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        ApiSDK $apiSDK
    ) {
        $this->apiSdk = $apiSDK;
    }
    /**
     * @param SingleItemRequestModel $model
     * @param SingleItemEntryPoint $singleItemEntryPoint
     * @return JsonResponse
     */
    public function getSingleItem(
        SingleItemRequestModel $model,
        SingleItemEntryPoint $singleItemEntryPoint
    ): JsonResponse {
        /** @var SingleProductItem $singleItem */
        $singleItem = $singleItemEntryPoint->getSingleItem($model);

        /** @var ApiResponseData $responseData */
        $responseData = $this->apiSdk
            ->create($singleItem->toArray())
            ->method('GET')
            ->addMessage('A single item')
            ->isResource()
            ->setStatusCode(200)
            ->build();

        $response = new JsonResponse(
            $responseData->toArray(),
            $responseData->getStatusCode()
        );

        $response->headers->set('Cache-Control', 'no-cache');

        return $response;
    }
}