<?php

namespace App\Tests\Component\DataProvider;

use App\Component\Search\Ebay\Model\Request\Pagination as EbayPagination;
use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;
use App\Tests\Library\FakerTrait;
use App\Translation\Model\Language;

class DataProvider
{
    use FakerTrait;
    /**
     * @param array $data
     * @return EbaySearchModel
     */
    public function createEbaySearchRequestModel(array $data = []): EbaySearchModel
    {
        $keyword = (isset($data['keyword'])) ? new Language($data['keyword']): new Language('harry potter book');
        $lowestPrice = (isset($data['lowestPrice'])) ? $data['lowestPrice']: true;
        $highestPrice = (isset($data['highestPrice'])) ? $data['highestPrice']: false;
        $highQuality = (isset($data['highQuality'])) ? $data['highQuality']: false;
        $shippingCountries = (isset($data['shippingCountries'])) ? $data['shippingCountries']: [];
        $taxonomies = (isset($data['taxonomies'])) ? $data['taxonomies']: [];
        $globalIds = $data['globalId'];
        $pagination = (isset($data['pagination']) and $data['pagination'] instanceof EbayPagination)
            ? $data['pagination']
            : new EbayPagination(8, 1);

        $internalPagination = (isset($data['internalPagination']) and $data['internalPagination'] instanceof EbayPagination)
            ? $data['internalPagination']
            : new EbayPagination(80, 1);

        $locale = (isset($data['locale']) ? $data['locale'] : 'en');

        $hideDuplicateItems = (isset($data['hideDuplicateItems'])) ? $data['hideDuplicateItems'] : false;
        $doubleLocaleSearch = (isset($data['doubleLocaleSearch'])) ? $data['doubleLocaleSearch'] : false;
        $fixedPriceOnly = (isset($data['fixedPrice'])) ? $data['fixedPrice'] : false;
        $searchStores = (isset($data['searchStores'])) ? $data['searchStores'] : false;
        $searchQueryFilter = (isset($data['searchQueryFilter'])) ? $data['searchQueryFilter'] : false;

        $sortingMethod = (isset($data['sortingMethod'])) ? $data['sortingMethod'] : 'bestMatch';
        $watchCountIncrease = (isset($data['watchCountIncrease'])) ? $data['watchCountIncrease'] : false;
        $watchCountDecrease = (isset($data['watchCountDecrease'])) ? $data['watchCountDecrease'] : false;

        return new EbaySearchModel(
            $keyword,
            $lowestPrice,
            $highestPrice,
            $highQuality,
            $shippingCountries,
            $taxonomies,
            $pagination,
            $globalIds,
            $locale,
            $internalPagination,
            $hideDuplicateItems,
            $doubleLocaleSearch,
            $fixedPriceOnly,
            $searchStores,
            $sortingMethod,
            $searchQueryFilter,
            $watchCountIncrease,
            $watchCountDecrease
        );
    }
}