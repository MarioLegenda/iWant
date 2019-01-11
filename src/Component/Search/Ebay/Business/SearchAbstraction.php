<?php

namespace App\Component\Search\Ebay\Business;

use App\Cache\Implementation\ItemTranslationCacheImplementation;
use App\Component\Search\Ebay\Business\ResultsFetcher\FetcherFactory;
use App\Component\Search\Ebay\Model\Request\InternalSearchModel;
use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;
use App\Component\Search\Ebay\Model\Response\Title;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;
use App\Translation\TranslationCenter;
use App\Translation\TranslationCenterInterface;
use App\Translation\YandexCacheableTranslationCenter;

class SearchAbstraction
{
    /**
     * @var TranslationCenterInterface $translationCenter
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
    /**
     * @param SearchModelInterface|SearchModel $model
     * @return array
     */
    public function getProducts(SearchModelInterface $model): array
    {
        $products = $this->fetcherFactory->decideFetcher($model)->getResults($model);

        return $products;
    }
    /**
     * @param array $listing
     * @param SearchModel|SearchModelInterface $model
     * @return iterable
     */
    public function paginateListing(array $listing, SearchModelInterface $model): iterable
    {
        return $this->paginationHandler->paginateListing($listing, $model->getPagination());
    }
    /**
     * @param SearchModel|SearchModelInterface $model
     * @return iterable
     */
    public function paginateListingAutomatic(SearchModelInterface $model): iterable
    {
        $products = $this->getProducts($model);

        return $this->paginateListing(
            $products,
            $model
        );
    }
    /**
     * @param SearchModelInterface|SearchModel $model
     * @return array
     */
    public function paginateListingWithInformation(
        SearchModelInterface $model
    ) {
        $listing = $this->getProducts($model);

        return [
            'totalItems' => count($listing),
            'items' => $this->paginationHandler->paginateListing($listing, $model->getPagination())
        ];
    }
    /**
     * @param array $listing
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     * @return iterable
     */
    public function translateListing(array $listing, SearchModelInterface $model): iterable
    {
        return $this->translateSearchResults($listing, $model->getLocale());
    }
    /**
     * @param array $searchResults
     * @param string $locale
     * @return iterable
     */
    private function translateSearchResults(
        array $searchResults,
        string $locale
    ): iterable {
        $presentationResultsGen = Util::createGenerator($searchResults);

        $translatedPresentationResults = [];
        foreach ($presentationResultsGen as $entry) {
            $item = $entry['item'];

            $translatedPresentationResults[] = $this->translationCenter->translateArray(
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

                                return [
                                    'key' => 'title',
                                    'value' => $title->toArray(),
                                ];
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