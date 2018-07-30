<?php

namespace App\Library\Http\Request\Uniformity;

use App\Library\Http\Request\Contract\PaginatedInternalizedRequestInterface;

class PaginatedInternalizedRequest implements PaginatedInternalizedRequestInterface
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
     * @var string $locale
     */
    private $locale;
    /**
     * PaginatedInternalizedRequest constructor.
     * @param int $offset
     * @param int $limit
     * @param string $locale
     */
    public function __construct(int $offset, int $limit, string $locale)
    {
        $this->offset = $offset;
        $this->limit = $limit;
        $this->locale = $locale;
    }
    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
    /**
     * @param iterable $data
     * @return PaginatedInternalizedRequestInterface
     */
    public static function createFromIterable(iterable $data): PaginatedInternalizedRequestInterface
    {
        return new static($data['offset'], $data['limit'], $data['locale']);
    }
}