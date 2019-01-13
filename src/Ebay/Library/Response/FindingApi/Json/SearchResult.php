<?php

namespace App\Ebay\Library\Response\FindingApi\Json;

use App\Ebay\Library\Information\ISO3166CountryCodeInformation;
use App\Ebay\Library\Response\FindingApi\Json\Result\BasePrice;
use App\Ebay\Library\Response\FindingApi\Json\Result\Condition;
use App\Ebay\Library\Response\FindingApi\Json\Result\ListingInfo;
use App\Ebay\Library\Response\FindingApi\Json\Result\SellerInfo;
use App\Ebay\Library\Response\FindingApi\Json\Result\SellingStatus;
use App\Ebay\Library\Response\FindingApi\Json\Result\ShippingInfo;
use App\Ebay\Library\Response\FindingApi\Json\Result\StoreInfo;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

/**
 * Class SearchResult
 * @package App\Ebay\Library\Response\FindingApi\Json
 */
class SearchResult implements ArrayNotationInterface
{
    /**
     * @var string
     */
    private $itemId;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $globalId;
    /**
     * @var string|null
     */
    private $galleryUrl;
    /**
     * @var string
     */
    private $viewItemUrl;
    /**
     * @var array
     */
    private $paymentMethod;
    /**
     * @var bool
     */
    private $autoPay;
    /**
     * @var string
     */
    private $postalCode;
    /**
     * @var string
     */
    private $location;
    /**
     * @var string
     */
    private $country;
    /**
     * @var array
     */
    private $shippingInfo;
    /**
     * @var array
     */
    private $sellingStatus;
    /**
     * @var array $listingInfo
     */
    private $listingInfo;
    /**
     * @var bool $returnsAccepted
     */
    private $returnsAccepted;
    /**
     * @var array $condition
     */
    private $condition;
    /**
     * @var bool $isMultiVariationListing
     */
    private $isMultiVariationListing;
    /**
     * @var bool $topRatedListing
     */
    private $topRatedListing;
    /**
     * @var array
     */
    private $storeInfo;
    /**
     * @var string
     */
    private $pictureUrlLarge;
    /**
     * @var string
     */
    private $pictureUrlSuperSize;
    /**
     * @var array $sellerInfo
     */
    private $sellerInfo;
    /**
     * @var string|null
     */
    private $subtitle;
    /**
     * SearchResult constructor.
     * @param string $itemId
     * @param string $title
     * @param string $globalId
     * @param string $galleryURL
     * @param string $viewItemURL
     * @param array|null $paymentMethod
     * @param bool $autoPay
     * @param string|null $postalCode
     * @param string $location
     * @param string $country
     * @param array $shippingInfo
     * @param array $sellingStatus
     * @param array $listingInfo
     * @param bool|null $returnsAccepted
     * @param array|null $condition
     * @param bool $isMultiVariationListing
     * @param bool $topRatedListing
     * @param array|null $storeInfo
     * @param null|string $pictureUrlLarge
     * @param null|string $pictureUrlSuperSize
     * @param null|array $sellerInfo
     * @param string|null $subtitle
     */
    public function __construct(
        string $itemId,
        string $title,
        ?string $subtitle,
        string $globalId,
        ?string $galleryURL,
        string $viewItemURL,
        ?array $paymentMethod,
        bool $autoPay,
        ?string $postalCode,
        string $location,
        string $country,
        array $shippingInfo,
        array $sellingStatus,
        array $listingInfo,
        ?bool $returnsAccepted,
        ?array $condition,
        bool $isMultiVariationListing,
        bool $topRatedListing,
        ?array $storeInfo,
        ?string $pictureUrlLarge,
        ?string $pictureUrlSuperSize,
        ?array $sellerInfo
    ) {
        $this->itemId = $itemId;
        $this->globalId = $globalId;
        $this->galleryUrl = $galleryURL;
        $this->viewItemUrl = $viewItemURL;
        $this->title = $title;
        $this->paymentMethod = $paymentMethod;
        $this->autoPay = $autoPay;
        $this->postalCode = $postalCode;
        $this->location = $location;
        $this->country = $country;
        $this->shippingInfo = $shippingInfo;
        $this->listingInfo = $listingInfo;
        $this->sellingStatus = $sellingStatus;
        $this->returnsAccepted = $returnsAccepted;
        $this->condition = $condition;
        $this->isMultiVariationListing = $isMultiVariationListing;
        $this->topRatedListing = $topRatedListing;
        $this->storeInfo = $storeInfo;
        $this->pictureUrlLarge = $pictureUrlLarge;
        $this->pictureUrlSuperSize = $pictureUrlSuperSize;
        $this->sellerInfo = $sellerInfo;
        $this->subtitle = $subtitle;
    }
    /**
     * @return array|null
     */
    public function getPaymentMethod(): ?array
    {
        return $this->paymentMethod;
    }
    /**
     * @return bool
     */
    public function isAutoPay(): bool
    {
        return $this->autoPay;
    }
    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }
    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }
    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }
    /**
     * @return ShippingInfo
     */
    public function getShippingInfo(): ShippingInfo
    {
        if ($this->shippingInfo instanceof ShippingInfo) {
            return $this->shippingInfo;
        }

        $this->shippingInfo = new ShippingInfo(
            $this->shippingInfo['shippingType'][0],
            $this->shippingInfo['shipToLocations'],
            (isset($this->shippingInfo['expeditedShipping'])) ? stringToBool($this->shippingInfo['expeditedShipping'][0]) : null,
            (isset($this->shippingInfo['oneDayShippingAvailable'])) ? stringToBool($this->shippingInfo['oneDayShippingAvailable'][0]) : null,
            (isset($this->shippingInfo['handlingTime'])) ? (int) $this->shippingInfo['expeditedShipping'][0] : null
        );

        return $this->shippingInfo;
    }
    /**
     * @return SellingStatus
     */
    public function getSellingStatus(): SellingStatus
    {
        if ($this->sellingStatus instanceof SellingStatus) {
            return $this->sellingStatus;
        }

        $this->sellingStatus = new SellingStatus(
            new BasePrice($this->sellingStatus['currentPrice'][0]),
            new BasePrice($this->sellingStatus['convertedCurrentPrice'][0]),
            $this->sellingStatus['sellingState'][0],
            $this->sellingStatus['timeLeft'][0]
        );

        return $this->sellingStatus;
    }
    /**
     * @return ListingInfo
     */
    public function getListingInfo(): ListingInfo
    {
        if ($this->listingInfo instanceof ListingInfo) {
            return $this->listingInfo;
        }

        $this->listingInfo = new ListingInfo(
            stringToBool($this->listingInfo['bestOfferEnabled'][0]),
            stringToBool($this->listingInfo['buyItNowAvailable'][0]),
            $this->listingInfo['startTime'][0],
            $this->listingInfo['endTime'][0],
            $this->listingInfo['listingType'][0],
            stringToBool($this->listingInfo['gift'][0]),
            (isset($this->listingInfo['watchCount'])) ? (int) $this->listingInfo['watchCount'][0] : null
        );

        return $this->listingInfo;
    }
    /**
     * @return StoreInfo|null
     */
    public function getStoreInfo(): ?StoreInfo
    {
        if ($this->storeInfo instanceof StoreInfo) {
            return $this->storeInfo;
        }

        if (empty($this->storeInfo)) {
            return null;
        }

        $this->storeInfo = new StoreInfo(
            $this->storeInfo['storeName'][0],
            $this->storeInfo['storeURL'][0]
        );

        return $this->storeInfo;
    }

    public function getSellerInfo(): ?SellerInfo
    {
        if ($this->sellerInfo instanceof SellerInfo) {
            return $this->sellerInfo;
        }

        if (empty($this->sellerInfo)) {
            return null;
        }

        $this->sellerInfo = new SellerInfo(
            $this->sellerInfo['sellerUserName'][0],
            $this->sellerInfo['feedbackScore'][0],
            $this->sellerInfo['positiveFeedbackPercent'][0],
            $this->sellerInfo['feedbackRatingStar'][0],
            stringToBool($this->sellerInfo['topRatedSeller'][0])
        );

        return $this->sellerInfo;
    }
    /**
     * @return bool|null
     */
    public function isReturnsAccepted(): ?bool
    {
        return $this->returnsAccepted;
    }
    /**
     * @return array|null
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
            $this->condition['conditionId'][0],
            $this->condition['conditionDisplayName'][0]
        );

        return $this->condition;
    }
    /**
     * @return bool
     */
    public function isMultiVariationListing(): bool
    {
        return $this->isMultiVariationListing;
    }
    /**
     * @return bool
     */
    public function isTopRatedListing(): bool
    {
        return $this->topRatedListing;
    }
    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    /**
     * @return string
     */
    public function getGlobalId(): string
    {
        return $this->globalId;
    }
    /**
     * @return string|null
     */
    public function getGalleryUrl(): ?string
    {
        return $this->galleryUrl;
    }
    /**
     * @return string
     */
    public function getViewItemUrl(): string
    {
        return $this->viewItemUrl;
    }
    /**
     * @return string|null
     */
    public function getPictureUrlLarge(): ?string
    {
        return $this->pictureUrlLarge;
    }
    /**
     * @return string|null
     */
    public function getPictureUrlSuperSize(): ?string
    {
        return $this->pictureUrlSuperSize;
    }
    /**
     * @return string|null
     */
    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        $country = ISO3166CountryCodeInformation::instance()->get($this->getCountry());

        return [
            'itemId' => $this->getItemId(),
            'title' => $this->getTitle(),
            'subtitle' => $this->getSubtitle(),
            'globalId' => $this->getGlobalId(),
            'galleryUrl' => $this->getGalleryUrl(),
            'viewItemUrl' => $this->getViewItemUrl(),
            'paymentMethod' => $this->getPaymentMethod(),
            'autoPay' => $this->isAutoPay(),
            'postalCode' => $this->getPostalCode(),
            'location' => $this->getLocation(),
            'country' => $country,
            'shippingInfo' => $this->getShippingInfo()->toArray(),
            'sellingStatus' => $this->getSellingStatus()->toArray(),
            'listingInfo' => $this->getListingInfo()->toArray(),
            'returnsAccepted' => $this->isReturnsAccepted(),
            'condition' => ($this->getCondition() instanceof Condition) ? $this->getCondition()->toArray() : null,
            'multiVariationListing' => $this->isMultiVariationListing,
            'topRatedListing' => $this->isTopRatedListing(),
            'storeInfo' => ($this->getStoreInfo() instanceof StoreInfo) ? $this->getStoreInfo()->toArray() : null,
            'pictureUrlLarge' => $this->getPictureUrlLarge(),
            'pictureUrlSuperSize' => $this->getPictureUrlSuperSize(),
        ];
    }
    /**
     * @param array $item
     * @return SearchResult
     */
    public static function createFromResponseArray(array $item): SearchResult
    {
        return new SearchResult(
            $item['itemId'][0],
            $item['title'][0],
            (isset($item['subtitle'])) ? $item['subtitle'][0] : null,
            $item['globalId'][0],
            (isset($item['galleryURL'])) ? $item['galleryURL'][0] : null,
            $item['viewItemURL'][0],
            (isset($item['paymentMethod'])) ? $item['paymentMethod'] : null,
            stringToBool($item['autoPay'][0]),
            (isset($item['postalCode'])) ? $item['postalCode'][0] : null,
            $item['location'][0],
            $item['country'][0],
            $item['shippingInfo'][0],
            $item['sellingStatus'][0],
            $item['listingInfo'][0],
            (isset($item['returnsAccepted'])) ? $item['returnsAccepted'][0] : null,
            (isset($item['condition'])) ? $item['condition'][0] : null,
            stringToBool($item['isMultiVariationListing'][0]),
            stringToBool($item['topRatedListing'][0]),
            (isset($item['storeInfo'])) ? $item['storeInfo'][0] : null,
            (isset($item['pictureURLLarge'])) ? $item['pictureURLLarge'][0] : null,
            (isset($item['pictureURLSuperSize'])) ? $item['pictureURLSuperSize'][0] : null,
            (isset($item['sellerInfo'])) ? $item['sellerInfo'][0] : null
        );
    }
}