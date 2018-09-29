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

/**
 * @Entity @Table(
 *     name="countries"
 * )
 * @HasLifecycleCallbacks()
 **/
class Country implements ArrayNotationInterface
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
     * @var string $alpha3Code
     * @Column(type="string")
     */
    private $alpha3Code;
    /**
     * @var string $alpha2Code
     * @Column(type="string")
     */
    private $alpha2Code;
    /**
     * @var string|null $currencyCode
     * @Column(type="string", nullable=true)
     */
    private $currencyCode;
    /**
     * @var string $flag
     * @Column(type="string")
     */
    private $flag;
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
        string $name,
        string $alpha3Code,
        string $alpha2Code,
        string $flag,
        string $currencyCode = null
    ) {
        $this->name = $name;
        $this->alpha3Code = $alpha3Code;
        $this->flag = $flag;
        $this->currencyCode = $currencyCode;
        $this->alpha2Code = $alpha2Code;
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
     * @return string
     */
    public function getAlpha3Code(): string
    {
        return $this->alpha3Code;
    }
    /**
     * @param string $alpha3Code
     */
    public function setAlpha3Code(string $alpha3Code): void
    {
        $this->alpha3Code = $alpha3Code;
    }
    /**
     * @return string|null
     */
    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }
    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode(string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }
    /**
     * @return string
     */
    public function getFlag(): string
    {
        return $this->flag;
    }
    /**
     * @param string $flag
     */
    public function setFlag(string $flag): void
    {
        $this->flag = $flag;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    /**
     * @return string
     */
    public function getAlpha2Code(): string
    {
        return $this->alpha2Code;
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
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'alpha2Code' => $this->getAlpha2Code(),
            'alpha3Code' => $this->getAlpha3Code(),
            'flag' => $this->getFlag(),
            'currencyCode' => $this->getCurrencyCode(),
        ];
    }
}