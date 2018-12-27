<?php

namespace App\Web\Controller;

use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Library\Http\Response\ApiResponseData;
use App\Web\Library\ApiResponseDataFactory;
use App\Web\Library\ResponseEnvironmentHandler;
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
     * @param SingleItemRequestModel $model
     * @param SingleItemEntryPoint $singleItemEntryPoint
     * @param ResponseEnvironmentHandler $responseEnvironmentHandler
     * @return JsonResponse
     * @throws \App\Cache\Exception\CacheException
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getSingleItem(
        SingleItemRequestModel $model,
        SingleItemEntryPoint $singleItemEntryPoint,
        ResponseEnvironmentHandler $responseEnvironmentHandler
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

        $response = new JsonResponse(
            $singleItemResponseData->toArray(),
            $singleItemResponseData->getStatusCode()
        );

        return $responseEnvironmentHandler->handleSingleItemCache($response);
    }
}