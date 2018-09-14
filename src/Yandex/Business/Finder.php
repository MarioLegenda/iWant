<?php

namespace App\Yandex\Business;

use App\Library\Http\Request;
use App\Yandex\Business\Request\DetectLanguage;
use App\Yandex\Business\Request\GetSupportedLanguages;
use App\Yandex\Business\Request\TranslateText;
use App\Yandex\Library\Processor\ApiKeyProcessor;
use App\Yandex\Library\Processor\RequestBaseProcessor;
use App\Yandex\Library\Response\DetectLanguageResponse;
use App\Yandex\Library\Response\ResponseModelInterface;
use App\Yandex\Library\Response\SupportedLanguagesResponse;
use App\Yandex\Library\Response\TranslatedTextResponse;
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
     * Finder constructor.
     * @param FinderSource $finderSource
     * @param RequestBaseProcessor $requestBaseProcessor
     * @param ApiKeyProcessor $apiKeyProcessor
     */
    public function __construct(
        FinderSource $finderSource,
        RequestBaseProcessor $requestBaseProcessor,
        ApiKeyProcessor $apiKeyProcessor
    ) {
        $this->finderSource = $finderSource;
        $this->requestBaseProcessor = $requestBaseProcessor;
        $this->apiKeyProcessor = $apiKeyProcessor;
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return ResponseModelInterface
     */
    public function getSupportedLanguages(YandexRequestModelInterface $model): ResponseModelInterface
    {
        $getSupportedLanguages = new GetSupportedLanguages(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $getSupportedLanguages->getRequest();

        $resource = $this->finderSource->getApiResource($request);

        return $this->createSupportedLanguagesResponse($resource);
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return ResponseModelInterface
     */
    public function detectLanguage(YandexRequestModelInterface $model): ResponseModelInterface
    {
        $detectLanguage = new DetectLanguage(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $detectLanguage->getRequest();

        $resource = $this->finderSource->getApiResource($request);

        return $this->createDetectLanguageResponse($resource);
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return ResponseModelInterface
     */
    public function translate(YandexRequestModelInterface $model): ResponseModelInterface
    {
        $translateText = new TranslateText(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $translateText->getRequest();

        $resource = $this->finderSource->getApiResource($request);

        return $this->createTranslationResponse($resource);
    }
    /**
     * @param string $response
     * @return SupportedLanguagesResponse
     */
    private function createSupportedLanguagesResponse(string $response): SupportedLanguagesResponse
    {
        $responseArray = json_decode($response, true);

        return new SupportedLanguagesResponse($responseArray['langs']);
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
}