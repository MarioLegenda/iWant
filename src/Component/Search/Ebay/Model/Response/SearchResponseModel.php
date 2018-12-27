<?php

namespace App\Component\Search\Ebay\Model\Response;

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
     * @var MarketplaceType $marketplace
     */
    private $marketplace;
    /**
     * @var string $staticUrl
     */
    private $staticUrl;
    /**
     * @var string $taxonomyName
     */
    private $taxonomyName;
    /**
     * @var array $shippingLocations
     */
    private $shippingLocations;
    /**
     * @var Country|null $country
     */
    private $country;
    /**
     * @var bool $isTranslated
     */
    private $isTranslated = false;
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
     * @param string $staticUrl
     * @param string $taxonomyName
     * @param array $shippingLocations
     * @param MarketplaceType|TypeInterface $marketplace
     * @param Country $country
     */
    public function __construct(
        string $uniqueName,
        string $itemId,
        Title $title,
        Image $image,
        BusinessEntity $businessEntity,
        Price $price,
        string $viewItemUrl,
        MarketplaceType $marketplace,
        string $staticUrl,
        string $taxonomyName,
        array $shippingLocations,
        string $globalId,
        ?Country $country
    ) {
        $this->uniqueName = $uniqueName;
        $this->itemId = $itemId;
        $this->staticUrl = $staticUrl;
        $this->globalId = $globalId;
        $this->title = $title;
        $this->image = $image;
        $this->businessEntity = $businessEntity;
        $this->price = $price;
        $this->viewItemUrl = $viewItemUrl;
        $this->taxonomyName = $taxonomyName;
        $this->marketplace = $marketplace;
        $this->shippingLocations = $shippingLocations;
        $this->country = $country;
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
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
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
     * @return MarketplaceType
     */
    public function getMarketplace(): MarketplaceType
    {
        return $this->marketplace;
    }
    /**
     * @return string|null
     */
    public function getGlobalId(): ?string
    {
        return $this->globalId;
    }
    /**
     * @return string
     */
    public function getStaticUrl(): string
    {
        return $this->staticUrl;
    }
    /**
     * @return string
     */
    public function getTaxonomyName(): string
    {
        return $this->taxonomyName;
    }
    /**
     * @return array
     */
    public function getShippingLocations(): array
    {
        return $this->shippingLocations;
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
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'uniqueName' => $this->getUniqueName(),
            'itemId' => $this->getItemId(),
            'globalId' => $this->getGlobalId(),
            'title' => $this->getTitle()->toArray(),
            'image' => $this->getImage()->toArray(),
            'businessEntity' => $this->getBusinessEntity()->toArray(),
            'price' => $this->getPrice()->toArray(),
            'viewItemUrl' => $this->getViewItemUrl(),
            'marketplace' => (string) $this->getMarketplace(),
            'taxonomyName' => $this->getTaxonomyName(),
            'staticUrl' => $this->getStaticUrl(),
            'shippingLocations' => $this->getShippingLocations(),
            'isTranslated' => $this->isTranslated(),
            'country' => $this->getCountry()->toArray(),
        ];
    }
}