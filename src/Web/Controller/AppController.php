<?php

namespace App\Web\Controller;

use App\App\Presentation\EntryPoint\CountryEntryPoint;
use App\App\Presentation\EntryPoint\MarketplaceEntryPoint;
use App\App\Presentation\EntryPoint\NativeTaxonomyEntryPoint;
use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Entity\SingleProductItem;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Representation\ApplicationShopRepresentation;
use App\Library\Util\SlackImplementation;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;
use App\Web\Model\Request\ActivityMessage;
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
    /**
     * @param MarketplaceEntryPoint $marketplaceEntryPoint
     * @return JsonResponse
     */
    public function getMarketplaces(MarketplaceEntryPoint $marketplaceEntryPoint)
    {
        $marketplaces = $marketplaceEntryPoint->getMarketplaces();

        /** @var ApiResponseData $responseData */
        $responseData = $this->apiSdk
            ->create($marketplaces->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION))
            ->method('GET')
            ->addMessage('A list of marketplaces')
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
    /**
     * @param NativeTaxonomyEntryPoint $nativeTaxonomyEntryPoint
     * @return JsonResponse
     */
    public function getNativeTaxonomies(NativeTaxonomyEntryPoint $nativeTaxonomyEntryPoint)
    {
        $taxonomies = $nativeTaxonomyEntryPoint->getNativeTaxonomies();

        /** @var ApiResponseData $responseData */
        $responseData = $this->apiSdk
            ->create($taxonomies->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION))
            ->method('GET')
            ->addMessage('A list of marketplaces')
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
    /**
     * @return JsonResponse
     */
    public function getGlobalIdsInformation(): JsonResponse
    {
        $globalIds = GlobalIdInformation::instance()->getAll();

        /** @var ApiResponseData $responseData */
        $responseData = $this->apiSdk
            ->create($globalIds)
            ->method('GET')
            ->addMessage('A list of all ebay global ids')
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
    /**
     * @param ActivityMessage $model
     * @param SlackImplementation $slackImplementation
     * @return JsonResponse
     * @throws \Http\Client\Exception
     */
    public function postActivity(
        ActivityMessage $model,
        SlackImplementation $slackImplementation
    ) {
        $slackImplementation
            ->sendMessageToChannel(
                '#app_activity',
                json_encode($model->toArray())
            );

        return new JsonResponse(
            200
        );
    }
}