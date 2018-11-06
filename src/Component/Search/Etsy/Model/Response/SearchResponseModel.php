<?php

namespace App\Component\Search\Etsy\Model\Response;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;

class SearchResponseModel implements ArrayNotationInterface
{
    /**
     * @var string $itemId
     */
    private $itemId;
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
     * @var MarketplaceType|TypeInterface $marketplace
     */
    private $marketplace;
    /**
     * @var string $staticUrl
     */
    private $staticUrl;
    /**
     * @var array $shippingLocations
     */
    private $shippingLocations;
    /**
     * SearchResponseModel constructor.
     * @param string $itemId
     * @param Title $title
     * @param Image $image
     * @param string $shopName
     * @param Price $price
     * @param string $viewItemUrl
     * @param string $staticUrl
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
        array $shippingLocations
    ) {
        $this->itemId = $itemId;
        $this->staticUrl = $staticUrl;
        $this->title = $title;
        $this->image = $image;
        $this->shopName = $shopName;
        $this->price = $price;
        $this->viewItemUrl = $viewItemUrl;
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
     * @return string
     */
    public function getStaticUrl(): string
    {
        return $this->staticUrl;
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
            'title' => $this->getTitle()->toArray(),
            'image' => $this->getImage()->toArray(),
            'shopName' => $this->getShopName(),
            'price' => $this->getPrice()->toArray(),
            'viewItemUrl' => $this->getViewItemUrl(),
            'marketplace' => (string) $this->getMarketplace(),
            'staticUrl' => $this->getStaticUrl(),
            'shippingLocations' => $this->getShippingLocations(),
        ];
    }
}