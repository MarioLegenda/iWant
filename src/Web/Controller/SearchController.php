<?php

namespace App\Web\Controller;

use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Response\SearchResponseModel;
use App\Component\Search\SearchComponent;
use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController
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
     * @param SearchModel $model
     * @param SearchComponent $searchComponent
     * @return JsonResponse
     * @throws \App\Cache\Exception\CacheException
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getSearch(
        SearchModel $model,
        SearchComponent $searchComponent
    ): JsonResponse {
        /** @var iterable|array $products */
        $products = $searchComponent->searchEbay($model);
        /** @var ApiResponseData $responseData */
        $responseData = $this->apiSdk
            ->create($products)
            ->method('GET')
            ->addMessage('A search result')
            ->isCollection()
            ->addPagination($model->getPagination()->getLimit(), $model->getPagination()->getPage())
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