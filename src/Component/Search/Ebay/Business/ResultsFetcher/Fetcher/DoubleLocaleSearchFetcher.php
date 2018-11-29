<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\ResponseFetcher\ResponseFetcher;
use App\Component\Search\Ebay\Business\ResultsFetcher\FetcherInterface;
use App\Component\Search\Ebay\Business\ResultsFetcher\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Library\Representation\LanguageTranslationsRepresentation;
use App\Yandex\Business\Request\DetectLanguage;
use App\Yandex\Library\Request\RequestFactory;
use App\Yandex\Library\Response\DetectLanguageResponse;
use App\Yandex\Library\Response\TranslatedTextResponse;
use App\Yandex\Presentation\EntryPoint\YandexEntryPoint;
use App\Yandex\Presentation\Model\YandexRequestModel;

class DoubleLocaleSearchFetcher implements FetcherInterface
{
    /**
     * @var ResponseFetcher $responseFetcher
     */
    private $responseFetcher;
    /**
     * @var SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    private $searchResponseCacheImplementation;
    /**
     * @var LanguageTranslationsRepresentation $languageTranslationRepresentation
     */
    private $languageTranslationRepresentation;
    /**
     * @var FilterApplierInterface $filterApplier
     */
    private $filterApplier;
    /**
     * @var YandexEntryPoint $yandexEntryPoint
     */
    private $yandexEntryPoint;
    /**
     * ResultsFetcher constructor.
     * @param ResponseFetcher $responseFetcher
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param LanguageTranslationsRepresentation $languageTranslationsRepresentation
     * @param YandexEntryPoint $yandexEntryPoint
     */
    public function __construct(
        ResponseFetcher $responseFetcher,
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        LanguageTranslationsRepresentation $languageTranslationsRepresentation,
        YandexEntryPoint $yandexEntryPoint
    ) {
        $this->responseFetcher = $responseFetcher;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->languageTranslationRepresentation = $languageTranslationsRepresentation;
        $this->yandexEntryPoint = $yandexEntryPoint;
    }

    public function getResults(SearchModel $model, array $replacements = []): array
    {
        /*
         * 1. Detect the keyword language
         * 2. Translate from that language to site locale
         * 3. Translate from that language to main locale
         * 4. Do search by main locale
         * 5. Do search by site locale
         */
        $globalId = $model->getGlobalId();

        if ($this->languageTranslationRepresentation->areLocalesIdentical($globalId)) {
            // perform a single search
        }

        $mainLocale = $this->languageTranslationRepresentation->getMainLocaleByGlobalId($globalId);
        $siteLocale = $this->languageTranslationRepresentation->getLocaleByGlobalId($globalId);

        $keyword = $model->getKeyword();

        $usedLanguage = $this->detectKeywordsLanguage($keyword);

        $siteLocaleTranslatedKeyword = $this->translateKeyword($keyword, sprintf('%s-%s', $usedLanguage, $siteLocale));
        $mainLocaleTranslatedKeyword = $this->translateKeyword($keyword, sprintf('%s-%s', $usedLanguage, $mainLocale));
    }
    /**
     * @param FilterApplierInterface $filterApplier
     */
    public function addFilterApplier(FilterApplierInterface $filterApplier)
    {
        $this->filterApplier = $filterApplier;
    }

    private function detectKeywordsLanguage(string $keywords): string
    {
        /** @var YandexRequestModel $detectLanguageRequest */
        $detectLanguageRequest = RequestFactory::createDetectLanguageRequestModel($keywords);

        /** @var DetectLanguageResponse $response */
        $response = $this->yandexEntryPoint->detectLanguage($detectLanguageRequest);

        return $response->getLang();
    }

    private function translateKeyword(string $keywords, string $locale): string
    {
        /** @var YandexRequestModel $detectLanguageRequest */
        $detectLanguageRequest = RequestFactory::createTranslateRequestModel($keywords, $locale);

        /** @var TranslatedTextResponse $response */
        $response = $this->yandexEntryPoint->translate($detectLanguageRequest);

        return $response->getText();
    }
}