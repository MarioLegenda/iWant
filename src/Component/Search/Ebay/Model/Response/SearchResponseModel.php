<?php

namespace App\Component\Search\Ebay\Model\Response;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;

class SearchResult implements ArrayNotationInterface
{
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
     * @var string $shopName
     */
    private $shopName;
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
     * TodayProductRequestModel constructor.
     * @param string $itemId
     * @param string $globalId
     * @param Title $title
     * @param Image $image
     * @param string $shopName
     * @param Price $price
     * @param string $viewItemUrl
     * @param string $staticUrl
     * @param string $taxonomyName
     * @param array $shippingLocations
     * @param MarketplaceType|TypeInterface $marketplace
     */
    public function __construct(
        string $itemId,
        Title $title,
        Image $image,
        string $shopName,
        Price $price,
        string $viewItemUrl,
        MarketplaceType $marketplace,
        string $staticUrl,
        string $taxonomyName,
        array $shippingLocations,
        string $globalId = null
    ) {
        $this->itemId = $itemId;
        $this->staticUrl = $staticUrl;
        $this->globalId = $globalId;
        $this->title = $title;
        $this->image = $image;
        $this->shopName = $shopName;
        $this->price = $price;
        $this->viewItemUrl = $viewItemUrl;
        $this->taxonomyName = $taxonomyName;
        $this->marketplace = $marketplace;
        $this->shippingLocations = $shippingLocations;
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
     * @return string
     */
    public function getShopName(): string
    {
        return $this->shopName;
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
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'itemId' => $this->getItemId(),
            'globalId' => $this->getGlobalId(),
            'title' => $this->getTitle()->toArray(),
            'image' => $this->getImage()->toArray(),
            'shopName' => $this->getShopName(),
            'price' => $this->getPrice()->toArray(),
            'viewItemUrl' => $this->getViewItemUrl(),
            'marketplace' => (string) $this->getMarketplace(),
            'taxonomyName' => $this->getTaxonomyName(),
            'staticUrl' => $this->getStaticUrl(),
            'shippingLocations' => $this->getShippingLocations(),
        ];
    }
}