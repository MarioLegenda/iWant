<?php

namespace App\Doctrine\Entity;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
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
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @Entity @Table(
 *     name="application_shops"
 * )
 * @HasLifecycleCallbacks()
 **/
class ApplicationShop implements ArrayNotationInterface
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
     * @var bool $unCategorised
     * @Column(type="boolean")
     */
    private $unCategorised = false;
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
     * @param bool $unCategorised
     */
    public function __construct(
        string $name,
        string $applicationName,
        string $marketplace,
        NativeTaxonomy $nativeTaxonomy = null,
        string $globalId = null,
        bool $unCategorised = false
    ) {
        if ($unCategorised === true and $nativeTaxonomy instanceof NativeTaxonomy) {
            $message = sprintf(
                'Application shop with name \'%s\' cannot have a native taxonomy \'%s\' and be not categorised at the same time',
                $name,
                $nativeTaxonomy->getInternalName()
            );

            throw new \RuntimeException($message);
        }

        $this->name = $name;
        $this->applicationName = $applicationName;
        $this->globalId = $globalId;
        $this->marketplace = (string) MarketplaceType::fromValue($marketplace);
        $this->nativeTaxonomy = $nativeTaxonomy;
        $this->unCategorised = $unCategorised;
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
     * @return bool
     */
    public function isUnCategorised(): bool
    {
        return $this->unCategorised;
    }

    /**
     * @param bool $unCategorised
     */
    public function setUnCategorised(bool $unCategorised): void
    {
        $this->unCategorised = $unCategorised;
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
     * @param string|null $globalId
     */
    public function setGlobalId(string $globalId = null)
    {
        $this->globalId = $globalId;
    }
    /**
     * @return string
     */
    public function getGlobalId(): string
    {
        return $this->globalId;
    }
    /**
     * @return NativeTaxonomy|null
     */
    public function getNativeTaxonomy(): ?NativeTaxonomy
    {
        return $this->nativeTaxonomy;
    }
    /**
     * @param NativeTaxonomy|null $nativeTaxonomy
     */
    public function setNativeTaxonomy(NativeTaxonomy $nativeTaxonomy = null): void
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
    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'applicationName' => $this->getApplicationName(),
            'globalId' => $this->getGlobalId(),
            'marketplace' => MarketplaceType::fromValue($this->getMarketplace()),
            'createdAt' => Util::formatFromDate($this->getCreatedAt()),
            'updatedAt' => Util::formatFromDate($this->getUpdatedAt()),
        ];
    }
}