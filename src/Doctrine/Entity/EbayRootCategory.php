<?php

namespace App\Doctrine\Entity;

use App\Library\Util\Util;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Index;

/**
 * @Entity @Table(
 *     name="ebay_root_categories",
 *     indexes={ @Index(name="category_id_idx", columns={"category_id"}) }
 * )
 * @HasLifecycleCallbacks()
 **/
class EbayRootCategory
{
    /**
     * @var int $id
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @var string $globalId
     * @Column(type="string")
     */
    private $globalId;
    /**
     * @var string $categoryId
     * @Column(type="string")
     */
    private $categoryId;
    /**
     * @var string $categoryIdPath
     * @Column(type="string")
     */
    private $categoryIdPath;
    /**
     * @var int $categoryLevel
     * @Column(type="smallint")
     */
    private $categoryLevel;
    /**
     * @var string $categoryName
     * @Column(type="string")
     */
    private $categoryName;
    /**
     * @var string $categoryNamePath
     * @Column(type="string")
     */
    private $categoryNamePath;
    /**
     * @var string $categoryIdPath
     * @Column(type="string")
     */
    private $categoryParentId;
    /**
     * @var bool $leafCategory
     * @Column(type="boolean")
     */
    private $leafCategory;
    /**
     * @ManyToOne(targetEntity="NativeTaxonomy")
     * @JoinColumn(name="normalized_category_id", referencedColumnName="id")
     */
    private $normalizedCategory;
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

    public function __construct(
        string $globalId,
        string $categoryId,
        string $categoryIdPath,
        int $categoryLevel,
        string $categoryName,
        string $categoryNamePath,
        string $categoryParentId,
        bool $leafCategory,
        NativeTaxonomy $normalizedCategory
    ) {
        $this->globalId = $globalId;
        $this->categoryId = $categoryId;
        $this->categoryIdPath = $categoryIdPath;
        $this->categoryLevel = $categoryLevel;
        $this->categoryName = $categoryName;
        $this->categoryNamePath = $categoryNamePath;
        $this->categoryParentId = $categoryParentId;
        $this->leafCategory = $leafCategory;
        $this->normalizedCategory = $normalizedCategory;
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
    public function getGlobalId(): string
    {
        return $this->globalId;
    }
    /**
     * @param string $globalId
     */
    public function setGlobalId(string $globalId): void
    {
        $this->globalId = $globalId;
    }
    /**
     * @return string
     */
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }
    /**
     * @param string $categoryId
     */
    public function setCategoryId(string $categoryId): void
    {
        $this->categoryId = $categoryId;
    }
    /**
     * @return string
     */
    public function getCategoryIdPath(): string
    {
        return $this->categoryIdPath;
    }
    /**
     * @param string $categoryIdPath
     */
    public function setCategoryIdPath(string $categoryIdPath): void
    {
        $this->categoryIdPath = $categoryIdPath;
    }
    /**
     * @return int
     */
    public function getCategoryLevel(): int
    {
        return $this->categoryLevel;
    }
    /**
     * @param int $categoryLevel
     */
    public function setCategoryLevel(int $categoryLevel): void
    {
        $this->categoryLevel = $categoryLevel;
    }
    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }
    /**
     * @param string $categoryName
     */
    public function setCategoryName(string $categoryName): void
    {
        $this->categoryName = $categoryName;
    }
    /**
     * @return string
     */
    public function getCategoryNamePath(): string
    {
        return $this->categoryNamePath;
    }
    /**
     * @param string $categoryNamePath
     */
    public function setCategoryNamePath(string $categoryNamePath): void
    {
        $this->categoryNamePath = $categoryNamePath;
    }
    /**
     * @return string
     */
    public function getCategoryParentId(): string
    {
        return $this->categoryParentId;
    }
    /**
     * @param string $categoryParentId
     */
    public function setCategoryParentId(string $categoryParentId): void
    {
        $this->categoryParentId = $categoryParentId;
    }
    /**
     * @return bool
     */
    public function isLeafCategory(): bool
    {
        return $this->leafCategory;
    }
    /**
     * @param bool $leafCategory
     */
    public function setLeafCategory(bool $leafCategory): void
    {
        $this->leafCategory = $leafCategory;
    }
    /**
     * @return NativeTaxonomy
     */
    public function getNormalizedCategory(): NativeTaxonomy
    {
        return $this->normalizedCategory;
    }
    /**
     * @param NativeTaxonomy $normalizedCategory
     */
    public function setNormalizedCategory(NativeTaxonomy $normalizedCategory): void
    {
        $this->normalizedCategory = $normalizedCategory;
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