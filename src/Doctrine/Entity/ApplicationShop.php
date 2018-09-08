<?php

namespace App\Doctrine\Entity;

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
 *     name="application_shops"
 * )
 * @HasLifecycleCallbacks()
 **/
class ApplicationShop
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
     * @var string $name
     * @Column(type="string")
     */
    private $applicationName;
    /**
     * @var string $name
     * @Column(type="string", nullable=true)
     */
    private $globalId;
    /**
     * @var NativeTaxonomy $nativeTaxonomy
     * @ORM\ManyToOne(targetEntity="NativeTaxonomy")
     * @JoinColumn(name="normalized_category_id", referencedColumnName="id")
     */
    private $nativeTaxonomy;
    /**
     * @var string $name
     * @Column(type="string")
     */
    private $marketplace;
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
     * ApplicationShop constructor.
     * @param string $name
     * @param string $applicationName
     * @param string $globalId
     * @param string $marketplace
     * @param NativeTaxonomy $nativeTaxonomy
     */
    public function __construct(
        string $name,
        string $applicationName,
        string $marketplace,
        NativeTaxonomy $nativeTaxonomy,
        string $globalId = null
    ) {
        $this->name = $name;
        $this->applicationName = $applicationName;
        $this->globalId = $globalId;
        $this->marketplace = (string) MarketplaceType::fromValue($marketplace);
        $this->nativeTaxonomy = $nativeTaxonomy;
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
     * @return string
     */
    public function getApplicationName(): string
    {
        return $this->applicationName;
    }
    /**
     * @param string $applicationName
     */
    public function setApplicationName(string $applicationName): void
    {
        $this->applicationName = $applicationName;
    }
    /**
     * @return string
     */
    public function getGlobalId(): string
    {
        return $this->globalId;
    }
    /**
     * @return NativeTaxonomy
     */
    public function getNativeTaxonomy(): NativeTaxonomy
    {
        return $this->nativeTaxonomy;
    }
    /**
     * @param NativeTaxonomy $nativeTaxonomy
     */
    public function setNativeTaxonomy(NativeTaxonomy $nativeTaxonomy): void
    {
        $this->nativeTaxonomy = $nativeTaxonomy;
    }
    /**
     * @return string
     */
    public function getMarketplace(): string
    {
        return $this->marketplace;
    }
    /**
     * @param string $marketplace
     */
    public function setMarketplace(string $marketplace): void
    {
        $this->marketplace = $marketplace;
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