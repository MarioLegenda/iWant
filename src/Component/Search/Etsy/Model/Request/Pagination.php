<?php

namespace App\Component\Search\Etsy\Model\Request;

class Pagination
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
}