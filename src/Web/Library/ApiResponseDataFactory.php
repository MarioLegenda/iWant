<?php

namespace App\Web\Library;

use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Response\PreparedEbayResponse;
use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;

class ApiResponseDataFactory
{
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * ApiResponseDataFactory constructor.
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        ApiSDK $apiSDK
    ) {
        $this->apiSdk = $apiSDK;
    }
    /**
     * @param PreparedEbayResponse $preparedEbayResponse
     * @return ApiResponseData
     */
    public function createPreparedEbayResponseData(
        PreparedEbayResponse $preparedEbayResponse
    ): ApiResponseData {
        return $this->apiSdk
            ->create($preparedEbayResponse->toArray())
            ->method('POST')
            ->addMessage('Prepared ebay response for getting the items from the cache')
            ->isResource()
            ->setStatusCode(200)
            ->build();
    }
    /**
     * @return ApiResponseData
     */
    public function createErrorUniqueNameSearchResultsResponseData(): ApiResponseData
    {
        return $this->apiSdk
            ->create([])
            ->isError()
            ->method('GET')
            ->addMessage('Operation not allowed')
            ->isResource()
            ->setStatusCode(400)
            ->build();
    }
    /**
     * @param TypedArray $searchResults
     * @param Pagination $pagination
     * @return ApiResponseData
     */
    public function createSuccessUniqueNameSearchResultsResponseData(
        TypedArray $searchResults,
        Pagination $pagination
    ): ApiResponseData {
        return $this->apiSdk
            ->create($searchResults->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION))
            ->method('GET')
            ->addMessage('A list of paginated products')
            ->addPagination($pagination->getLimit(), $pagination->getPage())
            ->isCollection()
            ->setStatusCode(200)
            ->build();
    }
}