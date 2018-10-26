<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Ebay\Library\Response\FindingApi\ResponseItem\AbstractItemIterator;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\{
    Attribute, Condition, DiscountPriceInfo, ShippingInfo, ListingInfo, SellingStatus, Category
};

class Item extends AbstractItemIterator implements ArrayNotationInterface
{
    /**
     * @var UnitPrice $unitPrice
     */
    private $unitPrice;
    /**
     * @var string $subtitle
     */
    private $subtitle;
    /**
     * @var StoreInfo $storeInfo
     */
    private $storeInfo;
    /**
     * @var SellerInfo $sellerInfo
     */
    private $sellerInfo;
    /**
     * @var string $pictureUrlSuperSize
     */
    private $pictureUrlSuperSize;
    /**
     * @var string $pictureUrlLarge
     */
    private $pictureUrlLarge;
    /**
     * @var array $galleryPlusPictureUrl
     */
    private $galleryPlusPictureUrl;
    /**
     * @var GalleryInfoContainer $galleryInfoContainer
     */
    private $galleryInfoContainer;
    /**
     * @var array $eekStatus
     */
    private $eekStatus;
    /**
     * @var array $distance
     */
    private $distance;
    /**
     * @var DiscountPriceInfo $discountPriceInfo
     */
    private $discountPriceInfo;
    /**
     * @var string $compatibility
     */
    private $compatibility;
    /**
     * @var string $charityId
     */
    private $charityId;
    /**
     * @var Attribute[] $attributes
     */
    private $attributes;
    /**
     * @var Condition $condition
     */
    private $condition;
    /**
     * @var bool $topRatedListing
     */
    private $topRatedListing;
    /**
     * @var bool $isMultiVariationListing
     */
    private $isMultiVariationListing;
    /**
     * @var bool $returnsAccepted
     */
    private $returnsAccepted;
    /**
     * @var ListingInfo $listingInfo
     */
    private $listingInfo;
    /**
     * @var SellingStatus $sellingStatus
     */
    private $sellingStatus;
    /**
     * @var ShippingInfo $shippingInfo
     */
    private $shippingInfo;
    /**
     * @var string $country
     */
    private $country;
    /**
     * @var string $location
     */
    private $location;
    /**
     * @var int $postalCode
     */
    private $postalCode;
    /**
     * @var bool $autoPay
     */
    private $autoPay;
    /**
     * @var string $paymentMethod
     */
    private $paymentMethods;
    /**
     * @var array $productId
     */
    private $productId;
    /**
     * @var string $viewItemUrl
     */
    private $viewItemUrl;
    /**
     * @var string $galleryUrl
     */
    private $galleryUrl;
    /**
     * @var Category $primaryCategory
     */
    private $primaryCategory;
    /**
     * @var Category $secondaryCategory
     */
    private $secondaryCategory;
    /**
     * @var string $globalId
     */
    private $globalId;
    /**
     * @var string $title
     */
    private $title;
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * @return string
     */
    public function getItemId(): string
    {
        if ($this->itemId === null) {
            $this->setItemId((string)$this->simpleXml->itemId);
        }

        return $this->itemId;
    }
    /**
     * @return string
     */
    public function getTitle() : string
    {
        if ($this->title === null) {
            $this->setTitle((string)$this->simpleXml->{'title'});
        }

        return $this->title;
    }
    /**
     * @return string
     */
    public function getGlobalId() : string
    {
        if ($this->globalId === null) {
            $this->setGlobalId((string)$this->simpleXml->globalId);
        }

        return $this->globalId;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getSubtitle($default = null)
    {
        if ($this->subtitle === null) {
            if (!empty($this->simpleXml->subtitle)) {
                $this->setSubtitle((string) $this->simpleXml->subtitle);
            }
        }

        if ($this->subtitle === null and $default !== null) {
            return $default;
        }

        return $this->subtitle;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getPictureURLSuperSize($default = null)
    {
        if ($this->pictureUrlSuperSize === null) {
            if (!empty($this->simpleXml->pictureURLSuperSize)) {
                $this->setPictureURLSuperSize((string) $this->simpleXml->pictureURLSuperSize);
            }
        }

        if ($this->pictureUrlSuperSize === null and $default !== null) {
            return $default;
        }

        return $this->pictureUrlSuperSize;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getPictureURLLarge($default = null)
    {
        if ($this->pictureUrlLarge === null) {
            if (!empty($this->simpleXml->pictureURLLarge)) {
                $this->setPictureURLLarge((string) $this->simpleXml->pictureURLLarge);
            }
        }

        if ($this->pictureUrlLarge === null and $default !== null) {
            return $default;
        }

        return $this->pictureUrlLarge;
    }
    /**
     * @param $default
     * @return array|null
     */
    public function getGalleryPlusPictureURL($default = null)
    {
        if ($this->galleryPlusPictureUrl === null) {
            if (!empty($this->simpleXml->galleryPlusPictureURL)) {
                $url = array();
                foreach ($this->simpleXml->galleryPlusPictureURL as $galleryUrl) {
                    $url[] = (string) $galleryUrl;
                }

                $this->setGalleryPlusPictureURL($url);
            }
        }

        if ($this->galleryPlusPictureUrl === null and $default !== null) {
            return $default;
        }

        return $this->galleryPlusPictureUrl;
    }
    /**
     * @param null $default
     * @return GalleryInfoContainer|null
     */
    public function getGalleryContainer($default = null)
    {
        if ($this->galleryInfoContainer === null) {
            if (!empty($this->simpleXml->galleryInfoContainer)) {
                $galleryUrlContainer = new GalleryInfoContainer($this->simpleXml->galleryInfoContainer);
                $this->setGalleryInfoContainer($galleryUrlContainer);
            }
        }

        if ($this->galleryInfoContainer === null and $default !== null) {
            return $default;
        }

        return $this->galleryInfoContainer;
    }
    /**
     * @param null $default
     * @return array|null
     */
    public function getEekStatus($default = null)
    {
        if ($this->eekStatus === null) {
            if (!empty($this->simpleXml->eekStatus)) {
                foreach ($this->simpleXml->eekStatus as $eekStatus) {
                    $this->setEekStatus((string) $eekStatus);
                }
            }
        }

        if ($this->eekStatus === null and $default !== null) {
            return $default;
        }

        return $this->eekStatus;
    }
    /**
     * @param null $default
     * @return array|null
     */
    public function getDistance($default = null)
    {
        if ($this->distance === null) {
            if (!empty($this->simpleXml->distance)) {
                $this->setDistance((string) $this->simpleXml->distance['unit'], (float) $this->simpleXml->distance);
            }
        }

        if ($this->distance === null and $default !== null) {
            return $default;
        }

        return $this->distance;
    }
    /**
     * @param $default
     * @return mixed/Category
     */
    public function getPrimaryCategory($default = null): Category
    {
        if (!$this->primaryCategory instanceof Category) {
            if (!empty($this->simpleXml->primaryCategory)) {
                $this->setPrimaryCategory(new Category($this->simpleXml->primaryCategory));
            }
        }

        if (!$this->primaryCategory instanceof  Category and $default !== null) {
            return $default;
        }


        return $this->primaryCategory;
    }
    /**
     * @param null $default
     * @return \FindingAPI\Core\ResponseParser\ResponseItem\Child\Item\Category|null
     */
    public function getSecondaryCategory($default = null)
    {
        if (!$this->secondaryCategory instanceof Category) {
            if (!empty($this->simpleXml->secondaryCategory)) {
                $this->setSecondaryCategory(new Category($this->simpleXml->secondaryCategory));
            }
        }

        if (!$this->secondaryCategory instanceof Category and $default !== null) {
            return $default;
        }

        return $this->secondaryCategory;
    }
    /**
     * @param $default
     * @return ShippingInfo|null
     */
    public function getShippingInfo($default = null) : ?ShippingInfo
    {
        if (!$this->shippingInfo instanceof ShippingInfo) {
            if (!empty($this->simpleXml->shippingInfo)) {
                $this->setShippingInfo(new ShippingInfo($this->simpleXml->shippingInfo));
            }
        }

        if (!$this->shippingInfo instanceof ShippingInfo and $default !== null) {
            return $default;
        }

        return $this->shippingInfo;
    }

    /**
     * @param null $default
     * @return SellingStatus|null
     */
    public function getSellingStatus($default = null): ?SellingStatus
    {
        if ($this->sellingStatus === null) {
            if (!empty($this->simpleXml->sellingStatus)) {
                $this->setSellingStatus(new SellingStatus($this->simpleXml->sellingStatus));
            }
        }

        if ($this->sellingStatus === null and $default !== null) {
            return $default;
        }

        return $this->sellingStatus;
    }
    /**
     * @param null $default
     * @return SellerInfo|null
     */
    public function getSellerInfo($default = null): ?SellerInfo
    {
        if (!$this->sellerInfo instanceof SellerInfo) {
            if (!empty($this->simpleXml->sellerInfo)) {
                $this->setSellerInfo(new SellerInfo($this->simpleXml->sellerInfo));
            }
        }

        if (!$this->sellerInfo instanceof SellerInfo and $default !== null) {
            return $default;
        }

        return $this->sellerInfo;
    }
    /**
     * @param mixed $default
     * @return ListingInfo|null
     */
    public function getListingInfo($default = null): ?ListingInfo
    {
        if ($this->listingInfo === null) {
            if (!empty($this->simpleXml->listingInfo)) {
                $this->setListingInfo(new ListingInfo($this->simpleXml->listingInfo));
            }
        }

        if ($this->listingInfo === null and $default !== null) {
            return $default;
        }

        return $this->listingInfo;
    }
    /**
     * @param null $default
     * @return StoreInfo|null
     */
    public function getStoreInfo($default = null)
    {
        if (!$this->storeInfo instanceof StoreInfo) {
            if (!empty($this->simpleXml->storeInfo)) {
                $this->setStoreInfo(new StoreInfo($this->simpleXml->storeInfo));
            }
        }

        if (!$this->storeInfo instanceof StoreInfo and $default !== null) {
            return $default;
        }

        return $this->storeInfo;
    }
    /**
     * @param null $default
     * @return UnitPrice|null
     */
    public function getUnitPrice($default = null)
    {
        if (!$this->unitPrice instanceof UnitPrice) {
            if (!empty($this->simpleXml->unitPrice)) {
                $this->setUnitPrice(new UnitPrice($this->simpleXml->unitPrice));
            }
        }

        if (!$this->unitPrice instanceof UnitPrice and $default !== null) {
            return $default;
        }

        return $this->unitPrice;
    }
    /**
     * @param mixed $default
     * @return Condition|null
     */
    public function getCondition($default = null)
    {
        if ($this->condition === null) {
            if (!empty($this->simpleXml->condition)) {
                $this->setCondition(new Condition($this->simpleXml->condition));
            }
        }

        if ($this->condition === null and $default !== null) {
            return $default;
        }

        return $this->condition;
    }

    /**
     * @param mixed $default
     * @return string
     */
    public function getGalleryUrl($default = null)
    {
        if ($this->galleryUrl === null) {
            if (isset($this->simpleXml->galleryURL)) {
                $this->setGalleryUrl((string) $this->simpleXml->galleryURL);
            }
        }

        if ($this->galleryUrl === null and $default !== null) {
            return $default;
        }

        return $this->galleryUrl;
    }

    /**
     * @param mixed $default
     * @return string
     */
    public function getViewItemUrl($default = null) : string
    {
        if ($this->viewItemUrl === null) {
            if ($this->simpleXml->viewItemURL) {
                $this->setViewItemUrl((string)$this->simpleXml->viewItemURL);
            }
        }

        if ($this->viewItemUrl === null and $default !== null) {
            return $default;
        }

        return $this->viewItemUrl;
    }
    /**
     * @return array
     */
    public function getProductId() : array
    {
        if ($this->productId === null) {
            $this->setProductId((string) $this->simpleXml->productId['type'], (int) $this->simpleXml->productId);
        }

        return $this->productId;
    }
    /**
     * @return array
     */
    public function getPaymentMethod() : array
    {
        if ($this->paymentMethods === null) {
            $paymentMethods = array();
            foreach ($this->simpleXml->paymentMethod as $paymentMethod) {
                $paymentMethods[] = (string) $paymentMethod;
            }

            $this->setPaymentMethod($paymentMethods);
        }

        return $this->paymentMethods;
    }
    /**
     * @param mixed $default
     * @return bool|null
     */
    public function getAutoPay($default = null)
    {
        if ($this->autoPay === null) {
            if (!empty($this->simpleXml->autoPay)) {
                $this->setAutoPay((bool) $this->simpleXml->autoPay);
            }
        }
        
        if ($this->autoPay === null and $default !== null) {
            return $default;
        }

        return $this->autoPay;
    }

    /**
     * @param mixed $default
     * @return int|null
     */
    public function getPostalCode($default = null) 
    {
        if ($this->postalCode === null) {
            if (!empty($this->simpleXml->postalCode)) {
                $this->setPostalCode((int) $this->simpleXml->postalCode);
            }
        }

        if ($this->postalCode === null and $default !== null) {
            return $default;
        }

        return $this->postalCode;
    }
    /**
     * @param mixed $default
     * @return string
     */
    public function getLocation($default = null) : string
    {
        if ($this->location === null) {
            if (!empty($this->simpleXml->location)) {
                $this->setLocation((string) $this->simpleXml->location);
            }
        }

        if ($this->location === null and $default !== null) {
            return $default;
        }

        return $this->location;
    }
    /**
     * @param $default
     * @return string
     */
    public function getCountry($default = null) : string
    {
        if ($this->country === null) {
            if (!empty($this->simpleXml->country)) {
                $this->setCountry((string) $this->simpleXml->country);
            }
        }

        if ($this->country === null and $default !== null) {
            return $default;
        }

        return $this->country;
    }
    /**
     * @param null $default
     * @return bool|null
     */
    public function getReturnsAccepted($default = null) 
    {
        if ($this->returnsAccepted === null) {
            if (!empty($this->simpleXml->returnsAccepted)) {
                $this->setReturnsAccepted((bool) $this->simpleXml->returnsAccepted);
            }
        }

        if ($this->returnsAccepted === null and $default !== null) {
            return $default;
        }

        return $this->returnsAccepted;
    }
    /**
     * @param null $default
     * @return bool|null
     */
    public function getIsMultiVariationListing($default = null) 
    {
        if ($this->isMultiVariationListing === null) {
            if (!empty($this->simpleXml->isMultiVariationListing)) {
                $this->setIsMultiVariationListing((bool) $this->simpleXml->isMultiVariationListing);
            }
        }

        if ($this->isMultiVariationListing === null and $default !== null) {
            return $default;
        } 

        return $this->isMultiVariationListing;
    }
    /**
     * @param null $default
     * @return bool|null
     */
    public function getTopRatedListing($default = null) 
    {
        if ($this->topRatedListing === null) {
            if (!empty($this->simpleXml->topRatedListing)) {
                $this->setTopRatedListing((bool) $this->simpleXml->topRatedListing);
            }
        }

        if ($this->topRatedListing === null and $default !== null) {
            return $default;
        }

        return $this->topRatedListing;
    }
    /**
     * @param null $default
     * @return Attribute[]|null
     */
    public function getAttributes($default = null)
    {
        if ($this->attributes === null) {
            if (!empty($this->simpleXml->attribute)) {
                foreach ($this->attribute as $attr) {
                    $this->setAttribute(new Attribute($attr));
                }
            }
        }

        if ($this->attributes === null and $default !== null) {
            return $default;
        }

        return $this->attributes;
    }
    /**
     * @param null $default
     * @return null
     */
    public function getCharityId($default = null) 
    {
        if ($this->charityId === null) {
            if (!empty($this->simpleXml->charityId)) {
                $this->setCharityId((string) $this->simpleXml->charityId);
            }
        }

        if ($this->charityId === null and $default !== null) {
            return $default;
        }

        return $this->charityId;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getCompatibility($default = null)
    {
        if ($this->compatibility === null) {
            if (!empty($this->simpleXml->compatibility)) {
                $this->setCompatibility((string) $this->simpleXml->compatibility);
            }
        }

        if ($this->compatibility === null and $default !== null) {
            return $default;
        }

        return $this->compatibility;
    }
    /**
     * @param null $default
     * @return DiscountPriceInfo|null
     */
    public function getDiscountPriceInfo($default = null)
    {
        if (!$this->discountPriceInfo instanceof DiscountPriceInfo) {
            //var_dump($this->simpleXml->discountPriceInfo->originalRetailPrice);
            if (!empty($this->simpleXml->discountPriceInfo)) {
                $this->setDiscountPriceInfo(new DiscountPriceInfo($this->simpleXml->discountPriceInfo));
            }
        }

        if (!$this->discountPriceInfo instanceof DiscountPriceInfo and $default !== null) {
            return $default;
        }

        return $this->discountPriceInfo;
    }
    /**
     * @param \Closure $closure
     * @return mixed
     */
    public function dynamicSingleItemChoice(\Closure $closure)
    {
        return $closure->__invoke($this);
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        $toArray = array(
            'itemId' => $this->getItemId(),
            'title' => $this->getTitle(),
            'subtitle' => $this->getSubtitle(),
            'storeInfo' => ($this->getStoreInfo() instanceof StoreInfo) ?
                            $this->getStoreInfo()->toArray() :
                            null,
            'unitPrice' => ($this->getUnitPrice() instanceof UnitPrice) ?
                            $this->getUnitPrice()->toArray() :
                            null,
            'sellerInfo' => ($this->getSellerInfo() instanceof SellerInfo) ?
                            $this->getSellerInfo()->toArray() :
                            null,
            'pictureUrlSuperSize' => $this->getPictureURLSuperSize(),
            'galleryPlusPictureUrl' => $this->getGalleryPlusPictureURL(),
            'pictureUrlLarge' => $this->getPictureURLLarge(),
            'galleryInfoContainer' => ($this->getGalleryContainer() instanceof GalleryInfoContainer) ?
                                        $this->getGalleryContainer()->toArray() :
                                        null,
            'eekStatus' => $this->getEekStatus(),
            'distance' => $this->getDistance(),
            'discountPriceInfo' => ($this->getDiscountPriceInfo() instanceof DiscountPriceInfo) ?
                                    $this->getDiscountPriceInfo()->toArray() :
                                    null,
            'compatibility' => $this->getCompatibility(),
            'charityId' => $this->getCharityId(),
            'primaryCategory' => ($this->getPrimaryCategory() instanceof Category) ?
                                    $this->getPrimaryCategory()->toArray() :
                                    null,
            'secondaryCategory' => ($this->getSecondaryCategory() instanceof Category) ?
                                    $this->getSecondaryCategory()->toArray() :
                                    null,
            'condition' => ($this->getCondition() instanceof Condition) ?
                            $this->getCondition()->toArray() :
                            null,
            'topRatedListing' => $this->getTopRatedListing(),
            'multiVariationListing' => $this->getIsMultiVariationListing(),
            'returnsAccepted' => $this->getReturnsAccepted(),
            'listingInfo' => ($this->getListingInfo() instanceof ListingInfo) ?
                                $this->getListingInfo()->toArray() :
                                null,
            'sellingStatus' => ($this->getSellingStatus() instanceof SellingStatus) ?
                                $this->getSellingStatus()->toArray() :
                                null,
            'shippingInfo' => ($this->getShippingInfo() instanceof ShippingInfo) ?
                                $this->getShippingInfo()->toArray() :
                                null,
            'country' => $this->getCountry(),
            'location' => $this->getLocation(),
            'postalCode' => $this->getPostalCode(),
            'autoPay' => $this->getAutoPay(),
            'paymentMethods' => $this->getPaymentMethod(),
            'productId' => $this->getProductId(),
            'viewItemUrl' => $this->getViewItemUrl(),
            'galleryUrl' => $this->getGalleryUrl(),
            'globalId' => $this->getGlobalId(),
        );

        $toArray['attributes'] = array();
        $attributes = $this->getAttributes();

        if (is_array($attributes)) {
            foreach ($attributes as $attribute) {
                $toArray['attributes'][] = $attribute->toArray();
            }
        }

        return $toArray;
    }

    private function setDiscountPriceInfo(DiscountPriceInfo $discountPriceInfo) : Item 
    {
        $this->discountPriceInfo = $discountPriceInfo;

        return $this;
    }

    private function setSellingStatus(SellingStatus $sellingStatus) : Item
    {
        $this->sellingStatus = $sellingStatus;

        return $this;
    }

    private function setLocation(string $location) : Item
    {
        $this->location = $location;

        return $this;
    }

    private function setCountry(string $country) : Item
    {
        $this->country = $country;

        return $this;
    }

    private function setPostalCode(int $postalCode) : Item
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    private function setPrimaryCategory(Category $primaryCategory) : Item
    {
        $this->primaryCategory = $primaryCategory;

        return $this;
    }

    private function setAutoPay(bool $autoPay) : Item
    {
        $this->autoPay = $autoPay;

        return $this;
    }

    private function setItemId(string $itemId) : Item
    {
        $this->itemId = $itemId;

        return $this;
    }

    private function setTitle(string $title) : Item
    {
        $this->title = $title;

        return $this;
    }

    private function setGlobalId(string $globalId) : Item
    {
        $this->globalId = $globalId;

        return $this;
    }

    private function setProductId(string $type, int $productId) : Item
    {
        $this->productId = array(
            'type' => $type,
            'productId' => $productId,
        );

        return $this;
    }

    private function setPaymentMethod(array $paymentMethods) : Item
    {
        $this->paymentMethods = $paymentMethods;

        return $this;
    }

    private function setViewItemUrl(string $url) : Item
    {
        $this->viewItemUrl = $url;

        return $this;
    }

    private function setGalleryUrl(string $galleryUrl) : Item
    {
        $this->galleryUrl = $galleryUrl;

        return $this;
    }

    private function setShippingInfo(ShippingInfo $shippingInfo) : Item
    {
        $this->shippingInfo = $shippingInfo;

        return $this;
    }
    
    private function setListingInfo(ListingInfo $listingInfo) : Item
    {
        $this->listingInfo = $listingInfo;

        return $this;
    }

    private function setReturnsAccepted(bool $returnsAccepted) : Item
    {
        $this->returnsAccepted = $returnsAccepted;
        
        return $this;
    }

    private function setIsMultiVariationListing(bool $isMultiVariationListing) : Item
    {
        $this->isMultiVariationListing = $isMultiVariationListing;

        return $this;
    }

    private function setTopRatedListing(bool $topRatedListing) : Item
    {
        $this->topRatedListing = $topRatedListing;

        return $this;
    }

    private function setCondition(Condition $condition) : Item
    {
        $this->condition = $condition;

        return $this;
    }

    private function setAttribute(Attribute $attribute) : Item
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    private function setCharityId(string $charityId) : Item
    {
        $this->charityId = $charityId;

        return $this;
    }

    private function setCompatibility(string $compatibility) : Item
    {
        $this->compatibility = $compatibility;

        return $this;
    }

    private function setDistance(string $unit, float $distance) : Item
    {
        $this->distance = array(
            'unit' => $unit,
            'distance' => $distance,
        );

        return $this;
    }

    private function setEekStatus(string $eekStatus) : Item
    {
        $this->eekStatus[] = $eekStatus;

        return $this;
    }

    private function setGalleryInfoContainer(GalleryInfoContainer $galleryInfoContainer) : Item
    {
        $this->galleryInfoContainer = $galleryInfoContainer;

        return $this;
    }

    private function setGalleryPlusPictureURL(array $galleryPlusPictureUrl)  : Item
    {
        $this->galleryInfoContainer = $galleryPlusPictureUrl;

        return $this;
    }

    private function setPictureURLLarge(string $pictureUrlLarge) : Item
    {
        $this->pictureUrlLarge = $pictureUrlLarge;

        return $this;
    }

    private function setPictureURLSuperSize(string $pictureUrlSuperSize) : Item
    {
        $this->pictureUrlSuperSize = $pictureUrlSuperSize;

        return $this;
    }
    
    private function setSecondaryCategory(Category $category) : Item 
    {
        $this->secondaryCategory = $category;

        return $this;
    }

    private function setSellerInfo(SellerInfo $sellerInfo) : Item
    {
        $this->sellerInfo = $sellerInfo;

        return $this;
    }

    private function setStoreInfo(StoreInfo $storeInfo) : Item
    {
        $this->storeInfo = $storeInfo;

        return $this;
    }

    private function setSubtitle(string $subtitle) : Item
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function setUnitPrice(UnitPrice $unitPrice) : Item
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }
}