<?php

namespace App\Component\Search\Ebay\Business\Cache;

use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;

class UniqueIdentifierFactory
{
    /**
     * @param SearchModelInterface|SearchModel $model
     * @return string
     */
    public static function createIdentifier(SearchModelInterface $model): string
    {
        return md5(serialize([
            'keyword' => (string) $model->getKeyword(),
            'bestMatch' => $model->isBestMatch(),
            'shippingCountries' => $model->getShippingCountries(),
            'highQuality' => $model->isHighQuality(),
            'globalId' => $model->getGlobalId(),
            'hideDuplicateItems' => $model->isHideDuplicateItems(),
            'doubleLocaleSearch' => $model->isDoubleLocaleSearch(),
            'searchStores' => $model->isSearchStores(),
            'newlyListed' => $model->isNewlyListed(),
        ]));
    }
}