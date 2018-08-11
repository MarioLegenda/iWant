<?php

namespace App\Bonanza\Library\Response\ResponseItem;

class Item
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
}