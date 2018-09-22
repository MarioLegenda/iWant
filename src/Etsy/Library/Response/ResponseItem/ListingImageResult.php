<?php

namespace App\Etsy\Library\Response\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ListingImageResult implements ArrayNotationInterface
{
    /**
     * @var array $resultItem
     */
    private $resultItem;
    /**
     * Result constructor.
     * @param array $resultItem
     */
    public function __construct(array $resultItem)
    {
        $this->resultItem = $resultItem;
    }
    /**
     * @return string
     */
    public function getListingImageId(): string
    {
        return $this->resultItem['listing_image_id'];
    }
    /**
     * @return string
     */
    public function getUrl75(): string
    {
        return $this->resultItem['url_75x75'];
    }
    /**
     * @return string
     */
    public function getUrl570(): string
    {
        return $this->resultItem['url_570xN'];
    }
    /**
     * @return string
     */
    public function getUrl170(): string
    {
        return $this->resultItem['url_170x135'];
    }
    /**
     * @return string
     */
    public function getUrlFull(): string
    {
        return $this->resultItem['url_fullxfull'];
    }
    /**
     * @return string
     */
    public function getFullWidth(): string
    {
        return $this->resultItem['full_width'];
    }
    /**
     * @return string
     */
    public function getFullHeight(): string
    {
        return $this->resultItem['full_height'];
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'listingImageId' => $this->getListingImageId(),
            'url75' => $this->getUrl75(),
            'url170' => $this->getUrl170(),
            'url570' => $this->getUrl570(),
            'urlFull' => $this->getUrlFull(),
            'fullWidth' => $this->getFullWidth(),
            'fullHeight' => $this->getFullHeight(),
        ];
    }
}