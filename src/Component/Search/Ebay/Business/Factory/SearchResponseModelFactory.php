<?php

namespace App\Component\Search\Ebay\Business\Factory;

use App\Component\Search\Ebay\Model\Response\BusinessEntity;
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
    public function fromSearchResults(
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

            $businessEntity = new BusinessEntity(
                $item->getStoreInfo(),
                $item->getSellerInfo()
            );

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
                $businessEntity,
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
    /**
     * @param string $uniqueName
     * @param string $globalId
     * @param array $searchResults
     * @return TypedArray
     */
    public function fromArray(
        string $uniqueName,
        string $globalId,
        array $searchResults
    ): TypedArray {
        $searchResponseModels = TypedArray::create('integer', SearchResponseModel::class);

        $searchResultsGen = Util::createGenerator($searchResults);

        foreach ($searchResultsGen as $entry) {
            /** @var Item $item */
            $item = $entry['item'];

            $itemId = $item['itemId'];
            $title = new Title($item['title']['original']);
            $image = new Image(
                $item['image']['url'],
                $item['image']['width'],
                $item['image']['height']
            );

            $shopName = $item['shopName'];

            $price = new Price(
                $item['price']['currency'],
                $item['price']['price']
            );

            $viewItemUrl = $item['viewItemUrl'];
            $marketplaceType = MarketplaceType::fromValue($item['marketplace']);
            $staticUrl = $item['staticUrl'];

            $taxonomyName = $item['taxonomyName'];
            $shippingLocations = $item['shippingLocations'];

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