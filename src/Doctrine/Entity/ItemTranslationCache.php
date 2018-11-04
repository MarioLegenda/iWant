<?php

namespace App\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Index;
use App\Library\Util\Util;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

/**
 * @Entity @Table(
 *     name="item_translation_cache",
 *     uniqueConstraints={ @UniqueConstraint(columns={"unique_name"}), @UniqueConstraint(columns={"item_id"}) },
 *     indexes={ @Index(name="unique_name_ids", columns={"unique_name"}), @Index(name="item_id_idx", columns={"item_id"}) }
 * )
 * @HasLifecycleCallbacks()
 **/
class ItemTranslationCache implements ArrayNotationInterface
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
     * @var string $itemId
     * @Column(type="string")
     */
    private $itemId;
    /**
     * @var string $itemId
     * @Column(type="text")
     */
    private $translations;
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
     * ItemTranslationCache constructor.
     * @param string $uniqueName
     * @param string $itemId
     * @param string $translations
     */
    public function __construct(
        string $uniqueName,
        string $itemId,
        string $translations
    ) {
        $this->uniqueName = $uniqueName;
        $this->itemId = $itemId;
        $this->translations = $translations;
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
    public function getTranslations(): string
    {
        return $this->translations;
    }
    /**
     * @param string $translations
     */
    public function setTranslations(string $translations)
    {
        $this->translations = $translations;
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

    public function toArray(): iterable
    {
    }
}