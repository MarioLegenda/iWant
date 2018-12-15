<?php

namespace App\Yandex\Source;

use App\Library\Http\Request;
use App\Library\Http\Response\ApiSDK;
use App\Library\Http\Response\ResponseModelInterface;
use App\Library\Util\Environment;
use App\Symfony\Async\StaticAsyncHandler;
use App\Symfony\Exception\ExceptionType;
use App\Symfony\Exception\ExternalApiNativeException;
use App\Symfony\Exception\NetworkExceptionBody;
use App\Yandex\Library\Model\DetectLanguageResponse;
use App\Yandex\Library\Model\ErrorResponse;
use App\Yandex\Library\Model\SupportedLanguagesResponse;
use App\Yandex\Library\Model\TranslatedTextResponse;
use App\Yandex\Source\Repository\Repository;
use Psr\Log\LoggerInterface;

class FinderSource
{
    /**
     * @var Repository $repository
     */
    private $repository;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * @var Environment $environment
     */
    private $environment;
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * FinderSource constructor.
     * @param Repository $repository
     * @param LoggerInterface $logger
     * @param Environment $environment
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        Repository $repository,
        LoggerInterface $logger,
        Environment $environment,
        ApiSDK $apiSDK
    ) {
        $this->repository = $repository;
        $this->environment = $environment;
        $this->logger = $logger;
        $this->apiSdk = $apiSDK;
    }
    /**
     * @param Request $request
     * @return DetectLanguageResponse
     * @throws ExternalApiNativeException
     */
    public function getDetectLanguageModel(Request $request): DetectLanguageResponse
    {
        $response = $this->repository->getResource($request);

        $this->handleException($response);

        $responseArray = $response->getBodyArrayIfJson();

        return new DetectLanguageResponse(
            (int) $responseArray['code'],
            $responseArray['lang']
        );
    }
    /**
     * @param Request $request
     * @return SupportedLanguagesResponse
     * @throws ExternalApiNativeException
     */
    public function getSupportedLanguageModel(Request $request): SupportedLanguagesResponse
    {
        $response = $this->repository->getResource($request);

        $this->handleException($response);

        $responseArray = $response->getBodyArrayIfJson();

        return new SupportedLanguagesResponse($responseArray['langs']);
    }
    /**
     * @param Request $request
     * @return TranslatedTextResponse
     * @throws ExternalApiNativeException
     */
    public function getTranslatedTextModel(Request $request)
    {
        $response = $this->repository->getResource($request);

        $this->handleException($response);

        $responseArray = $response->getBodyArrayIfJson();

        $statusCode = $responseArray['code'];
        $lang = $responseArray['lang'];
        $text = $responseArray['text'];

        return new TranslatedTextResponse($statusCode, $lang, $text);
    }
    /**
     * @param ResponseModelInterface $response
     * @throws ExternalApiNativeException
     */
    private function handleException(ResponseModelInterface $response): void
    {
        if ($response->is400Range()) {
            $errorResponse = new ErrorResponse($response->getBodyArrayIfJson());

            if ($response->getStatusCode() === 401) {
                $errorResponse->invalidApiKey();
            }

            if ($response->getStatusCode() === 402) {
                $errorResponse->blockedApiKey();
            }

            if ($response->getStatusCode() === 404) {
                $errorResponse->dailyLimitExceeded();
            }

            if ($response->is500Range()) {
                $errorResponse->unhandledError();
            }

            $this->buildException($errorResponse, $response);
        }
    }
    /**
     * @param ErrorResponse $response
     * @param ResponseModelInterface $responseModel
     * @throws ExternalApiNativeException
     */
    private function buildException(
        ErrorResponse $response,
        ResponseModelInterface $responseModel
    ) {

        $logMessage = sprintf(
            'An exception was caught with message: %s',
            $response->getMessage()
        );

        $this->logger->critical($logMessage);

        $builtData = $this->apiSdk
            ->create([
                'type' => ExceptionType::HTTP_EXCEPTION,
                'message' => $logMessage,
                'url' => $responseModel->getRequest()->getBaseUrl(),
                'external_api' => 'yandex',
                'environment' => (string) $this->environment,
            ])
            ->isError()
            ->method('GET')
            ->setStatusCode(503)
            ->isResource()
            ->build();

        $networkExceptionBody = new NetworkExceptionBody($responseModel->getStatusCode(), $builtData->toArray());

        throw new ExternalApiNativeException($networkExceptionBody);
    }
}