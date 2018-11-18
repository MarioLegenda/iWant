<?php

namespace App\Web\Controller;

use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\SearchComponent;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Library\Http\Response\ApiResponseData;
use App\Web\Library\ApiResponseDataFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController
{
    /**
     * @var ApiResponseDataFactory $apiResponseDataFactory
     */
    private $apiResponseDataFactory;
    /**
     * SearchController constructor.
     * @param ApiResponseDataFactory $apiResponseDataFactory
     */
    public function __construct(
        ApiResponseDataFactory $apiResponseDataFactory
    ) {
        $this->apiResponseDataFactory = $apiResponseDataFactory;
    }
    /**
     * @param SearchModel $model
     * @param SearchComponent $searchComponent
     * @return JsonResponse
     */
    public function prepareProducts(
        SearchModel $model,
        SearchComponent $searchComponent
    ): JsonResponse {
        $searchComponent->saveProducts($model);
        /** @var ApiResponseData $apiResponseData */
        $apiResponseData = $this->apiResponseDataFactory->createPreparedProductsResponseData([
            'uniqueName' => $model->getUniqueName(),
        ]);

        $response = new JsonResponse(
            $apiResponseData->toArray(),
            $apiResponseData->getStatusCode()
        );

        return $response;
    }
    /**
     * @param SearchModel $model
     * @param SearchComponent $searchComponent
     * @return JsonResponse
     */
    public function getProducts(
        SearchModel $model,
        SearchComponent $searchComponent
    ): JsonResponse {
        $listing = $searchComponent->getProductsPaginated($model);

        /** @var ApiResponseData $apiResponseData */
        $apiResponseData = $this->apiResponseDataFactory->createSearchListingResponseData([
            'siteInformation' => GlobalIdInformation::instance()->getTotalInformation($model->getGlobalId()),
            'items' => $listing
        ]);

        $response = new JsonResponse(
            $apiResponseData->toArray(),
            $apiResponseData->getStatusCode()
        );

        return $response;
    }
}