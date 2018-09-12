<?php

namespace App\Web\Controller;

use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use App\Web\EntryPoint\TodayProductsEntryPoint;
use App\Web\Model\Request\TodayProductRequestModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TodayProductsController
{
    /**
     * @var TodayProductsEntryPoint $todayProductsEntryPoint
     */
    private $todayProductsEntryPoint;
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * TodayProductsController constructor.
     * @param TodayProductsEntryPoint $todayProductsEntryPoint
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        TodayProductsEntryPoint $todayProductsEntryPoint,
        ApiSDK $apiSDK
    ) {
        $this->todayProductsEntryPoint = $todayProductsEntryPoint;
        $this->apiSdk = $apiSDK;
    }
    /**
     * @param TodayProductRequestModel $model
     * @return JsonResponse
     * @throws \App\Symfony\Exception\HttpException
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getTodaysProducts(TodayProductRequestModel $model): Response
    {
        $todaysProducts = $this->todayProductsEntryPoint->getTodaysProducts($model);

        /** @var ApiResponseData $responseData */
        $responseData = $this->apiSdk
            ->create($todaysProducts)
            ->method('GET')
            ->addMessage('Today\'s products list')
            ->isCollection()
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