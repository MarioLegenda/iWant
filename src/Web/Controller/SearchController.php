<?php

namespace App\Web\Controller;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\SearchComponent;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Library\Http\Response\ApiResponseData;
use App\Web\Library\ApiResponseDataFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

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
    ): Response {
        $listing = $searchComponent->getProductsPaginated($model);

        /** @var ApiResponseData $apiResponseData */
        $apiResponseData = $this->apiResponseDataFactory->createSearchListingResponseData([
            'siteInformation' => GlobalIdInformation::instance()->getTotalInformation($model->getGlobalId()),
            'items' => $listing
        ]);

        $response = new Response(
            jsonEncodeWithFix($apiResponseData->toArray()),
            $apiResponseData->getStatusCode()
        );

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function getProductsListingOptions(
        SearchModel $model,
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        UrlGeneratorInterface $router
    ) {
        $isCached = $searchResponseCacheImplementation->isStored($model->getUniqueName());
        $route = ($isCached) ? $router->generate('app_get_products_by_global_id', [
            'searchData' => json_encode($model->toArray())
        ]) : $router->generate('app_post_products_by_global_id');

        $data = [
            'isCached' => $isCached,
            'siteInformation' => GlobalIdInformation::instance()->getTotalInformation($model->getGlobalId()),
            'method' => ($isCached) ? 'GET' : 'POST',
            'route' => $route,
        ];

        $apiResponseData = $this->apiResponseDataFactory->createOptionsResponseData($data);

        return new JsonResponse(
            $apiResponseData->toArray(),
            $apiResponseData->getStatusCode()
        );
    }
}