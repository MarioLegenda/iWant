<?php

namespace App\Web\Model\Request;

class UniformedRequestModel
{
    /**
     * @var string $keywords
     */
    private $keywords;
    /**
     * @var iterable $itemFilters
     */
    private $itemFilters;
    /**
     * UniformedRequestModel constructor.
     * @param string $keywords
     * @param iterable $itemFilters
     */
    public function __construct(
        string $keywords,
        iterable $itemFilters
    ) {
        $this->keywords = $keywords;
        $this->itemFilters = RequestItemFilterFactory::create($itemFilters);
    }
    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }
    /**
     * @return iterable
     */
    public function getItemFilters(): iterable
    {
        return $this->itemFilters;
    }
}