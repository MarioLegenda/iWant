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
            'keyword' => $model->getKeyword(),
            'bestMatch' => $model->isBestMatch(),
            'highQuality' => $model->isHighQuality(),
            'globalId' => $model->getGlobalId(),
            'hideDuplicateItems' => $model->isHideDuplicateItems(),
            'doubleLocaleSearch' => $model->isDoubleLocaleSearch(),
        ]));
    }
}