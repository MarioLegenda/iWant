<?php

namespace App\Yandex\Presentation\EntryPoint;

use App\Yandex\Business\Finder;
use App\Yandex\Library\Model\DetectLanguageResponse;
use App\Yandex\Library\Model\ResponseModelInterface;
use App\Yandex\Library\Model\SupportedLanguagesResponse;
use App\Yandex\Library\Model\TranslatedTextResponse;
use App\Yandex\Presentation\Model\YandexRequestModelInterface;

class YandexEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * YandexEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return SupportedLanguagesResponse
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     */
    public function getSupportedLanguages(YandexRequestModelInterface $model): SupportedLanguagesResponse
    {
        return $this->finder->getSupportedLanguages($model);
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return DetectLanguageResponse
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     */
    public function detectLanguage(YandexRequestModelInterface $model): DetectLanguageResponse
    {
        return $this->finder->detectLanguage($model);
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return TranslatedTextResponse
     */
    public function translate(YandexRequestModelInterface $model): TranslatedTextResponse
    {
        return $this->finder->translate($model);
    }
}