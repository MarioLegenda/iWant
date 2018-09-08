<?php

namespace App\Component\TodayProducts\Model;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class TodayProduct implements ArrayNotationInterface
{
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
     * TodayProduct constructor.
     * @param string $title
     * @param string $imageUrl
     * @param string $shopName
     * @param string $price
     * @param string $viewItemUrl
     */
    public function __construct(
        string $title,
        string $imageUrl,
        string $shopName,
        string $price,
        string $viewItemUrl
    ) {
        $this->title = $title;
        $this->imageUrl = $imageUrl;
        $this->shopName = $shopName;
        $this->price = $price;
        $this->viewItemUrl = $viewItemUrl;
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
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'title' => $this->getTitle(),
            'imageUrl' => $this->getImageUrl(),
            'shopName' => $this->getShopName(),
            'price' => $this->getPrice(),
            'viewItemUrl' => $this->getViewItemUrl(),
        ];
    }
}