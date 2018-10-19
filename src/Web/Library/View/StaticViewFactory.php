<?php

namespace App\Web\Library\View;

use App\Library\Util\Util;

class StaticViewFactory
{
    /**
     * @param array $products
     * @return array
     */
    public static function createGlobalIdView(array $products): array
    {
        $productsGen = Util::createGenerator($products);

        $mergedItems = [];
        foreach ($productsGen as $entry) {
            $item = $entry['item'];
            $globalId = $item['globalIdInformation']['global_id'];
            $listingItems = $item['items'];

            $mergedItems[$globalId] = $listingItems;
        }

        return $mergedItems;
    }
    /**
     * @param array $products
     * @return array
     */
    public static function createItemsView(array $products): array
    {
        $productsGen = Util::createGenerator($products);

        $mergedItems = [];
        foreach ($productsGen as $entry) {
            $item = $entry['item'];
            $listingItems = $item['items'];

            $mergedItems = array_merge($mergedItems, $listingItems);
        }

        return $mergedItems;
    }
}