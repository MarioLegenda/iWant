<?php

namespace App\Component\Search\Ebay\Business\Factory;

use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Web\Library\View\EbaySearchViewType;

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
            $shippingCountries,
            $marketplaces,
            $taxonomies,
            $pagination,
            $viewType,
            $globalIds
        );
    }
}