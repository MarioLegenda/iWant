<?php

namespace App\Ebay\Library\Response\FindingApi\Json;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class PaginationOutput implements ArrayNotationInterface
{
    /**
     * @var int $entriesPerPage
     */
    private $entriesPerPage;
    /**
     * @var int $totalPages
     */
    private $totalPages;
    /**
     * @var int $totalEntries
     */
    private $totalEntries;
    /**
     * @var int $pageNumber
     */
    private $pageNumber;
    /**
     * PaginationOutput constructor.
     * @param int $pageNumber
     * @param int $entriesPerPage
     * @param int $totalPages
     * @param int $totalEntries
     */
    public function __construct(
        int $pageNumber,
        int $entriesPerPage,
        int $totalPages,
        int $totalEntries
    ) {
        $this->pageNumber = $pageNumber;
        $this->entriesPerPage = $entriesPerPage;
        $this->totalPages = $totalPages;
        $this->totalEntries = $totalEntries;
    }
    /**
     * @return int
     */
    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }
    /**
     * @return int
     */
    public function getEntriesPerPage(): int
    {
        return $this->entriesPerPage;
    }
    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }
    /**
     * @return int
     */
    public function getTotalEntries(): int
    {
        return $this->totalEntries;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return (array) ($this);
    }
}