<?php

namespace App\Web\Library\View;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;

class StaticViewFactory
{
    /**
     * @param iterable|TypedArray $products
     * @return array
     */
    public static function createGlobalIdView(iterable $products): array
    {
        $products = ($products instanceof TypedArray) ? $products->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION) : $products;

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
     * @param iterable|TypedArray $products
     * @return array
     */
    public static function createItemsView(iterable $products): array
    {
        $products = ($products instanceof TypedArray) ? $products->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION) : $products;

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