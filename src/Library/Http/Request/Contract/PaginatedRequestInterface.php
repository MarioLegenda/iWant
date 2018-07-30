<?php

namespace App\Library\Http\Request\Contract;

interface PaginatedRequestInterface
{
    /**
     * @return int
     */
    public function getOffset(): int;
    /**
     * @return int
     */
    public function getLimit(): int;
}