<?php

namespace App\Web\Controller;

use App\App\Presentation\EntryPoint\CountryEntryPoint;
use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Doctrine\Entity\SingleProductItem;
use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppController
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
     * @param SingleItemRequestModel $model
     * @param SingleItemEntryPoint $singleItemEntryPoint
     * @return JsonResponse
     */
    public function getSingleItem(
        SingleItemRequestModel $model,
        SingleItemEntryPoint $singleItemEntryPoint
    ): JsonResponse {
        /** @var SingleProductItem $singleItem */
        $singleItem = $singleItemEntryPoint->getSingleItem($model);

        /** @var ApiResponseData $responseData */
        $responseData = $this->apiSdk
            ->create($singleItem->toArray())
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
    /**
     * @param CountryEntryPoint $countryEntryPoint
     * @return JsonResponse
     */
    public function getCountries(
        CountryEntryPoint $countryEntryPoint
    ) {
        /** @var TypedArray $countries */
        $countries = $countryEntryPoint->getCountries();

        /** @var ApiResponseData $responseData */
        $responseData = $this->apiSdk
            ->create($countries->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION))
            ->method('GET')
            ->addMessage('A list of countries')
            ->isCollection()
            ->setStatusCode(200)
            ->build();

        $response = new JsonResponse(
            $responseData->toArray(),
            $responseData->getStatusCode()
        );

        $response->setCache([
            'max_age' => 60 * 60 * 24 * 30
        ]);

        return $response;
    }
}