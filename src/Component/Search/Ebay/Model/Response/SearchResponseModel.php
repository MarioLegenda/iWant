<?php

namespace App\Component\Search\Ebay\Model\Response;

use App\Ebay\Library\Response\FindingApi\Json\Result\ListingInfo;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;
use App\Web\Library\Grouping\GroupContract\PriceGroupingInterface;

class SearchResponseModel implements
    PriceGroupingInterface,
    ArrayNotationInterface
{
    /**
     * @var string $uniqueName
     */
    private $uniqueName;
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * @var string|null $globalId
     */
    private $globalId;
    /**
     * @var Title $title
     */
    private $title;
    /**
     * @var Image $imageUrl
     */
    private $image;
    /**
     * @var BusinessEntity $businessEntity
     */
    private $businessEntity;
    /**
     * @var Price $price
     */
    private $price;
    /**
     * @var string $viewItemUrl
     */
    private $viewItemUrl;
    /**
     * @var Country|null $country
     */
    private $country;
    /**
     * @var array $listingInfo
     */
    private $listingInfo;
    /**
     * @var bool $isTranslated
     */
    private $isTranslated = false;
    /**
     * @var Title|null
     */
    private $subtitle;
    /**
     * TodayProductRequestModel constructor.
     * @param string $itemId
     * @param string $uniqueName
     * @param string $globalId
     * @param Title $title
     * @param Image $image
     * @param BusinessEntity $businessEntity
     * @param Price $price
     * @param string $viewItemUrl
     * @param Country $country
     * @param array $listingInfo
     * @param Title|null $subtitle
     */
    public function __construct(
        string $uniqueName,
        string $itemId,
        Title $title,
        ?Title $subtitle,
        Image $image,
        BusinessEntity $businessEntity,
        Price $price,
        string $viewItemUrl,
        string $globalId,
        ?Country $country,
        ?array $listingInfo
    ) {
        $this->uniqueName = $uniqueName;
        $this->itemId = $itemId;
        $this->globalId = $globalId;
        $this->title = $title;
        $this->image = $image;
        $this->businessEntity = $businessEntity;
        $this->price = $price;
        $this->viewItemUrl = $viewItemUrl;
        $this->country = $country;
        $this->listingInfo = $listingInfo;
        $this->subtitle = $subtitle;
    }
    /**
     * @return Country|null
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }
    /**
     * @return string
     */
    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }
    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }
    /**
     * @return Title
     */
    public function getTitle(): Title
    {
        return $this->title;
    }
    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = new Title($title);
    }
    /**
     * @return bool
     */
    public function isFixedPrice(): bool
    {
        $infoTypes = ['FixedPrice', ['StoreInventory']];
        $listingType = $this->getListingInfo()['listingType'];

        return in_array($listingType, $infoTypes) === true;
    }
    /**
     * @return bool
     */
    public function isAuction(): bool
    {
        return $this->isFixedPrice() === false;
    }
    /**
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }
    /**
     * @return array|null
     */
    public function getListingInfo(): ?array
    {
        return $this->listingInfo;
    }
    /**
     * @return BusinessEntity
     */
    public function getBusinessEntity(): BusinessEntity
    {
        return $this->businessEntity;
    }
    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
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
    public function getGlobalId(): ?string
    {
        return $this->globalId;
    }
    /**
     * @param bool $isTranslated
     */
    public function translated(bool $isTranslated)
    {
        $this->isTranslated = $isTranslated;
    }
    /**
     * @return bool
     */
    public function isTranslated(): bool
    {
        return $this->isTranslated;
    }
    /**
     * @return float
     */
    public function getPriceForGrouping(): float
    {
        return (float) $this->getPrice()->getPrice();
    }
    /**
     * @return Title|null
     */
    public function getSubtitle(): ?Title
    {
        return $this->subtitle;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'uniqueName' => $this->getUniqueName(),
            'itemId' => $this->getItemId(),
            'globalId' => $this->getGlobalId(),
            'subtitle' => ($this->getSubtitle() instanceof Title) ? $this->getSubtitle()->toArray() : null,
            'title' => $this->getTitle()->toArray(),
            'image' => $this->getImage()->toArray(),
            'businessEntity' => $this->getBusinessEntity()->toArray(),
            'price' => $this->getPrice()->toArray(),
            'viewItemUrl' => $this->getViewItemUrl(),
            'isTranslated' => $this->isTranslated(),
            'country' => $this->getCountry()->toArray(),
            'listingInfo' => $this->getListingInfo(),
            'isFixedPrice' => $this->isFixedPrice(),
            'isAuction' => $this->isAuction(),
        ];
    }
}