<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\ResponseFetcher\ResponseFetcher;
use App\Component\Search\Ebay\Business\ResultsFetcher\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Model\Request\InternalSearchModel;
use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;
use App\Library\Representation\LanguageTranslationsRepresentation;
use App\Library\Util\Util;
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
     * @var SingleSearchFetcher $singleSearchFetcher
     */
    private $singleSearchFetcher;
    /**
     * ResultsFetcher constructor.
     * @param ResponseFetcher $responseFetcher
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param LanguageTranslationsRepresentation $languageTranslationsRepresentation
     * @param YandexEntryPoint $yandexEntryPoint
     * @param SingleSearchFetcher $singleSearchFetcher
     */
    public function __construct(
        SingleSearchFetcher $singleSearchFetcher,
        ResponseFetcher $responseFetcher,
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        LanguageTranslationsRepresentation $languageTranslationsRepresentation,
        YandexEntryPoint $yandexEntryPoint
    ) {
        $this->responseFetcher = $responseFetcher;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->languageTranslationRepresentation = $languageTranslationsRepresentation;
        $this->yandexEntryPoint = $yandexEntryPoint;
        $this->singleSearchFetcher = $singleSearchFetcher;
    }

    public function getResults(SearchModelInterface $model, array $replacements = []): array
    {
        $uniqueName = $model->getUniqueName();

        if ($this->searchResponseCacheImplementation->isStored($uniqueName)) {
            $searchCache = $this->searchResponseCacheImplementation->getStored($uniqueName);

            return json_decode($searchCache->getProductsResponse(), true);
        }
        /*
         * 1. Detect the keyword language
         * 2. Translate from that language to site locale
         * 3. Translate from that language to main locale
         * 4. Do search by main locale
         * 5. Do search by site locale
         */
        $globalId = $model->getGlobalId();

        $mainLocale = $this->languageTranslationRepresentation->getMainLocaleByGlobalId($globalId);
        $siteLocale = $this->languageTranslationRepresentation->getLocaleByGlobalId($globalId);

        $keyword = $model->getKeyword();

        $usedLanguage = $this->detectKeywordsLanguage($keyword);

        $siteLocaleTranslatedKeyword = $this->translateKeyword($keyword, sprintf('%s-%s', $usedLanguage, $siteLocale));
        $mainLocaleTranslatedKeyword = $this->translateKeyword($keyword, sprintf('%s-%s', $usedLanguage, $mainLocale));

        $siteLocaleResults = $this->getResultsWithTranslatedKeyword($model, $siteLocaleTranslatedKeyword);
        $mainLocaleResults = $this->getResultsWithTranslatedKeyword($model, $mainLocaleTranslatedKeyword);

        $results = $this->arrangeResults($siteLocaleResults, $mainLocaleResults);

        $this->searchResponseCacheImplementation->store(
            $uniqueName,
            jsonEncodeWithFix($results)
        );

        return $results;
    }
    /**
     * @param array $siteLocaleResults
     * @param array $mainLocaleResults
     * @return array
     */
    private function arrangeResults(
        array $siteLocaleResults,
        array $mainLocaleResults
    ) {
        $arrangeFinalResultFunction = function($mainIteration, array $additionalIteration): array {
            $mainIterationGen = Util::createGenerator($mainIteration);
            $finalResults = [];

            foreach ($mainIterationGen as $entry) {
                $item = $entry['item'];
                $key = $entry['key'];

                if (array_key_exists($key, $additionalIteration)) {
                    $finalResults[] = $item;
                }

                $finalResults[] = $item;
            }

            return $finalResults;
        };

        if (count($siteLocaleResults) === count($mainLocaleResults)) {
            return $arrangeFinalResultFunction($siteLocaleResults, $mainLocaleResults);
        }

        $mainIterationArray = null;
        $secondaryIterationArray = null;

        if (count($siteLocaleResults) > count($mainLocaleResults)) {
            $mainIterationArray = $siteLocaleResults;
            $secondaryIterationArray = $mainLocaleResults;
        } else {
            $mainIterationArray = $mainLocaleResults;
            $secondaryIterationArray = $siteLocaleResults;
        }

        return $arrangeFinalResultFunction($mainIterationArray, $secondaryIterationArray);
    }
    /**
     * @param FilterApplierInterface $filterApplier
     */
    public function addFilterApplier(FilterApplierInterface $filterApplier)
    {
        $this->filterApplier = $filterApplier;
    }

    private function getResultsWithTranslatedKeyword(
        SearchModel $model,
        string $keyword
    ) {
        /** @var InternalSearchModel $internalSearchModel */
        $internalSearchModel = SearchModel::createInternalSearchModelFromSearchModel($model);
        $internalPagination = new Pagination(40, $model->getInternalPagination()->getPage());

        $internalSearchModel->setKeyword($keyword);
        $internalSearchModel->setInternalPagination($internalPagination);

        return $this->singleSearchFetcher->getFreshResults($internalSearchModel);
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