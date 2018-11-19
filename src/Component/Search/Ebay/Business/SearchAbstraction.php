<?php

namespace App\Component\Search\Ebay\Business;

use App\Cache\Implementation\ItemTranslationCacheImplementation;
use App\Component\Search\Ebay\Business\ResultsFetcher\ResultsFetcher;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Response\Title;
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
     * @var ResultsFetcher $resultsFetcher
     */
    private $resultsFetcher;

    public function __construct(
        ResultsFetcher $resultsFetcher,
        TranslationCenter $translationCenter,
        ItemTranslationCacheImplementation $itemTranslationCacheImplementation,
        PaginationHandler $paginationHandler
    ) {
        $this->translationCenter = $translationCenter;
        $this->itemTranslationCacheImplementation = $itemTranslationCacheImplementation;
        $this->paginationHandler = $paginationHandler;
        $this->resultsFetcher = $resultsFetcher;
    }

    public function getProducts(SearchModel $model)
    {
        return $this->resultsFetcher->getResults($model);
    }

    public function paginateListing(array $listing, SearchModel $model)
    {
        return $this->paginationHandler->paginateListing($listing, $model->getPagination());
    }

    public function paginateListingAutomatic(SearchModel $model)
    {
        return $this->paginateListing(
            $this->getProducts($model),
            $model
        );
    }

    public function translateListing(array $listing, SearchModel $model)
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