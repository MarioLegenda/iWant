<?php

namespace App\Component\Search\Ebay\Business\Factory;

use App\Component\Search\Ebay\Model\Response\Image;
use App\Component\Search\Ebay\Model\Response\Nan;
use App\Component\Search\Ebay\Model\Response\Price;
use App\Component\Search\Ebay\Model\Response\SearchResponseModel;
use App\Component\Search\Ebay\Model\Response\Title;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Util\Util;

class SearchResponseModelFactory
{
    /**
     * @param string $uniqueName
     * @param string $globalId
     * @param iterable $searchResults
     * @return TypedArray
     */
    public function fromIterable(
        string $uniqueName,
        string $globalId,
        iterable $searchResults
    ): TypedArray {
        $searchResponseModels = TypedArray::create('integer', SearchResponseModel::class);

        $searchResultsGen = Util::createGenerator($searchResults);

        foreach ($searchResultsGen as $entry) {
            /** @var Item $item */
            $item = $entry['item'];

            $itemId = $item->getItemId();
            $title = new Title($item->getTitle());
            $image = new Image($item->dynamicSingleItemChoice(function(Item $item) {
                $methods = ['getPictureURLSuperSize', 'getPictureURLLarge', 'getGalleryPlusPictureURL', 'getGalleryUrl'];

                foreach ($methods as $method) {
                    $image = $item->{$method}();

                    if (is_string($image)) {
                        return $image;
                    }
                }

                return Nan::fromValue();
            }));

            $shopName = $item->dynamicSingleItemChoice(function(Item $item) {
                if ($item->getStoreInfo() !== null) {
                    return $item->getStoreInfo()->getStoreName();
                }

                if ($item->getSellerInfo() !== null) {
                    return $item->getSellerInfo()->getSellerUsername();
                }

                return 'Unknown';
            });

            $price = new Price(
                $item->getSellingStatus()->getCurrentPrice()['currencyId'],
                $item->getSellingStatus()->getCurrentPrice()['currentPrice']
            );

            $viewItemUrl = $item->getViewItemUrl();
            $marketplaceType = MarketplaceType::fromValue('Ebay');
            $staticUrl = sprintf(
                '/item/Ebay/%s/%s',
                \URLify::filter($item->getTitle()),
                $itemId
            );

            $taxonomyName = 'Invalid taxonomy';
            $shippingLocations = [];

            $searchResponseModels[] = new SearchResponseModel(
                $uniqueName,
                $itemId,
                $title,
                $image,
                $shopName,
                $price,
                $viewItemUrl,
                $marketplaceType,
                $staticUrl,
                $taxonomyName,
                $shippingLocations,
                $globalId
            );
        }

        return $searchResponseModels;
    }
}