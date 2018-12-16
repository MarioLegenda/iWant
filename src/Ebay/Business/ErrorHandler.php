<?php

namespace App\Ebay\Business;

use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ResponseModelInterface;
use App\Library\Http\Response\ApiSDK;
use App\Library\Util\Environment;
use App\Symfony\Exception\ExceptionType;
use App\Symfony\Exception\ExternalApiNativeException;
use App\Symfony\Exception\NetworkExceptionBody;
use App\Ebay\Library\Response\ResponseModelInterface as EbayResponseModelInterface;

class ErrorHandler
{
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * @var Environment $environment
     */
    private $environment;
    /**
     * ErrorHandler constructor.
     * @param ApiSDK $apiSDK
     * @param Environment $environment
     */
    public function __construct(
        ApiSDK $apiSDK,
        Environment $environment
    ) {
        $this->apiSdk = $apiSDK;
        $this->environment = $environment;
    }
    /**
     * @param EbayResponseModelInterface|XmlFindingApiResponseModel $response
     * @param ResponseModelInterface $responseModel
     * @throws ExternalApiNativeException
     */
    public function handleError(
        EbayResponseModelInterface $response,
        ResponseModelInterface $responseModel
    ) {
        if ($response instanceof EbayResponseModelInterface) {
            $logMessage = sprintf(
                'An error occurred in the eBay Finding API with response %s',
                jsonEncodeWithFix($response->getErrors()->toArray())
            );

            /** @var ApiResponseData $builtData */
            $builtData = $this->apiSdk
                ->create([
                    'type' => ExceptionType::HTTP_EXCEPTION,
                    'message' => $logMessage,
                    'url' => $responseModel->getRequest()->getBaseUrl(),
                    'external_api' => 'eBay Finding API',
                    'environment' => (string) $this->environment,
                ])
                ->isError()
                ->method('GET')
                ->setStatusCode(503)
                ->isResource()
                ->build();

            $this->throwException($responseModel, $builtData);
        } else {
            $logMessage = sprintf(
                'An error occurred in the eBay Finding API with response %s',
                jsonEncodeWithFix($response->getErrors()->toArray())
            );

            /** @var ApiResponseData $builtData */
            $builtData = $this->apiSdk
                ->create([
                    'type' => ExceptionType::HTTP_EXCEPTION,
                    'message' => $logMessage,
                    'url' => $responseModel->getRequest()->getBaseUrl(),
                    'external_api' => 'eBay Other API',
                    'environment' => (string) $this->environment,
                ])
                ->isError()
                ->method('GET')
                ->setStatusCode(503)
                ->isResource()
                ->build();

            $this->throwException($responseModel, $builtData);
        }
    }
    /**
     * @param ResponseModelInterface $responseModel
     * @param ApiResponseData $apiResponseData
     * @throws ExternalApiNativeException
     */
    private function throwException(
        ResponseModelInterface $responseModel,
        ApiResponseData $apiResponseData
    ) {
        $networkExceptionBody = new NetworkExceptionBody(
            $responseModel->getStatusCode(),
            $apiResponseData->toArray()
        );

        throw new ExternalApiNativeException($networkExceptionBody);
    }
}