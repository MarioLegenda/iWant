<?php

namespace App\Doctrine\Entity;

use App\Library\Util\Util;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * @ORM\MappedSuperclass()
 */
class BaseUniqueIdentifierCache
{
    /**
     * @var string $identifier
     * @Column(type="string")
     */
    protected $identifier;
    /**
     * @var string $response
     * @Column(type="text")
     */
    protected $response;
    /**
     * @var \DateTime $storedAt
     * @Column(type="datetime")
     */
    protected $storedAt;
    /**
     * @var int $expiresAt
     * @Column(type="integer")
     */
    protected $expiresAt;
    /**
     * @var \DateTime $createdAt
     * @Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @var \DateTime $updatedAt
     * @Column(type="datetime", nullable=true)
     */
    protected $updatedAt;
    /**
     * BaseUniqueIdentifierCache constructor.
     * @param string $identifier
     * @param string $response
     */
    public function __construct(
        string $identifier,
        string $response
    ) {
        $this->identifier = $identifier;
        $this->response = $response;
    }
    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
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
}