<?php

namespace App\Component\Search\Ebay\Model\Request;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Pagination implements ArrayNotationInterface
{
    /**
     * @var int|null $limit
     */
    private $limit;
    /**
     * @var int|null $page
     */
    private $page;
    /**
     * Pagination constructor.
     * @param int $limit
     * @param int $page
     */
    public function __construct(
        int $limit,
        int $page
    ) {
        $this->limit = $limit;
        $this->page = $page;
    }
    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }
    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'limit' => $this->getLimit(),
            'page' => $this->getPage(),
        ];
    }
}