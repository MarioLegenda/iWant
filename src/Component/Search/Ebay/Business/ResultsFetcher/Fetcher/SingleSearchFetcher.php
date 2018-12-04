<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Cache\UniqueIdentifierFactory;
use App\Component\Search\Ebay\Business\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Business\ResponseFetcher\ResponseFetcher;
use App\Component\Search\Ebay\Model\Request\InternalSearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;
use App\Doctrine\Entity\SearchCache;
use App\Library\Infrastructure\Helper\TypedArray;
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
     * ResultsFetcher constructor.
     * @param ResponseFetcher $responseFetcher
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param YandexTranslationCenter $yandexTranslationCenter
     */
    public function __construct(
        ResponseFetcher $responseFetcher,
        YandexTranslationCenter $yandexTranslationCenter,
        SearchResponseCacheImplementation $searchResponseCacheImplementation
    ) {
        $this->responseFetcher = $responseFetcher;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->yandexTranslationCenter = $yandexTranslationCenter;
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
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     * @return SearchModelInterface
     */
    private function translateKeywordsToEnglishIfRequired(SearchModelInterface $model): SearchModelInterface
    {
        /** @var Language $language */
        $language = $this->yandexTranslationCenter->detectLanguage($model->getKeyword());

        if ($language->getEntry() === 'en') {
            return $model;
        }

        if ($language->getEntry() !== 'en') {
            /** @var Translation $translation */
            $translation = $this->yandexTranslationCenter->translate('en', $model->getKeyword());

            /** @var InternalSearchModel $internalSearchModel */
            $internalSearchModel = SearchModel::createInternalSearchModelFromSearchModel($model);

            $internalSearchModel->setKeyword($translation->getEntry());

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