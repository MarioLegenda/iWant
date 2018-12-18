<?php

namespace App\Yandex\Business;

use App\Yandex\Business\Request\DetectLanguage;
use App\Yandex\Business\Request\GetSupportedLanguages;
use App\Yandex\Business\Request\TranslateText;
use App\Yandex\Library\Processor\ApiKeyProcessor;
use App\Yandex\Library\Processor\RequestBaseProcessor;
use App\Yandex\Library\Model\DetectLanguageResponse;
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
     * @return SupportedLanguagesResponse
     * @throws \App\Yandex\Library\Exception\YandexException
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
     * @throws \App\Yandex\Library\Exception\YandexException
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
     * @throws \App\Yandex\Library\Exception\YandexException
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
}