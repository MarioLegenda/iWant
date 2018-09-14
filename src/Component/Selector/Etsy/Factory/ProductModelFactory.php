<?php

namespace App\Component\Selector\Etsy\Factory;

use App\Component\Selector\Type\Nan;
use App\Component\TodayProducts\Model\Image;
use App\Component\TodayProducts\Model\Price;
use App\Component\TodayProducts\Model\Title;
use App\Component\TodayProducts\Model\TodayProduct;
use App\Etsy\Library\Response\ResponseItem\Result;
use App\Library\MarketplaceType;

class ProductModelFactory
{
    public function createModel(Result $singleModel)
    {
        $itemId = (string) $singleModel->getListingId();
        $title = new Title($singleModel->getTitle());
        $imageUrl = $this->createImage($singleModel);
        $shopName = 'Shop name';
        $price = $this->createPrice($singleModel);
        $viewItemUrl = $singleModel->getUrl();
        $shopType = MarketplaceType::fromValue('Etsy');

        return new TodayProduct(
            $itemId,
            $title,
            $imageUrl,
            $shopName,
            $price,
            $viewItemUrl,
            $shopType
        );
    }
    /**
     * @param Result $singleModel
     * @return Image
     */
    private function createImage(Result $singleModel): Image
    {
        return new Image(Nan::fromValue());
    }
    /**
     * @param Result $singleModel
     * @return Price
     */
    private function createPrice(Result $singleModel): Price
    {
        return new Price(
            $singleModel->getCurrency(),
            $singleModel->getPrice()
        );
    }
}