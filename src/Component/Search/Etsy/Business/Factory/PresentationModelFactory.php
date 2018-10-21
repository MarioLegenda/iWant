<?php

namespace App\Component\Search\Etsy\Business\Factory;

use App\Component\Search\Etsy\Model\Response\Image;
use App\Component\Search\Etsy\Model\Response\Price;
use App\Component\Search\Etsy\Model\Response\SearchResponseModel;
use App\Component\Search\Etsy\Model\Response\Title;
use App\Etsy\Library\Response\ResponseItem\ListingShop;
use App\Etsy\Library\Response\ResponseItem\ResultsInterface;
use App\Library\MarketplaceType;
use App\Library\Util\Util;

class PresentationModelFactory
{
    /**
     * @param array $listing
     * @param array $images
     * @param ListingShop|ResultsInterface $listingShop
     * @return SearchResponseModel
     */
    public function createModel(
        array $listing,
        array $images,
        ListingShop $listingShop
    ): SearchResponseModel {
        $listingId = $listing['listing_id'];
        $title = new Title($listing['title']);
        $image = $this->chooseImage($images);
        $shopName = $this->determineShopName($listingShop);
        $price = new Price($listing['currency'], $listing['price']);
        $viewItemUrl = $listing['url'];
        $marketplaceType = MarketplaceType::fromValue('Etsy');
        $staticUrl = sprintf('/item/Etsy/%s/%s', \URLify::filter($title->getOriginal()), $listingId);
        $shippingLocations = [];

        return new SearchResponseModel(
            $listingId,
            $title,
            $image,
            $shopName,
            $price,
            $viewItemUrl,
            $marketplaceType,
            $staticUrl,
            $shippingLocations
        );
    }
    /**
     * @param ListingShop $listingShop
     * @return string
     */
    private function determineShopName(ListingShop $listingShop): string
    {
        return $listingShop->getIterableResults()[0]['shop_name'];
    }
    /**
     * @param array $images
     * @return Image
     */
    private function chooseImage(array $images): Image
    {
        $imagesGen = Util::createGenerator($images);

        $cascade = ['urlFull', 'url570', 'url170', 'url75'];

        $imageUrl = null;
        foreach ($imagesGen as $entry) {
            $item = $entry['item'];

            foreach ($cascade as $imageName) {
                if (array_key_exists($imageName, $item) and !empty($item[$imageName])) {
                    $imageUrl = $item[$imageName];
                }
            }
        }

        return new Image($imageUrl);
    }
}