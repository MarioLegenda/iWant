<?php

namespace App\Doctrine\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity @Table(
 *     name="countries"
 * )
 **/
class Country
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
     * @var string|null $currencyCode
     * @Column(type="string", nullable=true)
     */
    private $currencyCode;
    /**
     * @var string $flag
     * @Column(type="string")
     */
    private $flag;

    public function __construct(
        string $name,
        string $alpha3Code,
        string $flag,
        string $currencyCode = null
    ) {
        $this->name = $name;
        $this->alpha3Code = $alpha3Code;
        $this->flag = $flag;
        $this->currencyCode = $currencyCode;
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
     * @return string
     */
    public function getCurrencyCode(): string
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
}