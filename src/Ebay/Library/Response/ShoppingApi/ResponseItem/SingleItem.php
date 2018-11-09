<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class SingleItem extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * @var string $title
     */
    private $title;
    /**
     * @var bool $bestOfferEnabled
     */
    private $bestOfferEnabled;
    /**
     * @var string $description
     */
    private $description;
    /**
     * @var \DateTime $startTime
     */
    private $startTime;
    /**
     * @var \DateTime $endTime
     */
    private $endTime;
    /**
     * @var string $viewItemUrlForNaturalSearch
     */
    private $viewItemUrlForNaturalSearch;
    /**
     * @var string $listingType
     */
    private $listingType;
    /**
     * @var string $location
     */
    private $location;
    /**
     * @var string $paymentMethods
     */
    private $paymentMethods;
    /**
     * @var string $galleryUrl
     */
    private $galleryUrl;
    /**
     * @var string $pictureUrl
     */
    private $pictureUrl;
    /**
     * @var string $primaryCategoryId
     */
    private $primaryCategoryId;
    /**
     * @var string $primaryCategoryName
     */
    private $primaryCategoryName;
    /**
     * @var string $quantity
     */
    private $quantity;
    /**
     * @var SellerItem
     */
    private $seller;
    /**
     * @var PriceInfo $priceInfo
     */
    private $priceInfo;
    /**
     * @var string $listingStatus
     */
    private $listingStatus;
    /**
     * @var string $quantitySold
     */
    private $quantitySold;
    /**
     * @var array $shipsToLocations
     */
    private $shipsToLocations;
    /**
     * @var string $site
     */
    private $site;
    /**
     * @var string $timeLeft
     */
    private $timeLeft;
    /**
     * @var ShippingCostSummary $shippingCostSummary
     */
    private $shippingCostSummary;
    /**
     * @var ItemSpecifics $itemSpecifics
     */
    private $itemSpecifics;
    /**
     * @param null $default
     * @return string
     */
    public function getItemId($default = null): ?string
    {
        if ($this->itemId === null) {
            if (!empty($this->simpleXml->ItemID)) {
                $this->itemId = (string) $this->simpleXml->ItemID;
            }
        }

        if ($this->itemId === null and $default !== null) {
            return $default;
        }

        return $this->itemId;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getDescription($default = null): ?string
    {
        if ($this->description === null) {
            if (!empty($this->simpleXml->Description)) {
                $this->description = (string) $this->simpleXml->Description;
            }
        }

        if ($this->description === null and $default !== null) {
            return $default;
        }

        return $this->description;
    }
    /**
     * @param null $default
     * @return bool
     */
    public function getBestOfferEnabled($default = null): bool
    {
        if ($this->bestOfferEnabled === null) {
            if (!empty($this->simpleXml->BestOfferEnabled)) {
                $this->bestOfferEnabled = (string) $this->simpleXml->BestOfferEnabled;
            }
        }

        if ($this->bestOfferEnabled === null and $default !== null) {
            return $default;
        }

        return $this->bestOfferEnabled;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getEndTime($default = null): ?string
    {
        if ($this->endTime === null) {
            if (!empty($this->simpleXml->EndTime)) {
                $this->endTime = (string) $this->simpleXml->EndTime;
            }
        }

        if ($this->endTime === null and $default !== null) {
            return $default;
        }

        return $this->endTime;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getStartTime($default = null): ?string
    {
        if ($this->startTime === null) {
            if (!empty($this->simpleXml->StartTime)) {
                $this->startTime = (string) $this->simpleXml->StartTime;
            }
        }

        if ($this->startTime === null and $default !== null) {
            return $default;
        }

        return $this->startTime;
    }
    /**
     * @return \DateTime
     */
    public function getStartTimeObject(): \DateTime
    {
        return new \DateTime($this->getStartTime());
    }
    /**
     * @return \DateTime
     */
    public function getEndTimeObject(): \DateTime
    {
        return new \DateTime($this->getEndTime());
    }
    /**
     * @param null $default
     * @return string
     */
    public function getViewItemUrlForNaturalSearch($default = null): ?string
    {
        if ($this->viewItemUrlForNaturalSearch === null) {
            if (!empty($this->simpleXml->ViewItemURLForNaturalSearch)) {
                $this->viewItemUrlForNaturalSearch = (string) $this->simpleXml->ViewItemURLForNaturalSearch;
            }
        }

        if ($this->viewItemUrlForNaturalSearch === null and $default !== null) {
            return $default;
        }

        return $this->viewItemUrlForNaturalSearch;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getListingType($default = null): ?string
    {
        if ($this->listingType === null) {
            if (!empty($this->simpleXml->ListingType)) {
                $this->listingType = (string) $this->simpleXml->ListingType;
            }
        }

        if ($this->listingType === null and $default !== null) {
            return $default;
        }

        return $this->listingType;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getLocation($default = null): ?string
    {
        if ($this->location === null) {
            if (!empty($this->simpleXml->Location)) {
                $this->location = (string) $this->simpleXml->Location;
            }
        }

        if ($this->location === null and $default !== null) {
            return $default;
        }

        return $this->location;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getPaymentMethods($default = null): ?string
    {
        if ($this->paymentMethods === null) {
            if (!empty($this->simpleXml->PaymentMethods)) {
                $this->paymentMethods = (string) $this->simpleXml->PaymentMethods;
            }
        }

        if ($this->paymentMethods === null and $default !== null) {
            return $default;
        }

        return $this->paymentMethods;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getGalleryUrl($default = null): ?string
    {
        if ($this->galleryUrl === null) {
            if (!empty($this->simpleXml->GalleryURL)) {
                $this->galleryUrl = (string) $this->simpleXml->GalleryURL;
            }
        }

        if ($this->galleryUrl === null and $default !== null) {
            return $default;
        }

        return $this->galleryUrl;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getPictureUrl($default = null): ?string
    {
        if ($this->pictureUrl === null) {
            if (!empty($this->simpleXml->PictureURL)) {
                $this->pictureUrl = (string) $this->simpleXml->PictureURL;
            }
        }

        if ($this->pictureUrl === null and $default !== null) {
            return $default;
        }

        return $this->pictureUrl;
    }
    /**
     * @param string|null $default
     * @return string
     */
    public function getPrimaryCategoryId(string $default = null): ?string
    {
        if ($this->primaryCategoryId === null) {
            if (!empty($this->simpleXml->PrimaryCategoryID)) {
                $this->primaryCategoryId = (string) $this->simpleXml->PrimaryCategoryID;
            }
        }

        if ($this->primaryCategoryId === null and $default !== null) {
            return $default;
        }

        return $this->primaryCategoryId;
    }
    /**
     * @param string|null $default
     * @return string
     */
    public function getPrimaryCategoryName($default = null): ?string
    {
        if ($this->primaryCategoryName === null) {
            if (!empty($this->simpleXml->PrimaryCategoryName)) {
                $this->primaryCategoryName = (string) $this->simpleXml->PrimaryCategoryName;
            }
        }

        if ($this->primaryCategoryName === null and $default !== null) {
            return $default;
        }

        return $this->primaryCategoryName;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getQuantity($default = null): ?string
    {
        if ($this->quantity === null) {
            if (!empty($this->simpleXml->Quantity)) {
                $this->quantity = (string) $this->simpleXml->Quantity;
            }
        }

        if ($this->quantity === null and $default !== null) {
            return $default;
        }

        return $this->quantity;
    }
    /**
     * @param null $default
     * @return SellerItem|null
     */
    public function getSeller($default = null): ?SellerItem
    {
        if (!$this->seller instanceof SellerItem) {
            if (!empty($this->simpleXml->Seller)) {
                $this->seller = new SellerItem($this->simpleXml->Seller);
            }
        }

        if ($this->seller === null and $default !== null) {
            return $default;
        }

        return $this->seller;
    }
    /**
     * @return PriceInfo
     */
    public function getPriceInfo(): PriceInfo
    {
        if (!$this->priceInfo instanceof PriceInfo) {
            $convertedCurrentPrice = null;
            $currentPrice = null;

            if (!empty($this->simpleXml->ConvertedCurrentPrice)) {
                $convertedCurrentPrice = new ConvertedCurrentPrice($this->simpleXml->ConvertedCurrentPrice);
            }

            if (!empty($this->simpleXml->CurrentPrice)) {
                $currentPrice = new CurrentPrice($this->simpleXml->CurrentPrice);
            }

            $this->priceInfo = new PriceInfo($convertedCurrentPrice, $currentPrice);
        }

        return $this->priceInfo;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getListingStatus($default = null): string
    {
        if ($this->listingStatus === null) {
            if (!empty($this->simpleXml->ListingStatus)) {
                $this->listingStatus = (string) $this->simpleXml->ListingStatus;
            }
        }

        if ($this->listingStatus === null and $default !== null) {
            return $default;
        }

        return $this->listingStatus;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getQuantitySold($default = null): string
    {
        if ($this->quantitySold === null) {
            if (!empty($this->simpleXml->QuantitySold)) {
                $this->quantitySold = (string) $this->simpleXml->QuantitySold;
            }
        }

        if ($this->quantitySold === null and $default !== null) {
            return $default;
        }

        return $this->quantitySold;
    }
    /**
     * @param null $default
     * @return array
     */
    public function getShipsToLocations($default = null): array
    {
        if ($this->shipsToLocations === null) {
            if (!empty($this->simpleXml->ShipToLocations)) {
                $shipToLocations = $this->simpleXml->ShipToLocations;

                foreach ($shipToLocations as $location) {
                    $this->shipsToLocations[] = (string) $location;
                }
            }
        }

        if ($this->shipsToLocations === null and $default !== null) {
            return $default;
        }

        return $this->shipsToLocations;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getSite($default = null): string
    {
        if ($this->site === null) {
            if (!empty($this->simpleXml->Site)) {
                $this->site = (string) $this->simpleXml->Site;
            }
        }

        if ($this->site === null and $default !== null) {
            return $default;
        }

        return $this->site;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getTimeLeft($default = null): string
    {
        if ($this->timeLeft === null) {
            if (!empty($this->simpleXml->TimeLeft)) {
                $this->timeLeft = (string) $this->simpleXml->TimeLeft;
            }
        }

        if ($this->timeLeft === null and $default !== null) {
            return $default;
        }

        return $this->timeLeft;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getTitle($default = null): string
    {
        if ($this->title === null) {
            if (!empty($this->simpleXml->Title)) {
                $this->title = (string) $this->simpleXml->Title;
            }
        }

        if ($this->title === null and $default !== null) {
            return $default;
        }

        return $this->title;
    }
    /**
     * @param null $default
     * @return ShippingCostSummary
     */
    public function getShippingCostSummary($default = null): ShippingCostSummary
    {
        if (!$this->shippingCostSummary instanceof ShippingCostSummary) {
            if (!empty($this->simpleXml->ShippingCostSummary)) {
                $this->shippingCostSummary = new ShippingCostSummary($this->simpleXml->ShippingCostSummary);
            }
        }

        if ($this->shippingCostSummary === null and $default !== null) {
            return $default;
        }

        return $this->shippingCostSummary;
    }
    /**
     * @param null $default
     * @return ItemSpecifics
     */
    public function getItemSpecifics($default = null): ItemSpecifics
    {
        if (!$this->itemSpecifics instanceof ItemSpecifics) {
            if (!empty($this->simpleXml->ItemSpecifics)) {
                $this->itemSpecifics = new ItemSpecifics($this->simpleXml->ItemSpecifics);
            }
        }

        if ($this->itemSpecifics === null and $default !== null) {
            return $default;
        }

        return $this->itemSpecifics;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'itemId' => $this->getItemId(),
            'title' => $this->getTitle(),
            'bestOfferEnabled' => $this->getBestOfferEnabled(),
            'description' => $this->getDescription(),
            'startTime' => $this->getStartTime(),
            'startTimeApplicationFormat' => $this->getStartTimeObject()->format(Util::getDateTimeApplicationFormat()),
            'endTime' => $this->getEndTime(),
            'endTimeApplicationFormat' => $this->getEndTimeObject()->format(Util::getDateTimeApplicationFormat()),
            'viewItemUrlForNaturalSearch' => $this->getViewItemUrlForNaturalSearch(),
            'listingType' => $this->getListingType(),
            'shipsToLocations' => $this->getShipsToLocations(),
            'location' => $this->getLocation(),
            'paymentMethods' => $this->getPaymentMethods(),
            'galleryUrl' => $this->getGalleryUrl(),
            'pictureUrl' => $this->getPictureUrl(),
            'primaryCategoryId' => $this->getPrimaryCategoryId(),
            'primaryCategoryName' => $this->getPrimaryCategoryName(),
            'quantity' => $this->getQuantity(),
            'seller' => $this->getSeller()->toArray(),
        ];
    }
}