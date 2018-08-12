<?php

namespace App\Etsy\Library\Response\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Result implements ArrayNotationInterface
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
     * @return int
     */
    public function getListingId(): int
    {
        return $this->resultItem['listing_id'];
    }
    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->resultItem['state'];
    }
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->resultItem['title'];
    }
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->resultItem['description'];
    }
    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->resultItem['price'];
    }
    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->resultItem['currency_code'];
    }
    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->resultItem['quantity'];
    }
    /**
     * @return array
     */
    public function getSku(): array
    {
        return $this->resultItem['sku'];
    }
    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->resultItem['tags'];
    }
    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->resultItem['url'];
    }
    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->resultItem['views'];
    }
    /**
     * @return int|null
     */
    public function getShippingTemplateId(): ?int
    {
        return $this->resultItem['shipping_template_id'];
    }
    /**
     * @return int
     */
    public function getTaxonomyId(): int
    {
        return $this->resultItem['taxonomy_id'];
    }
    /**
     * @return array
     */
    public function getTaxonomyPath(): array
    {
        return $this->resultItem['taxonomy_path'];
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'listing_id' => $this->getListingId(),
            'state' => $this->getState(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'currency' => $this->getCurrency(),
            'price' => $this->getPrice(),
            'quantity' => $this->getQuantity(),
            'sku' => $this->getSku(),
            'tags' => $this->getTags(),
            'url' => $this->getUrl(),
            'views' => $this->getViews(),
            'shipping_template_id' => $this->getShippingTemplateId(),
            'taxonomy_id' => $this->getTaxonomyId(),
            'taxonomy_path' => $this->getTaxonomyPath(),
        ];
    }
}