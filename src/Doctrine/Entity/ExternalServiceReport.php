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
 *     name="external_service_report",
 *     uniqueConstraints={ @UniqueConstraint(columns={"external_service_type"}) }
 * )
 * @HasLifecycleCallbacks()
 **/
class ExternalServiceReport implements ArrayNotationInterface
{
    /**
     * @var int $id
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @var string $externalServiceType
     * @Column(type="string")
     */
    private $externalServiceType;
    /**
     * @var string $report
     * @Column(type="text")
     */
    private $report;
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
        string $externalServiceType,
        array $report
    ) {
        $this->externalServiceType = $externalServiceType;
        $this->report = $report;
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
    public function getExternalServiceType(): string
    {
        return $this->externalServiceType;
    }
    /**
     * @return string
     */
    public function getReport(): string
    {
        if (is_array($this->report)) {
            return jsonEncodeWithFix($this->report);
        }

        return $this->report;
    }
    /**
     * @param array|string $report
     */
    public function setReport($report)
    {
        if (is_array($report)) {
            $this->report = jsonEncodeWithFix($report);
        }

        if (is_string($report)) {
            $this->report = $report;
        }
    }
    /**
     * @return array
     */
    public function getReportAsArray(): array
    {
        if (is_array($this->report)) {
            return $this->report;
        }

        if (is_string($this->report)) {
            return json_decode($this->report, true);
        }
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
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'externalServiceType' => $this->getExternalServiceType(),
            'report' => $this->getReport(),
            'createdAt' => Util::formatFromDate($this->getCreatedAt()),
            'updatedAt' => Util::formatFromDate($this->getUpdatedAt()),
        ];
    }
}