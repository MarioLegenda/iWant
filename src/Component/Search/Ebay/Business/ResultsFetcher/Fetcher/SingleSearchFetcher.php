<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher;

use App\Cache\Implementation\KeywordTranslationCacheImplementation;
use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Cache\UniqueIdentifierFactory;
use App\Component\Search\Ebay\Business\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Business\ResponseFetcher\ResponseFetcher;
use App\Component\Search\Ebay\Model\Request\InternalSearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;
use App\Doctrine\Entity\KeywordTranslationCache;
use App\Doctrine\Entity\SearchCache;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Representation\MainLocaleRepresentation;
use App\Library\Util\TypedRecursion;
use App\Translation\Model\Language;
use App\Translation\Model\Translation;
use App\Translation\YandexTranslationCenter;

class SingleSearchFetcher implements FetcherInterface
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
     * @var FilterApplierInterface $filterApplier
     */
    private $filterApplier;
    /**
     * @var YandexTranslationCenter $yandexTranslationCenter
     */
    private $yandexTranslationCenter;
    /**
     * @var MainLocaleRepresentation $mainLocaleRepresentation
     */
    private $mainLocaleRepresentation;
    /**
     * @var KeywordTranslationCacheImplementation $keywordTranslationCacheImplementation
     */
    private $keywordTranslationCacheImplementation;
    /**
     * ResultsFetcher constructor.
     * @param ResponseFetcher $responseFetcher
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param YandexTranslationCenter $yandexTranslationCenter
     * @param MainLocaleRepresentation $mainLocaleRepresentation
     * @param KeywordTranslationCacheImplementation $keywordTranslationCacheImplementation
     */
    public function __construct(
        ResponseFetcher $responseFetcher,
        YandexTranslationCenter $yandexTranslationCenter,
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        MainLocaleRepresentation $mainLocaleRepresentation,
        KeywordTranslationCacheImplementation $keywordTranslationCacheImplementation
    ) {
        $this->responseFetcher = $responseFetcher;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->yandexTranslationCenter = $yandexTranslationCenter;
        $this->mainLocaleRepresentation = $mainLocaleRepresentation;
        $this->keywordTranslationCacheImplementation = $keywordTranslationCacheImplementation;
    }
    /**
     * @param SearchModelInterface|SearchModel $model
     * @param array $replacementData
     * @return iterable
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getResults(SearchModelInterface $model, array $replacementData = []): iterable
    {
        $model = $this->translateKeywordsToEnglishIfRequired($model);

        $identifier = UniqueIdentifierFactory::createIdentifier($model);

        if ($this->searchResponseCacheImplementation->isStored($identifier)) {
            /** @var SearchCache $presentationResults */
            $presentationResults = $this->searchResponseCacheImplementation->getStored($identifier);

            $presentationResultsArray =  json_decode($presentationResults->getProductsResponse(), true);

            if ($this->filterApplier instanceof FilterApplierInterface) {
                return $this->filterApplier->apply($presentationResultsArray);
            }

            return $presentationResultsArray;
        }

        $presentationResultsArray = $this->getFreshResults($model, $identifier);

        $this->searchResponseCacheImplementation->store(
            $identifier,
            jsonEncodeWithFix($presentationResultsArray)
        );

        if ($this->filterApplier instanceof FilterApplierInterface) {
            return $this->filterApplier->apply($presentationResultsArray);
        }

        return $presentationResultsArray;
    }
    /**
     * @param FilterApplierInterface $filterApplier
     */
    public function addFilterApplier(FilterApplierInterface $filterApplier)
    {
        $this->filterApplier = $filterApplier;
    }
    /**
     * @param SearchModelInterface|SearchModel $model
     * @param string|null $identifier
     * @return iterable
     */
    public function getFreshResults(SearchModelInterface $model, string $identifier = null)
    {
        /** @var TypedArray $presentationResults */
        $presentationResults = $this->responseFetcher->getResponse($model, $identifier);

        $presentationResultsArray = $presentationResults->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION);

        return $presentationResultsArray;
    }

    /**
     * @param SearchModelInterface|SearchModel $model
     * @return SearchModelInterface|InternalSearchModel
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function translateKeywordsToEnglishIfRequired(SearchModelInterface $model): SearchModelInterface
    {
        if (
            $this->keywordTranslationCacheImplementation->isStored((string) $model->getKeyword()) OR
            $this->keywordTranslationCacheImplementation->isTranslation((string) $model->getKeyword())
        ) {
            $storedKeyword = $this->keywordTranslationCacheImplementation->getStored((string) $model->getKeyword());

            if ($storedKeyword instanceof KeywordTranslationCache) {
                /** @var InternalSearchModel $internalSearchModel */
                $internalSearchModel = SearchModel::createInternalSearchModelFromSearchModel($model);
                $internalSearchModel->setKeyword(new Language($storedKeyword->getTranslation()));

                return $internalSearchModel;
            }
        }

        /** @var Language $language */
        $language = $this->yandexTranslationCenter->detectLanguage((string) $model->getKeyword());

        if ($language->getEntry() === (string) $this->mainLocaleRepresentation) {
            $this->keywordTranslationCacheImplementation->upsert(
                (string) $model->getKeyword(),
                (string) $model->getKeyword()
            );

            return $model;
        }

        if ($language->getEntry() !== (string) $this->mainLocaleRepresentation) {
            /** @var Translation $translation */
            $translation = $this->yandexTranslationCenter->translateFromTo(
                $language,
                new Language((string) $this->mainLocaleRepresentation),
                $model->getKeyword()
            );

            /** @var InternalSearchModel $internalSearchModel */
            $internalSearchModel = SearchModel::createInternalSearchModelFromSearchModel($model);

            $internalSearchModel->setKeyword(new Language($translation->getEntry()));

            $this->keywordTranslationCacheImplementation->upsert(
                (string) $model->getKeyword(),
                $translation->getEntry()
            );

            return $internalSearchModel;
        }

        $message = sprintf(
            'An unhandled condition error. This part of the code should never been reached in %s::%s',
            get_class($this),
            __FUNCTION__
        );

        throw new \RuntimeException($message);
    }
}