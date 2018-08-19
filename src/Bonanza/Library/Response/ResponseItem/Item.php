<?php

namespace App\Bonanza\Library\Response\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Item implements ArrayNotationInterface
{
    /**
     * @var iterable $item
     */
    private $item;
    /**
     * @var array $responseObjects
     */
    private $responseObjects = [
        'listingInfo' => null,
        'sellerInfo' => null,
        'shippingInfo' => null,
    ];
    /**
     * Item constructor.
     * @param iterable $item
     */
    public function __construct(iterable $item)
    {
        $this->item = $item;
    }
    /**
     * @return int
     */
    public function getItemId(): int
    {
        return $this->item['itemId'];
    }
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->item['title'];
    }
    /**
     * @return string
     */
    public function getViewItemUrl(): string
    {
        return $this->item['viewItemURL'];
    }
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->item['descriptionBrief'];
    }
    /**
     * @return string
     */
    public function getGalleryUrl(): string
    {
        return $this->item['galleryURL'];
    }
    /**
     * @return ListingInfo
     */
    public function getListingInfo(): ListingInfo
    {
        if (!$this->responseObjects instanceof ListingInfo) {
            $this->responseObjects['listingInfo'] = new ListingInfo($this->item['listingInfo']);
        }

        return $this->responseObjects['listingInfo'];
    }
    /**
     * @return SellerInfo
     */
    public function getSellerInfo(): SellerInfo
    {
        if (!$this->responseObjects instanceof SellerInfo) {
            $this->responseObjects['sellerInfo'] = new SellerInfo($this->item['sellerInfo']);
        }

        return $this->responseObjects['sellerInfo'];
    }
    /**
     * @return ShippingInfo
     */
    public function getShippingInfo(): ShippingInfo
    {
        if (!$this->responseObjects instanceof ShippingInfo) {
            $this->responseObjects['shippingInfo'] = new ShippingInfo($this->item['shippingInfo']);
        }

        return $this->responseObjects['shippingInfo'];
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'itemId' => $this->getItemId(),
            'title' => $this->getTitle(),
            'viewItemUrl' => $this->getViewItemUrl(),
            'description' => $this->getDescription(),
            'galleryUrl' => $this->getGalleryUrl(),
            'listingInfo' => $this->getListingInfo()->toArray(),
            'sellerInfo' => $this->getSellerInfo()->toArray(),
        ];
    }
}