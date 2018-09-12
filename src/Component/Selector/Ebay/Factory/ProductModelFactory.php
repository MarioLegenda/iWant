<?php

namespace App\Component\Selector\Ebay\Factory;

use App\Component\Selector\Type\Nan;
use App\Component\TodayProducts\Model\Image;
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
        $title = $singleItem->getTitle();
        $shopName = $singleItem->getSellerInfo()->getSellerUsername();
        $price = $singleItem->getSellingStatus()->getCurrentPrice();
        $viewItemUrl = $singleItem->getViewItemUrl();
        $image = $this->createImage($singleItem);

        return new TodayProduct(
            $itemId,
            $title,
            $image,
            $shopName,
            $price['currentPrice'],
            $viewItemUrl,
            MarketplaceType::fromValue('Ebay')
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