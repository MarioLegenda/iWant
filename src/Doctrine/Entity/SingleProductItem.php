<?php

namespace App\Doctrine\Entity;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\MarketplaceType;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use App\Library\Util\Util;

/**
 * @Entity @Table(
 *     name="single_product_item"
 * )
 * @HasLifecycleCallbacks()
 **/
class SingleProductItem implements ArrayNotationInterface
{
    /**
     * @var int $id
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @var string $itemId
     * @Column(type="string")
     */
    private $itemId;
    /**
     * @var string $response
     * @Column(type="text")
     */
    private $response;
    /**
     * @var MarketplaceType $marketplace
     * @Column(type="string")
     */
    private $marketplace;
    /**
     * @var \DateTime $storedAt
     * @Column(type="datetime")
     */
    private $storedAt;
    /**
     * @var int $expiresAt
     * @Column(type="integer")
     */
    private $expiresAt;
    /**
     * @var \DateTime $createdAt
     * @Column(type="datetime")
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     * @Column(type="datetime", nullable=true)
     */
    private $updatedAt;
    /**
     * SingleProductItem constructor.
     * @param string $itemId
     * @param string $response
     * @param MarketplaceType $marketplace
     * @param int $expiresAt
     */
    public function __construct(
        string $itemId,
        string $response,
        MarketplaceType $marketplace,
        int $expiresAt
    ) {
        $this->itemId = $itemId;
        $this->marketplace = (string) $marketplace;
        $this->expiresAt = $expiresAt;
        $this->response = $response;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function getResponse(): string
    {
        return $this->response;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
    /**
     * @return int
     */
    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }
    /**
     * @param int $expiresAt
     */
    public function setExpiresAt(int $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }
    /**
     * @return \DateTime
     */
    public function getStoredAt(): \DateTime
    {
        return $this->storedAt;
    }
    /**
     * @param \DateTime $storedAt
     */
    public function setStoredAt(\DateTime $storedAt): void
    {
        $this->storedAt = $storedAt;
    }
    /**
     * @PrePersist()
     * @PreUpdate()
     */
    public function handleDates(): void
    {
        if ($this->createdAt instanceof \DateTime) {
            $this->setUpdatedAt(Util::toDateTime());
        }

        if (!$this->createdAt instanceof \DateTime) {
            $this->setCreatedAt(Util::toDateTime());
            $this->setStoredAt($this->getCreatedAt());
        }
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'itemId' => $this->getItemId(),
            'createdAt' => Util::formatFromDate($this->getCreatedAt()),
            'updatedAt' => Util::formatFromDate($this->getUpdatedAt()),
        ];
    }
}