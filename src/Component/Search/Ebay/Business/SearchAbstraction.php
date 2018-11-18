<?php

namespace App\Component\Search\Ebay\Business;

use App\Cache\Implementation\ItemTranslationCacheImplementation;
use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Response\Title;
use App\Doctrine\Entity\SearchCache;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;
use App\Translation\TranslationCenter;
use App\Component\Search\Ebay\Business\ResponseFetcher\ResponseFetcher;

/**
 * Class PreparedEbayResponseAbstraction
 * @package App\Component\Search\Ebay\Business
 *
 * ********************
 *
 * THIS IS JUST A FUCKING ABSTRACTION OVER THE SEARCH COMPONENT
 *
 * **********************
 */
class SearchAbstraction
{
    /**
     * @var ResponseFetcher $responseFetcher
     */
    private $responseFetcher;
    /**
     * @var TranslationCenter $translationCenter
     */
    private $translationCenter;
    /**
     * @var ItemTranslationCacheImplementation $itemTranslationCacheImplementation
     */
    private $itemTranslationCacheImplementation;
    /**
     * @var SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    private $searchResponseCacheImplementation;
    /**
     * @var PaginationHandler $paginationHandler
     */
    private $paginationHandler;
    /**
     * PreparedEbayResponseAbstraction constructor.
     * @param ResponseFetcher $responseFetcher
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param TranslationCenter $translationCenter
     * @param ItemTranslationCacheImplementation $itemTranslationCacheImplementation
     * @param PaginationHandler $paginationHandler
     */
    public function __construct(
        ResponseFetcher $responseFetcher,
        TranslationCenter $translationCenter,
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        ItemTranslationCacheImplementation $itemTranslationCacheImplementation,
        PaginationHandler $paginationHandler
    ) {
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->responseFetcher = $responseFetcher;
        $this->translationCenter = $translationCenter;
        $this->itemTranslationCacheImplementation = $itemTranslationCacheImplementation;
        $this->paginationHandler = $paginationHandler;
    }

    public function getEbayProductsByGlobalId(SearchModel $model)
    {
        if ($this->searchResponseCacheImplementation->isStored($model->getUniqueName())) {
            /** @var SearchCache $presentationResults */
            $presentationResults = $this->searchResponseCacheImplementation->getStored(
                $model->getUniqueName()
            );

            $presentationResultsArray = json_decode($presentationResults->getProductsResponse(), true);

            $paginatedProducts = $this->paginationHandler->paginateListing(
                $presentationResultsArray,
                $model->getPagination()
            );

            return $this->translateSearchResults($paginatedProducts, $model->getLocale());
        }

        /** @var TypedArray $presentationResults */
        $presentationResults = $this->responseFetcher->getResponse($model);

        $presentationResultsArray = $presentationResults->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION);

        $this->searchResponseCacheImplementation->store(
            $model->getUniqueName(),
            $model->getInternalPagination()->getPage(),
            jsonEncodeWithFix($presentationResultsArray)
        );

        $paginatedProducts = $this->paginationHandler->paginateListing(
            $presentationResultsArray,
            $model->getPagination()
        );

        return $this->translateSearchResults($paginatedProducts, $model->getLocale());
    }
    /**
     * @param array $searchResults
     * @param string $locale
     * @return iterable
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function translateSearchResults(
        array $searchResults,
        string $locale
    ): iterable {
        $presentationResultsGen = Util::createGenerator($searchResults);

        $translatedPresentationResults = [];
        foreach ($presentationResultsGen as $entry) {
            $item = $entry['item'];

            $translatedPresentationResults[] = $this->translationCenter->translateMultiple(
                $item,
                [
                    'title' => [
                        'pre_translate' => function(string $key, array $entireItem) {
                            if ($key === 'title') {
                                return $entireItem['title']['original'];
                            }
                        },
                        'post_translate' => function(string $key, string $translated) {
                            if ($key === 'title') {
                                $title = new Title($translated);

                                return $title->toArray();
                            }
                        }
                    ]
                ],
                $locale,
                $item['itemId']
            );
        }

        return $translatedPresentationResults;
    }
}