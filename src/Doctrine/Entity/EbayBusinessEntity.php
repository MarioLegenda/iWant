<?php

namespace App\Doctrine\Entity;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity @Table(
 *     name="ebay_business_entities",
 *     uniqueConstraints={ @UniqueConstraint(columns={"display_name"}) }
 * )
 * @HasLifecycleCallbacks()
 **/
class EbayBusinessEntity
{
    /**
     * @var int $id
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @var string $name
     * @Column(type="string", nullable=true)
     */
    private $name;
    /**
     * @var string $displayName
     * @Column(type="string", nullable=false)
     */
    private $displayName;
    /**
     * @var string $type
     * @Column(type="string", nullable=true)
     */
    private $type;
    /**
     * @var string $globalId
     * @Column(type="string", nullable=false)
     */
    private $globalId;
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
     * BusinessEntity constructor.
     * @param string $name
     * @param string $globalId
     * @param string $displayName
     * @param string $type
     */
    public function __construct(
        string $displayName,
        string $globalId,
        string $name = null,
        string $type = null
    ) {
        $this->name = $name;
        $this->displayName = $displayName;
        $this->type = $type;
        $this->globalId = $globalId;
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
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    /**
     * @return string
     */
    public function getGlobalId(): string
    {
        return $this->globalId;
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
        }
    }
}