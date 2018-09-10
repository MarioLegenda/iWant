<?php

namespace App\Component\TodayProducts\Model;

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
     * @var string $imageUrl
     */
    private $imageUrl;
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
     * @param string $imageUrl
     * @param string $shopName
     * @param string $price
     * @param string $viewItemUrl
     * @param MarketplaceType|TypeInterface $marketplace
     */
    public function __construct(
        string $itemId,
        string $title,
        string $imageUrl,
        string $shopName,
        string $price,
        string $viewItemUrl,
        MarketplaceType $marketplace
    ) {
        $this->itemId = $itemId;
        $this->title = $title;
        $this->imageUrl = $imageUrl;
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
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
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
            'imageUrl' => $this->getImageUrl(),
            'shopName' => $this->getShopName(),
            'price' => $this->getPrice(),
            'viewItemUrl' => $this->getViewItemUrl(),
            'marketplace' => (string) $this->getMarketplace(),
        ];
    }
}