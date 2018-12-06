<?php

namespace App\Doctrine\Entity;

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
 *     name="search_cache"
 * )
 * @HasLifecycleCallbacks()
 **/
class SearchCache
{
    /**
     * @var int $id
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @var string $uniqueName
     * @Column(type="string")
     */
    private $uniqueName;
    /**
     * @var string $productsResponse
     * @Column(type="text")
     */
    private $productsResponse;
    /**
     * @var int $productsCount
     * @Column(type="integer")
     */
    private $productsCount;
    /**
     * @var \DateTime $createdAt
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
     * SearchCache constructor.
     * @param string $uniqueName
     * @param string $productsResponse
     * @param int $productsCount
     * @param int $expiresAt
     */
    public function __construct(
        string $uniqueName,
        string $productsResponse,
        int $productsCount,
        int $expiresAt
    ) {
        $this->uniqueName = $uniqueName;
        $this->productsResponse = $productsResponse;
        $this->expiresAt = $expiresAt;
        $this->productsCount = $productsCount;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }
    /**
     * @param string $uniqueName
     */
    public function setUniqueName(string $uniqueName)
    {
        $this->uniqueName = $uniqueName;
    }
    /**
     * @return string
     */
    public function getProductsResponse(): string
    {
        return $this->productsResponse;
    }
    /**
     * @param string $productsResponse
     */
    public function setProductsResponse(string $productsResponse): void
    {
        $this->productsResponse = $productsResponse;
    }
    /**
     * @return int
     */
    public function getProductsCount(): int
    {
        return $this->productsCount;
    }
    /**
     * @param int $productsCount
     */
    public function setProductsCount(int $productsCount): void
    {
        $this->productsCount = $productsCount;
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
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
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
}