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
use App\Ebay\Library\Response\FindingApi\Json\Result\ListingInfo;
use App\Ebay\Library\Response\FindingApi\Json\SearchResult;
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
     * @param \Generator $searchResults
     * @return TypedArray
     */
    public function fromSearchResults(
        string $uniqueName,
        string $globalId,
        \Generator $searchResults
    ): TypedArray {
        $searchResponseModels = TypedArray::create('integer', SearchResponseModel::class);

        foreach ($searchResults as $entry) {
            /** @var SearchResult $item */
            $item = SearchResult::createFromResponseArray($entry['item']);

            $itemId = $item->getItemId();
            $title = new Title($item->getTitle());
            $subtitle = (is_string($item->getSubtitle()) and !empty($item->getSubtitle())) ? new Title($item->getSubtitle()) : null;
            $image = new Image((function(SearchResult $item) {
                $methods = ['getPictureUrlSuperSize', 'getPictureUrlLarge', 'getGalleryUrl'];

                foreach ($methods as $method) {
                    $image = $item->{$method}();

                    if (is_string($image)) {
                        return $image;
                    }
                }

                return Nan::fromValue();
            })($item));

            $businessEntity = new BusinessEntity(
                $item->getStoreInfo(),
                $item->getSellerInfo()
            );

            $country = $this->tryGetCountry($item);

            $price = new Price(
                $item->getSellingStatus()->getCurrentPrice()->getCurrency(),
                $item->getSellingStatus()->getCurrentPrice()->getPrice()
            );

            $viewItemUrl = $item->getViewItemUrl();

            $searchResponseModels[] = new SearchResponseModel(
                $uniqueName,
                $itemId,
                $title,
                $subtitle,
                $image,
                $businessEntity,
                $price,
                $viewItemUrl,
                $globalId,
                $country,
                ($item->getListingInfo() instanceof ListingInfo) ? $item->getListingInfo()->toArray() : null
            );

            unset($item);
        }

        return $searchResponseModels;
    }
    /**
     * @param SearchResult $item
     * @return Country|null
     */
    private function tryGetCountry(SearchResult $item): ?Country
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
}