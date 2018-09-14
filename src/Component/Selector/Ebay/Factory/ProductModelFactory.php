<?php

namespace App\Component\Selector\Ebay\Factory;

use App\Component\Selector\Type\Nan;
use App\Component\TodayProducts\Model\Image;
use App\Component\TodayProducts\Model\Price;
use App\Component\TodayProducts\Model\Title;
use App\Component\TodayProducts\Model\TodayProduct;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Library\MarketplaceType;

class ProductModelFactory
{
    /**
     * @param Item $singleItem
     * @return TodayProduct
     */
    public function createModel(Item $singleItem): TodayProduct
    {
        $itemId = (string) $singleItem->getItemId();
        $title = new Title($singleItem->getTitle());
        $shopName = $singleItem->getSellerInfo()->getSellerUsername();
        $price = $this->createPrice($singleItem->getSellingStatus()->getCurrentPrice());
        $viewItemUrl = $singleItem->getViewItemUrl();
        $image = $this->createImage($singleItem);
        $globalId = $singleItem->getGlobalId();

        return new TodayProduct(
            $itemId,
            $title,
            $image,
            $shopName,
            $price,
            $viewItemUrl,
            MarketplaceType::fromValue('Ebay'),
            $globalId
        );
    }
    /**
     * @param array $priceInfo
     * @return Price
     */
    private function createPrice(array $priceInfo): Price
    {
        return new Price(
            $priceInfo['currencyId'],
            $priceInfo['currentPrice']
        );
    }
    /**
     * @param Item $model
     * @return Image
     */
    private function createImage(Item $model): Image
    {
        $url = $model->getGalleryUrl();

        if (is_string($url)) {
            $imageSize = getimagesize($url);

            $width = $imageSize[0];
            $height = $imageSize[1];

            return new Image($url, $width, $height);
        }

        return new Image(
            Nan::fromValue()
        );
    }
}