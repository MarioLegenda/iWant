<?php

namespace App\Doctrine\Entity;

use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;
use App\Library\Util\Util;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @Entity @Table(
 *     name="todays_keywords"
 * )
 * @HasLifecycleCallbacks()
 **/
class TodayKeyword
{
    /**
     * @var int $id
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @var string $name
     * @Column(type="string")
     */
    private $name;
    /**
     * @var string $marketplace
     * @Column(type="string")
     */
    private $marketplace;
    /**
     * @var NativeTaxonomy $normalizedCategory
     * @ORM\ManyToOne(targetEntity="NativeTaxonomy")
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
    /**
     * TodayKeyword constructor.
     * @param string $keyword
     * @param MarketplaceType $marketplace
     * @param NativeTaxonomy $category
     */
    public function __construct(
        string $keyword,
        MarketplaceType $marketplace,
        NativeTaxonomy $category
    ) {
        $this->name = $keyword;
        $this->marketplace = $marketplace;
        $this->normalizedCategory = $category;
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
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    /**
     * @return MarketplaceType|TypeInterface
     */
    public function getMarketplace(): MarketplaceType
    {
        return MarketplaceType::fromValue($this->marketplace);
    }
    /**
     * @param string $marketplace
     */
    public function setMarketplace(string $marketplace): void
    {
        $this->marketplace = $marketplace;
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