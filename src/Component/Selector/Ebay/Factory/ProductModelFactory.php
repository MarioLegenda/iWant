<?php

namespace App\Component\Selector\Ebay\Factory;

use App\Component\Selector\Ebay\Selector\SearchProduct;
use App\Component\Selector\Type\Nan;
use App\Component\TodayProducts\Model\Image;
use App\Component\TodayProducts\Model\Price;
use App\Component\TodayProducts\Model\Title;
use App\Component\TodayProducts\Model\TodayProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;

class ProductModelFactory
{

    public function createModel(Item $singleItem, ApplicationShop $applicationShop): TodayProduct
    {
        $itemId = (string) $singleItem->getItemId();
        $title = new Title($singleItem->getTitle());
        $shopName = $applicationShop->getApplicationName();
        $price = $this->createPrice($singleItem->getSellingStatus()->getCurrentPrice());
        $viewItemUrl = $singleItem->getViewItemUrl();
        $image = $this->createImage($singleItem);
        $globalId = $singleItem->getGlobalId();
        $marketplace = MarketplaceType::fromValue('Ebay');
        $staticUrl = $this->createStaticUrl(
            $marketplace,
            $title->getOriginal(),
            (string) $itemId
        );

        $taxonomyName = $applicationShop->getNativeTaxonomy()->getName();

        return new TodayProduct(
            $itemId,
            $title,
            $image,
            $shopName,
            $price,
            $viewItemUrl,
            $marketplace,
            $staticUrl,
            $taxonomyName,
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
     * @param MarketplaceType|TypeInterface $marketplace
     * @param string $title
     * @param string $itemId
     * @return string
     */
    private function createStaticUrl(
        MarketplaceType $marketplace,
        string $title,
        string $itemId
    ): string {
        return sprintf(
            '/item/%s/%s/%s',
            (string) $marketplace,
            \URLify::filter($title),
            $itemId
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