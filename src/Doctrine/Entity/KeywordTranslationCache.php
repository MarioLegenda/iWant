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
 *     name="keyword_translation_cache"
 * )
 * @HasLifecycleCallbacks()
 **/
class KeywordTranslationCache implements ArrayNotationInterface
{
    /**
     * @var int $id
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @var string $original
     * @Column(type="string", nullable=false)
     */
    private $original;
    /**
     * @var string $translation
     * @Column(type="string", nullable=false)
     */
    private $translation;
    /**
     * @var string $languageDirection
     * @Column(type="string", nullable=false)
     */
    private $languageDirection;
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
     * KeywordTranslationCache constructor.
     * @param string $original
     * @param string $translation
     * @param string $languageDirection
     * @param int $expiresAt
     */
    public function __construct(
        string $original,
        string $translation,
        string $languageDirection,
        int $expiresAt
    ) {
        $this->original = $original;
        $this->translation = $translation;
        $this->languageDirection = $languageDirection;
        $this->expiresAt = $expiresAt;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param string $original
     */
    public function setOriginal(string $original): void
    {
        $this->original = $original;
    }
    /**
     * @return string
     */
    public function getOriginal(): string
    {
        return $this->original;
    }
    /**
     * @param string $translation
     */
    public function setTranslation(string $translation): void
    {
        $this->translation = $translation;
    }
    /**
     * @return string
     */
    public function getTranslation(): string
    {
        return $this->translation;
    }
    /**
     * @return string
     */
    public function getLanguageDirection(): string
    {
        return $this->languageDirection;
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
    public function getExpiresAt(): int
    {
        return $this->expiresAt;
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
     * @return iterable
     */
    public function toArray(): iterable
    {
        // TODO: Implement toArray() method.
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