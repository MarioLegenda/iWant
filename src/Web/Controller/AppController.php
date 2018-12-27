<?php

namespace App\Web\Controller;

use App\App\Presentation\EntryPoint\CountryEntryPoint;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Environment;
use App\Library\Util\SlackImplementation;
use App\Library\Util\TypedRecursion;
use App\Web\Library\ResponseEnvironmentHandler;
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
     * @param CountryEntryPoint $countryEntryPoint
     * @param ResponseEnvironmentHandler $responseEnvironmentHandler
     * @return JsonResponse
     */
    public function getCountries(
        CountryEntryPoint $countryEntryPoint,
        ResponseEnvironmentHandler $responseEnvironmentHandler
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

        return $responseEnvironmentHandler->handleGetCountries($response);
    }
    /**
     * @param ResponseEnvironmentHandler $responseEnvironmentHandler
     * @return JsonResponse
     */
    public function getGlobalIdsInformation(
        ResponseEnvironmentHandler $responseEnvironmentHandler
    ): JsonResponse {
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

        return $responseEnvironmentHandler->handleGetGlobalIdsInformation($response);
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
                'Recording app activity trough external HTTP. Its probably the browser',
                '#app_activity',
                json_encode($model->toArray())
            );

        return new JsonResponse(
            200
        );
    }
}