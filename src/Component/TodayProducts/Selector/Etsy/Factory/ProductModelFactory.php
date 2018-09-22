<?php

namespace App\Component\TodayProducts\Selector\Etsy\Factory;

use App\Component\TodayProducts\Model\Image;
use App\Component\TodayProducts\Model\Price;
use App\Component\TodayProducts\Model\Title;
use App\Component\TodayProducts\Model\TodayProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Etsy\Library\Response\ResponseItem\Result;
use App\Library\MarketplaceType;
use App\Library\Infrastructure\Type\TypeInterface;

class ProductModelFactory
{
    /**
     * @param Result $singleModel
     * @param ApplicationShop $applicationShop
     * @param array $shippingInformation
     * @param array $image
     * @return TodayProduct
     */
    public function createModel(
        Result $singleModel,
        ApplicationShop $applicationShop,
        array $shippingInformation,
        array $image
    ) {
        $itemId = (string) $singleModel->getListingId();
        $title = new Title($singleModel->getTitle());
        $image = $this->createImage($image);
        $shopName = $applicationShop->getApplicationName();
        $price = $this->createPrice($singleModel);
        $viewItemUrl = $singleModel->getUrl();
        $marketplace = MarketplaceType::fromValue('Etsy');

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
            $shippingInformation
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
     * @param array $image
     * @return Image
     */
    private function createImage(array $image): Image
    {
        return new Image(
            $image['urlFull'],
            $image['fullWidth'],
            $image['fullHeight']
        );
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