<?php

namespace App\Component\Search\Ebay\Business\Factory;

use App\App\Presentation\EntryPoint\CountryEntryPoint;
use App\Component\Search\Ebay\Model\Response\BusinessEntity;
use App\Component\Search\Ebay\Model\Response\Country;
use App\Component\Search\Ebay\Model\Response\Image;
use App\Component\Search\Ebay\Model\Response\Nan;
use App\Component\Search\Ebay\Model\Response\Price;
use App\Component\Search\Ebay\Model\Response\SearchResponseModel;
use App\Component\Search\Ebay\Model\Response\Title;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\ListingInfo;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Util\Util;
use App\Doctrine\Entity\Country as CountryEntity;

class SearchResponseModelFactory
{
    /**
     * @var CountryEntryPoint $countryEntryPoint
     */
    private $countryEntryPoint;
    /**
     * SearchResponseModelFactory constructor.
     * @param CountryEntryPoint $countryEntryPoint
     */
    public function __construct(
        CountryEntryPoint $countryEntryPoint
    ) {
        $this->countryEntryPoint = $countryEntryPoint;
    }
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

            $country = $this->tryGetCountry($item);

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
                $globalId,
                $country,
                ($item->getListingInfo() instanceof ListingInfo) ? $item->getListingInfo()->toArray() : null
            );
        }

        return $searchResponseModels;
    }
    /**
     * @param Item $item
     * @return Country|null
     */
    private function tryGetCountry(Item $item): ?Country
    {
        if (!is_string($item->getCountry())) {
            return new Country();
        }

        $countryEntity = $this->countryEntryPoint->findByAlpha2Code($item->getCountry());

        if (!$countryEntity instanceof CountryEntity) {
            return new Country();
        }

        return new Country($countryEntity->toArray());
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