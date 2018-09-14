<?php

namespace App\Yandex\Presentation\EntryPoint;

use App\Yandex\Business\Finder;
use App\Yandex\Library\Response\ResponseModelInterface;
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
     * @return ResponseModelInterface
     */
    public function getSupportedLanguages(YandexRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->getSupportedLanguages($model);
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return ResponseModelInterface
     */
    public function detectLanguage(YandexRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->detectLanguage($model);
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return ResponseModelInterface
     */
    public function translate(YandexRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->translate($model);
    }
}