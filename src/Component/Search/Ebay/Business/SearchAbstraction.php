<?php

namespace App\Component\Search\Ebay\Business;

use App\Cache\Implementation\ItemTranslationCacheImplementation;
use App\Component\Search\Ebay\Business\ResultsFetcher\FetcherFactory;
use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Response\Title;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;
use App\Translation\TranslationCenter;

class SearchAbstraction
{
    /**
     * @var TranslationCenter $translationCenter
     */
    private $translationCenter;
    /**
     * @var ItemTranslationCacheImplementation $itemTranslationCacheImplementation
     */
    private $itemTranslationCacheImplementation;
    /**
     * @var PaginationHandler $paginationHandler
     */
    private $paginationHandler;
    /**
     * @var FetcherFactory $fetcherFactory
     */
    private $fetcherFactory;

    public function __construct(
        FetcherFactory $fetcherFactory,
        TranslationCenter $translationCenter,
        ItemTranslationCacheImplementation $itemTranslationCacheImplementation,
        PaginationHandler $paginationHandler
    ) {
        $this->translationCenter = $translationCenter;
        $this->itemTranslationCacheImplementation = $itemTranslationCacheImplementation;
        $this->paginationHandler = $paginationHandler;
        $this->fetcherFactory = $fetcherFactory;
    }

    public function getProducts(SearchModel $model): array
    {
        $products = $this->fetcherFactory->decideFetcher($model)->getResults($model);

        return $products;
    }
    /**
     * @param array $listing
     * @param SearchModel $model
     * @return iterable
     */
    public function paginateListing(array $listing, SearchModel $model): iterable
    {
        return $this->paginationHandler->paginateListing($listing, $model->getPagination());
    }
    /**
     * @param SearchModel $model
     * @return iterable
     */
    public function paginateListingAutomatic(SearchModel $model): iterable
    {
        $products = $this->getProducts($model);

        return $this->paginateListing(
            $products,
            $model
        );
    }
    /**
     * @param SearchModel $model
     * @return iterable
     *
     * @deprecated Will be implemented after prototype is finished
     */
    public function getListingRange(SearchModel $model): iterable
    {
        $range = $model->getRange();

        $to = $range->getTo();

        if ($to <= 0) {
            $message = sprintf(
                'Invalid products range. Range has to be between 1 and an integer greater than 1'
            );

            throw new \RuntimeException($message);
        }

        $pages = $to / 80;

        if (is_float($pages)) {
            $pages = (int) number_format(intval($pages), 0) + 1;
        }

        $combinedResults = [];
        for ($i = 1; $i <= $pages; $i++) {
            /** @var TypedArray $results */
            $results = $this->fetcherFactory->decideFetcher($model)->getResults($model, [
                'internalPagination' => new Pagination(80, $i),
            ]);

            $combinedResults = array_merge($combinedResults, $results->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION));
        }

        $finalResult = [];
        for ($i = $range->getFrom(); $i <= $range->getTo(); $i++) {
            $finalResult[] = $combinedResults[$i];
        }

        return $finalResult;
    }
    /**
     * @param array $listing
     * @param SearchModel $model
     * @return iterable
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function translateListing(array $listing, SearchModel $model): iterable
    {
        return $this->translateSearchResults($listing, $model->getLocale());
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