<?php

namespace App\Yandex\Business;

use App\Library\Http\Request;
use App\Reporting\Library\ReportsCollector;
use App\Symfony\Async\StaticAsyncHandler;
use App\Yandex\Business\Request\DetectLanguage;
use App\Yandex\Business\Request\GetSupportedLanguages;
use App\Yandex\Business\Request\TranslateText;
use App\Yandex\Library\Processor\ApiKeyProcessor;
use App\Yandex\Library\Processor\RequestBaseProcessor;
use App\Yandex\Library\Model\DetectLanguageResponse;
use App\Yandex\Library\Model\ErrorResponse;
use App\Yandex\Library\Model\SupportedLanguagesResponse;
use App\Yandex\Library\Model\TranslatedTextResponse;
use App\Yandex\Presentation\Model\YandexRequestModelInterface;
use App\Yandex\Source\FinderSource;

class Finder
{
    /**
     * @var FinderSource $finderSource
     */
    private $finderSource;
    /**
     * @var RequestBaseProcessor $requestBaseProcessor
     */
    private $requestBaseProcessor;
    /**
     * @var ApiKeyProcessor $apiKeyProcessor
     */
    private $apiKeyProcessor;
    /**
     * @var ReportsCollector $reportsCollector
     */
    private $reportsCollector;
    /**
     * Finder constructor.
     * @param FinderSource $finderSource
     * @param RequestBaseProcessor $requestBaseProcessor
     * @param ApiKeyProcessor $apiKeyProcessor
     * @param ReportsCollector $reportsCollector
     */
    public function __construct(
        FinderSource $finderSource,
        RequestBaseProcessor $requestBaseProcessor,
        ApiKeyProcessor $apiKeyProcessor,
        ReportsCollector $reportsCollector
    ) {
        $this->finderSource = $finderSource;
        $this->requestBaseProcessor = $requestBaseProcessor;
        $this->apiKeyProcessor = $apiKeyProcessor;
        $this->reportsCollector = $reportsCollector;
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return SupportedLanguagesResponse
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     */
    public function getSupportedLanguages(YandexRequestModelInterface $model): SupportedLanguagesResponse
    {
        $getSupportedLanguages = new GetSupportedLanguages(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var SupportedLanguagesResponse $response */
        return $this->finderSource->getSupportedLanguageModel($getSupportedLanguages->getRequest());
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return DetectLanguageResponse
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     */
    public function detectLanguage(YandexRequestModelInterface $model): DetectLanguageResponse
    {
        $detectLanguage = new DetectLanguage(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        return $this->finderSource->getDetectLanguageModel($detectLanguage->getRequest());
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return TranslatedTextResponse
     */
    public function translate(YandexRequestModelInterface $model): TranslatedTextResponse
    {
        $translateText = new TranslateText(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        return $this->finderSource->getTranslatedTextModel($translateText->getRequest());
    }
    /**
     * @param string $response
     * @return DetectLanguageResponse
     */
    private function createDetectLanguageResponse(string $response): DetectLanguageResponse
    {
        $responseArray = json_decode($response, true);

        $statusCode = $responseArray['code'];
        $lang = $responseArray['lang'];

        return new DetectLanguageResponse($statusCode, $lang);
    }
    /**
     * @param string $response
     * @return TranslatedTextResponse
     */
    private function createTranslationResponse(string $response): TranslatedTextResponse
    {
        $responseArray = json_decode($response, true);

        $statusCode = $responseArray['code'];
        $lang = $responseArray['lang'];
        $text = $responseArray['text'];

        return new TranslatedTextResponse($statusCode, $lang, $text);
    }
    /**
     * @param string $response
     * @return ErrorResponse
     */
    private function createErrorResponse(string $response): ErrorResponse
    {
        $responseArray = json_decode($response, true);

        return new ErrorResponse($responseArray);
    }
}