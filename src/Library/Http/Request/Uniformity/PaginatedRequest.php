<?php

namespace App\Library\Http\Request\Uniformity;

use App\Library\Http\Request\Contract\PaginatedRequestInterface;

class PaginatedRequest implements PaginatedRequestInterface
{
    /**
     * @var int $offset
     */
    private $offset;
    /**
     * @var int $limit
     */
    private $limit;
    /**
     * PaginatedRequest constructor.
     * @param int $offset
     * @param int $limit
     */
    public function __construct(int $offset, int $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }
    /**
     * @inheritdoc
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
    /**
     * @inheritdoc
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}