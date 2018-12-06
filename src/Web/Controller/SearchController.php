<?php

namespace App\Web\Controller;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Cache\UniqueIdentifierFactory;
use App\Component\Search\Ebay\Business\SearchModelValidator;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;
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
     * @param SearchModelInterface|SearchModel $model
     * @param SearchComponent $searchComponent
     * @return JsonResponse
     */
    public function prepareProducts(
        SearchModelInterface $model,
        SearchComponent $searchComponent
    ): JsonResponse {
        $searchComponent->saveProducts($model);
        /** @var ApiResponseData $apiResponseData */
        $apiResponseData = $this->apiResponseDataFactory->createPreparedProductsResponseData([
            'uniqueName' => UniqueIdentifierFactory::createIdentifier($model),
        ]);

        $response = new JsonResponse(
            $apiResponseData->toArray(),
            $apiResponseData->getStatusCode()
        );

        return $response;
    }
    /**
     * @param SearchModelInterface|SearchModel $model
     * @param SearchComponent $searchComponent
     * @return Response
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getProducts(
        SearchModelInterface $model,
        SearchComponent $searchComponent
    ): Response {
        $listing = $searchComponent->getProductsPaginated($model);

        /** @var ApiResponseData $apiResponseData */
        $apiResponseData = $this->apiResponseDataFactory->createSearchListingResponseData([
            'siteInformation' => GlobalIdInformation::instance()->getTotalInformation($model->getGlobalId()),
            'items' => $listing,
        ]);

        $response = new Response(
            jsonEncodeWithFix($apiResponseData->toArray()),
            $apiResponseData->getStatusCode()
        );

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     * @param SearchModelInterface|SearchModel $model
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param UrlGeneratorInterface $router
     * @return JsonResponse
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getProductsListingOptions(
        SearchModelInterface $model,
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        UrlGeneratorInterface $router
    ) {
        $identifier = UniqueIdentifierFactory::createIdentifier($model);

        $isCached = $searchResponseCacheImplementation->isStored($identifier);
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