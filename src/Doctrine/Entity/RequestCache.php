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
 *     name="request_cache"
 * )
 * @HasLifecycleCallbacks()
 **/
class RequestCache
{
    /**
     * @var int $id
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @var string $request
     * @Column(type="text")
     */
    private $request;
    /**
     * @var string $response
     * @Column(type="text")
     */
    private $response;
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
     * @var int $expiresAt
     * @Column(type="integer", nullable=true)
     */
    private $expiresAt;
    /**
     * @var int $storedAt
     * @Column(type="integer")
     */
    private $storedAt;
    /**
     * RequestCache constructor.
     * @param string $request
     * @param string $response
     * @param int $expiresAt
     */
    public function __construct(
        string $request,
        string $response,
        int $expiresAt
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->expiresAt = $expiresAt;
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
    public function getRequest(): string
    {
        return $this->request;
    }
    /**
     * @param string $request
     */
    public function setRequest(string $request): void
    {
        $this->request = $request;
    }
    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }
    /**
     * @param string $response
     */
    public function setResponse(string $response): void
    {
        $this->response = $response;
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
     * @return int
     */
    public function getStoredAt(): int
    {
        return $this->storedAt;
    }
    /**
     * @param int $storedAt
     */
    public function setStoredAt(int $storedAt): void
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
            $this->setStoredAt($this->getCreatedAt()->getTimestamp());
        }
    }
}