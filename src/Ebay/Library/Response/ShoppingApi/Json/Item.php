<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class Item implements ArrayNotationInterface
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
     * @var bool|null $bestOfferEnabled
     */
    private $bestOfferEnabled;
    /**
     * @var int|null $hitCount
     */
    private $hitCount;
    /**
     * @var int|null $handlingTime
     */
    private $handlingTime;
    /**
     * @var bool|null $globalShipping
     */
    private $globalShipping;
    /**
     * @var bool|null $eligibleForPickupDropOff
     */
    private $eligibleForPickupDropOff;
    /**
     * @var bool|null $availableForPickupDropOff
     */
    private $availableForPickupDropOff;
    /**
     * @var bool $buyItNowAvailable
     */
    private $buyItNowAvailable;
    /**
     * @var array $country
     */
    private $country;
    /**
     * @var int|null $bidCount
     */
    private $bidCount;
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
     * @var array|null $paymentMethods
     */
    private $paymentMethods;
    /**
     * @var string|null $galleryUrl
     */
    private $galleryUrl;
    /**
     * @var array|null $pictureUrl
     */
    private $pictureUrl;
    /**
     * @var string|null $quantity
     */
    private $quantity;
    /**
     * @var array|null
     */
    private $seller;
    /**
     * @var string $listingStatus
     */
    private $listingStatus;
    /**
     * @var string|null $quantitySold
     */
    private $quantitySold;
    /**
     * @var array|null $shipsToLocations
     */
    private $shipsToLocations;
    /**
     * @var bool|null $autoPay
     */
    private $autoPay;
    /**
     * @var array|null $excludeShipsToLocations
     */
    private $excludeShipsToLocations;
    /**
     * @var array|null
     */
    private $convertedCurrentPrice;
    /**
     * @var array
     */
    private $currentPrice;
    /**
     * @var null|array $condition
     */
    private $condition;
    /**
     * @var array|null $storeFront
     */
    private $storeFront;

    public function __construct(
        string $itemId,
        string $title,
        string $description,
        array $country,
        string $startTime,
        string $endTime,
        string $viewItemUrlForNaturalSearch,
        string $listingType,
        string $location,
        ?array $paymentMethods,
        ?string $galleryUrl,
        ?array $pictureUrl,
        ?int $quantity,
        ?array $sellerInfo,
        ?int $bidCount,
        ?array $convertedCurrentPrice,
        array $currentPrice,
        string $listingStatus,
        ?int $quantitySold,
        ?array $shipsToLocations,
        ?int $hitCount,
        ?bool $autoPay,
        ?int $handlingTime,
        ?array $condition,
        ?bool $globalShipping,
        ?bool $availableForPickupDropOff,
        ?bool $eligibleForPickupDropOff,
        ?bool $bestOfferEnabled,
        ?bool $buyItNowAvailable,
        ?array $storeFront
    ) {
        $this->itemId = $itemId;
        $this->title = $title;
        $this->country = $country;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->viewItemUrlForNaturalSearch = $viewItemUrlForNaturalSearch;
        $this->listingType = $listingType;
        $this->location = $location;
        $this->paymentMethods = $paymentMethods;
        $this->galleryUrl = $galleryUrl;
        $this->pictureUrl = $pictureUrl;
        $this->quantity = $quantity;
        $this->seller = $sellerInfo;
        $this->bidCount = $bidCount;
        $this->convertedCurrentPrice = $convertedCurrentPrice;
        $this->currentPrice = $currentPrice;
        $this->listingStatus = $listingStatus;
        $this->quantitySold = $quantitySold;
        $this->shipsToLocations = $shipsToLocations;
        $this->hitCount = $hitCount;
        $this->autoPay = $autoPay;
        $this->handlingTime = $handlingTime;
        $this->condition = $condition;
        $this->globalShipping = $globalShipping;
        $this->availableForPickupDropOff = $availableForPickupDropOff;
        $this->eligibleForPickupDropOff = $eligibleForPickupDropOff;
        $this->description = $description;
        $this->bestOfferEnabled = $bestOfferEnabled;
        $this->buyItNowAvailable = $buyItNowAvailable;
        $this->storeFront = $storeFront;
    }
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    /**
     * @return bool|null
     */
    public function getBestOfferEnabled(): ?bool
    {
        return $this->bestOfferEnabled;
    }
    /**
     * @return int|null
     */
    public function getHitCount(): ?int
    {
        return $this->hitCount;
    }
    /**
     * @return int|null
     */
    public function getHandlingTime(): ?int
    {
        return $this->handlingTime;
    }
    /**
     * @return bool|null
     */
    public function getGlobalShipping(): ?bool
    {
        return $this->globalShipping;
    }
    /**
     * @return bool|null
     */
    public function getEligibleForPickupDropOff(): ?bool
    {
        return $this->eligibleForPickupDropOff;
    }
    /**
     * @return bool|null
     */
    public function getAvailableForPickupDropOff(): ?bool
    {
        return $this->availableForPickupDropOff;
    }
    /**
     * @return bool
     */
    public function isBuyItNowAvailable(): bool
    {
        return $this->buyItNowAvailable;
    }
    /**
     * @return array
     */
    public function getCountry(): array
    {
        return $this->country;
    }
    /**
     * @return int|null
     */
    public function getBidCount(): ?int
    {
        return $this->bidCount;
    }
    /**
     * @return string
     */
    public function getViewItemUrlForNaturalSearch(): string
    {
        return $this->viewItemUrlForNaturalSearch;
    }
    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }
    /**
     * @return array|null
     */
    public function getPaymentMethods(): ?array
    {
        return $this->paymentMethods;
    }
    /**
     * @return array|null
     */
    public function getPictureUrl(): ?array
    {
        return $this->pictureUrl;
    }
    /**
     * @return string|null
     */
    public function getQuantity(): ?string
    {
        return $this->quantity;
    }
    /**
     * @return array|null
     */
    public function getSeller(): ?SellerInfo
    {
        if ($this->seller instanceof SellerInfo) {
            return $this->seller;
        }

        if (empty($this->seller)) {
            return null;
        }

        $this->seller = new SellerInfo(
            $this->seller['UserID'],
            $this->seller['FeedbackRatingStar'],
            $this->seller['FeedbackScore'],
            $this->seller['PositiveFeedbackPercent'],
            null
        );

        return $this->seller;
    }
    /**
     * @return string
     */
    public function getListingStatus(): string
    {
        return $this->listingStatus;
    }
    /**
     * @return string|null
     */
    public function getQuantitySold(): ?string
    {
        return $this->quantitySold;
    }
    /**
     * @return array|null
     */
    public function getShipsToLocations(): ?array
    {
        return $this->shipsToLocations;
    }
    /**
     * @return bool|null
     */
    public function getAutoPay(): ?bool
    {
        return $this->autoPay;
    }
    /**
     * @return array|null
     */
    public function getExcludeShipsToLocations(): ?array
    {
        return $this->excludeShipsToLocations;
    }
    /**
     * @return array|null
     */
    public function getConvertedCurrentPrice(): ?BasePrice
    {
        if ($this->convertedCurrentPrice instanceof BasePrice) {
            return $this->convertedCurrentPrice;
        }

        if (empty($this->convertedCurrentPrice)) {
            return null;
        }

        $this->convertedCurrentPrice = new BasePrice(
            $this->convertedCurrentPrice['CurrencyID'],
            to_float($this->convertedCurrentPrice['Value'])
        );

        return $this->convertedCurrentPrice;
    }
    /**
     * @return BasePrice|null
     */
    public function getCurrentPrice(): ?BasePrice
    {
        if ($this->currentPrice instanceof BasePrice) {
            return $this->currentPrice;
        }

        if (empty($this->currentPrice)) {
            return null;
        }

        $this->currentPrice = new BasePrice(
            $this->currentPrice['CurrencyID'],
            to_float($this->currentPrice['Value'])
        );

        return $this->currentPrice;
    }
    /**
     * @return Condition|null
     */
    public function getCondition(): ?Condition
    {
        if ($this->condition instanceof Condition) {
            return $this->condition;
        }

        if (empty($this->condition)) {
            return null;
        }

        $this->condition = new Condition(
            $this->condition['ConditionId'],
            $this->condition['ConditionDisplayName'],
            $this->condition['ConditionDescription']
        );

        return $this->condition;
    }
    /**
     * @return array|null
     */
    public function getStoreFront(): ?array
    {
        return $this->storeFront;
    }
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }
    /**
     * @return bool
     */
    public function isFixedPrice(): bool
    {
        return $this->listingType === 'FixedPriceItem';
    }
    /**
     * @return bool
     */
    public function isAuction(): bool
    {
        if ($this->bidCount !== null) {
            return is_int($this->bidCount);
        }

        return false;
    }
    /**
     * @return string
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }
    /**
     * @return string
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }
    /**
     * @return \DateTime
     */
    public function getStartTimeObject(): \DateTime
    {
        return Util::toDateTime($this->getStartTime());
    }
    /**
     * @return \DateTime
     */
    public function getEndTimeObject(): \DateTime
    {
        return Util::toDateTime($this->getEndTime());
    }

    /**
     * @return string
     */
    public function getListingType(): string
    {
        return $this->listingType;
    }
    /**
     * @return string|null
     */
    public function getGalleryUrl(): ?string
    {
        return $this->galleryUrl;
    }
    /**
     * @return array|null
     */
    public function getShipToLocations(): ?array
    {
        return $this->shipsToLocations;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'convertedCurrentPrice' => ($this->getConvertedCurrentPrice() instanceof BasePrice) ? $this->getConvertedCurrentPrice()->toArray() : null,
            'currentPrice' => ($this->getCurrentPrice() instanceof BasePrice) ? $this->getCurrentPrice()->toArray() : null,
            'condition' => ($this->getCondition() instanceof Condition) ? $this->getCondition()->toArray() : null,
            'itemId' => $this->getItemId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'country' => $this->getCountry(),
            'startTime' => Util::formatFromDate($this->getStartTimeObject()),
            'endTime' => Util::formatFromDate($this->getEndTimeObject()),
            'viewItemUrl' => $this->getViewItemUrlForNaturalSearch(),
            'listingType' => $this->getListingType(),
            'location' => $this->getLocation(),
            'paymentMethods' => $this->getPaymentMethods(),
            'galleryUrl' => $this->getGalleryUrl(),
            'pictureUrl' => $this->getPictureUrl(),
            'quantity' => $this->getQuantity(),
            'seller' => ($this->getSeller() instanceof SellerInfo) ? $this->getSeller()->toArray() : null,
            'bidCount' => $this->getBidCount(),
            'shipToLocations' => $this->getShipsToLocations(),
        ];
    }
}