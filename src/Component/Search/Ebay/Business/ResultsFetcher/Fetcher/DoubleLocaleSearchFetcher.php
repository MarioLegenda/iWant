<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Cache\UniqueIdentifierFactory;
use App\Component\Search\Ebay\Business\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Business\ResponseFetcher\ResponseFetcher;
use App\Component\Search\Ebay\Model\Request\InternalSearchModel;
use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;
use App\Library\Representation\LanguageTranslationsRepresentation;
use App\Library\Util\Util;
use App\Translation\Model\Language;
use App\Translation\YandexTranslationCenter;

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
     * @var SingleSearchFetcher $singleSearchFetcher
     */
    private $singleSearchFetcher;
    /**
     * @var YandexTranslationCenter $yandexTranslationCenter
     */
    private $yandexTranslationCenter;
    /**
     * ResultsFetcher constructor.
     * @param ResponseFetcher $responseFetcher
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param LanguageTranslationsRepresentation $languageTranslationsRepresentation
     * @param SingleSearchFetcher $singleSearchFetcher
     * @param YandexTranslationCenter $yandexTranslationCenter
     */
    public function __construct(
        SingleSearchFetcher $singleSearchFetcher,
        ResponseFetcher $responseFetcher,
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        LanguageTranslationsRepresentation $languageTranslationsRepresentation,
        YandexTranslationCenter $yandexTranslationCenter
    ) {
        $this->responseFetcher = $responseFetcher;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->languageTranslationRepresentation = $languageTranslationsRepresentation;
        $this->singleSearchFetcher = $singleSearchFetcher;
        $this->yandexTranslationCenter = $yandexTranslationCenter;
    }

    /**
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     * @param array $replacements
     * @return array
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getResults(SearchModelInterface $model, array $replacements = []): array
    {
        $identifier = UniqueIdentifierFactory::createIdentifier($model);

        if ($this->searchResponseCacheImplementation->isStored($identifier)) {
            $searchCache = $this->searchResponseCacheImplementation->getStored($identifier);

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

        $usedLanguage = $this->yandexTranslationCenter->detectLanguage((string) $keyword);

        $siteLocaleTranslatedKeyword = $this->yandexTranslationCenter->translateFromTo(
            new Language($usedLanguage),
            new Language($siteLocale),
            (string) $keyword
        );

        $mainLocaleTranslatedKeyword = $this->yandexTranslationCenter->translateFromTo(
            new Language($usedLanguage),
            new Language($mainLocale),
            (string) $keyword
        );

        $siteLocaleResults = $this->getResultsWithTranslatedKeyword($model, (string) $siteLocaleTranslatedKeyword);
        $mainLocaleResults = $this->getResultsWithTranslatedKeyword($model, (string) $mainLocaleTranslatedKeyword);

        $results = $this->arrangeResults($siteLocaleResults, $mainLocaleResults);

        $this->searchResponseCacheImplementation->store(
            $identifier,
            jsonEncodeWithFix($results),
            count($results)
        );

        return $results;
    }

    /**
     * @param FilterApplierInterface $filterApplier
     */
    public function addFilterApplier(FilterApplierInterface $filterApplier)
    {
        $this->filterApplier = $filterApplier;
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

        $filterByItemId = function($results): array {
            $duplicates = [];
            $resultsGen = Util::createGenerator($results);
            $finalResults = [];

            foreach ($resultsGen as $entry) {
                $item = $entry['item'];

                if (in_array($item['itemId'], $duplicates) === true) {
                    continue;
                }

                $finalResults[] = $item;

                $duplicates[] = $item['itemId'];
            }

            return $finalResults;
        };

        if (count($siteLocaleResults) === count($mainLocaleResults)) {
            $finalResults = $arrangeFinalResultFunction($siteLocaleResults, $mainLocaleResults);

            return $filterByItemId($finalResults);
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

        $finalResults = $arrangeFinalResultFunction($mainIterationArray, $secondaryIterationArray);

        return $filterByItemId($finalResults);
    }
    /**
     * @param SearchModel $model
     * @param string $keyword
     * @return iterable
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     */
    private function getResultsWithTranslatedKeyword(
        SearchModel $model,
        string $keyword
    ) {
        /** @var InternalSearchModel $internalSearchModel */
        $internalSearchModel = SearchModel::createInternalSearchModelFromSearchModel($model);
        $internalPagination = new Pagination(40, $model->getInternalPagination()->getPage());

        $internalSearchModel->setKeyword(new Language($keyword));
        $internalSearchModel->setInternalPagination($internalPagination);

        return $this->singleSearchFetcher->getFreshResults($internalSearchModel);
    }
}