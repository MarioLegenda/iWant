<?php

namespace App\Component\Search\Ebay\Model\Response;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class PreparedEbayResponse implements ArrayNotationInterface
{
    /**
     * @var string $uniqueName
     */
    private $uniqueName;
    /**
     * @var array $globalIdInformation
     */
    private $globalIdInformation;
    /**
     * @var string $globalId
     */
    private $globalId;
    /**
     * @var int $totalEntries
     */
    private $totalEntries;
    /**
     * @var int $entriesPerPage
     */
    private $entriesPerPage;
    /**
     * PreparedEbayResponse constructor.
     * @param string $uniqueName
     * @param array $globalIdInformation
     * @param string $globalId
     * @param int $totalEntries
     * @param int $entriesPerPage
     */
    public function __construct(
        string $uniqueName,
        array $globalIdInformation,
        string $globalId,
        int $totalEntries,
        int $entriesPerPage
    ) {
        $this->globalIdInformation = $globalIdInformation;
        $this->uniqueName = $uniqueName;
        $this->globalId = $globalId;
        $this->totalEntries = $totalEntries;
        $this->entriesPerPage = $entriesPerPage;
    }
    /**
     * @return string
     */
    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }
    /**
     * @return string
     */
    public function getGlobalId(): string
    {
        return $this->globalId;
    }
    /**
     * @return int
     */
    public function getTotalEntries(): int
    {
        return $this->totalEntries;
    }
    /**
     * @return int
     */
    public function getEntriesPerPage(): int
    {
        return $this->entriesPerPage;
    }
    /**
     * @return array
     */
    public function getGlobalIdInformation(): array
    {
        return $this->globalIdInformation;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'uniqueName' => $this->getUniqueName(),
            'globalId' => $this->getGlobalId(),
            'globalIdInformation' => $this->getGlobalIdInformation(),
            'totalEntries' => $this->getTotalEntries(),
            'entriesPerPage' => $this->getEntriesPerPage(),
        ];
    }
}