<?php

namespace App\Web\Controller;

use App\Component\Search\Ebay\Model\Request\PreparedItemsSearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;
use App\Component\Search\SearchComponent;
use App\Library\Http\Response\ApiResponseData;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Environment;
use App\Web\Library\ApiResponseDataFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
     * @param EbaySearchModel $model
     * @param SearchComponent $searchComponent
     * @param Environment $environment
     * @return JsonResponse
     */
    public function getEbayProductsByGlobalId(
        EbaySearchModel $model,
        SearchComponent $searchComponent,
        Environment $environment
    ): JsonResponse {
        $preparedEbayResponse = $searchComponent->getEbayProductsByGlobalId($model);
        /** @var ApiResponseData $apiResponseData */
        $apiResponseData = $this->apiResponseDataFactory->createPreparedEbayResponseData($preparedEbayResponse);

        $response = new JsonResponse(
            $apiResponseData->toArray(),
            $apiResponseData->getStatusCode()
        );

        if ((string) $environment === 'dev') {
            $response->headers->set('Cache-Control', 'no-cache');
        } else if ((string) $environment === 'prod') {
            $response->setMaxAge(60 * 60 * 24);
        }

        return $response;
    }
}