<?php

namespace App\Component\Search\Ebay\Business\Factory;

use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Web\Library\View\EbaySearchViewType;

/**
 * Class RequestModelFactory
 * @package App\Component\Search\Ebay\Business\Factory
 *
 * ONLY TO BE USED WHEN SPREADING AN ALREADY SEARCH QUERY FROM EBAY
 *
 * This factory will only create an object if you provide it a full
 * array with all the values. It does not presume anything about the
 * values of that array which means it is going to fail if some of the
 * value are missing
 *
 * **************
 *
 * USE WITH CAUTION
 *
 * *****************
 */
class RequestModelFactory
{
    /**
     * @param array $model
     * @return SearchModel
     */
    public static function createFromArray(array $model): SearchModel
    {
        $keyword = $model['keyword'];
        $lowestPrice = $model['lowestPrice'];
        $highestPrice = $model['highestPrice'];
        $highQuality = $model['highQuality'];
        $bestMatch = $model['bestMatch'];
        $shippingCountries = [];
        $marketplaces = [];
        $taxonomies = [];
        $globalIds = $model['globalId'];
        $pagination = new Pagination($model['pagination']['limit'], $model['pagination']['page']);

        $viewType = EbaySearchViewType::fromValue($model['viewType']);

        return new SearchModel(
            $keyword,
            $lowestPrice,
            $highestPrice,
            $highQuality,
            $bestMatch,
            $shippingCountries,
            $marketplaces,
            $taxonomies,
            $pagination,
            $viewType,
            $globalIds
        );
    }
}