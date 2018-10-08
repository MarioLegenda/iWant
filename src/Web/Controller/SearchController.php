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
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getSearch(
        SearchModel $model,
        SearchComponent $searchComponent
    ) {
        /** @var SearchResponseModel[]|iterable $searchResponseModels */
        $searchResponseModels = $searchComponent->searchEbay($model);

        /** @var ApiResponseData $responseData */
        $responseData = $this->apiSdk
            ->create(apply_on_iterable($searchResponseModels, function(array $responseModels, string $globalId) {
                $normalizedResponse = [];

                /** @var SearchResponseModel $responseModel */
                foreach ($responseModels as $responseModel) {
                    $normalizedResponse[$globalId][] = $responseModel->toArray();
                }
            }))
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