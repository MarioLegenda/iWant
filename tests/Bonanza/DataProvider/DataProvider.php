<?php

namespace App\Tests\Bonanza\DataProvider;

use App\Bonanza\Library\Information\SortOrderInformation;
use App\Bonanza\Presentation\Model\BonanzaApiModel;
use App\Bonanza\Presentation\Model\FindItemsByKeywords;
use App\Bonanza\Presentation\Model\ItemFilter;
use App\Bonanza\Presentation\Model\ItemFilterMetadata;
use App\Library\Infrastructure\Helper\TypedArray;

class DataProvider
{
    public function getFindItemsByKeywordsData(string $keywords): BonanzaApiModel
    {
        $findItemsByKeywords = new FindItemsByKeywords(
            'findItemsByKeywords',
            'keywords',
            $keywords
        );

        $itemFilters = TypedArray::create('integer', ItemFilter::class);

        $sortOrder = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            'sortOrder',
            [SortOrderInformation::CURRENT_PRICE_LOWEST]
        ));

        $paginationInput = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            'paginationInput',
            [
                [
                    'entriesPerPage' => 5,
                    'pageNumber' => 1
                ]
            ]
        ));

        $keywords = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            'keywords',
            ['boots, mountain']
        ));



        $itemFilters[] = $sortOrder;
        $itemFilters[] = $paginationInput;
        $itemFilters[] = $keywords;

        $model = new BonanzaApiModel($findItemsByKeywords, $itemFilters);

        return $model;
    }
}