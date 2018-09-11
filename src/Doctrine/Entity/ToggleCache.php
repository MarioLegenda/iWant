<?php

namespace App\Doctrine\Entity;

use App\Library\Util\Util;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;

/**
 * @Entity @Table(
 *     name="toggle_cache"
 * )
 * @HasLifecycleCallbacks()
 **/
class ToggleCache
{
    /**
     * @var int $id
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @var bool $allRequestCache
     * @Column(type="boolean")
     */
    private $allRequestCache = true;
    /**
     * @var bool $todaysKeywordsCache
     * @Column(type="boolean")
     */
    private $todaysKeywordsCache = true;
    /**
     * @var \DateTime $createdAt
     * @Column(type="datetime")
     */
    private $createdAt;
    /**
     * @var \DateTime $createdAt
     * @Column(type="datetime", nullable=true)
     */
    private $updatedAt;
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return bool
     */
    public function getAllRequestCache(): bool
    {
        return $this->allRequestCache;
    }
    /**
     * @param bool $allRequestCache
     */
    public function setAllRequestCache(bool $allRequestCache): void
    {
        $this->allRequestCache = $allRequestCache;
    }
    /**
     * @return bool
     */
    public function getTodaysKeywordsCache(): bool
    {
        return $this->todaysKeywordsCache;
    }
    /**
     * @param bool $todaysKeywordsCache
     */
    public function setTodaysKeywordsCache(bool $todaysKeywordsCache): void
    {
        $this->todaysKeywordsCache = $todaysKeywordsCache;
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
     * @PrePersist()
     */
    public function handleDates(): void
    {
        if ($this->updatedAt instanceof \DateTime) {
            $this->setUpdatedAt(Util::toDateTime());
        }

        if (!$this->createdAt instanceof \DateTime) {
            $this->setCreatedAt(Util::toDateTime());
        }
    }
}