<?php

namespace App\Web\Library;

use App\App\Presentation\Model\Response\SingleItemOptionsResponse;
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
     * @return ApiResponseData
     */
    public function create404Response(): ApiResponseData
    {
        return $this->apiSdk
            ->create([])
            ->method('GET')
            ->addMessage('Resource not found')
            ->isResource()
            ->setStatusCode(404)
            ->build();
    }
    /**
     * @param array $productsList
     * @return ApiResponseData
     */
    public function createSearchedProductResponseData(
        array $productsList
    ): ApiResponseData {
        return $this->apiSdk
            ->create($productsList)
            ->method('GET')
            ->addMessage('List of searched products')
            ->isCollection()
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
    /**
     * @param array $resource
     * @return ApiResponseData
     */
    public function createOptionsResponseData(
        array $resource
    ): ApiResponseData {
        return $this->apiSdk
            ->create($resource)
            ->method('OPTIONS')
            ->addMessage('Options for requesting item data')
            ->isResource()
            ->setStatusCode(200)
            ->build();
    }
    /**
     * @param array $resource
     * @return ApiResponseData
     */
    public function createSingleItemPutResponseData(
        array $resource
    ): ApiResponseData {
        return $this->apiSdk
            ->create($resource)
            ->method('PUT')
            ->addMessage('Created a single item')
            ->isResource()
            ->setStatusCode(201)
            ->build();
    }
    /**
     * @param array $resource
     * @return ApiResponseData
     */
    public function createSingleItemGetResponseData(
        array $resource
    ) {
        return $this->apiSdk
            ->create($resource)
            ->method('GET')
            ->addMessage('Fetched a single item')
            ->isResource()
            ->setStatusCode(200)
            ->build();
    }
}