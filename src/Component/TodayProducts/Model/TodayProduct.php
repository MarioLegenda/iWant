<?php

namespace App\Component\TodayProducts\Model;

use App\Component\Selector\Ebay\Type\Nan;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;

class TodayProduct implements ArrayNotationInterface
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
     * @var Image $imageUrl
     */
    private $image;
    /**
     * @var string $shopName
     */
    private $shopName;
    /**
     * @var string $price
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
     * TodayProductRequestModel constructor.
     * @param string $itemId
     * @param string $title
     * @param Image $image
     * @param string $shopName
     * @param string $price
     * @param string $viewItemUrl
     * @param MarketplaceType|TypeInterface $marketplace
     */
    public function __construct(
        string $itemId,
        string $title,
        Image $image,
        string $shopName,
        string $price,
        string $viewItemUrl,
        MarketplaceType $marketplace
    ) {
        $this->itemId = $itemId;
        $this->title = $title;
        $this->image = $image;
        $this->shopName = $shopName;
        $this->price = $price;
        $this->viewItemUrl = $viewItemUrl;
        $this->marketplace = $marketplace;
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
     * @return string
     */
    public function getPrice(): string
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
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'itemId' => $this->getItemId(),
            'title' => $this->getTitle(),
            'image' => $this->getImage()->toArray(),
            'shopName' => $this->getShopName(),
            'price' => $this->getPrice(),
            'viewItemUrl' => $this->getViewItemUrl(),
            'marketplace' => (string) $this->getMarketplace(),
        ];
    }
}