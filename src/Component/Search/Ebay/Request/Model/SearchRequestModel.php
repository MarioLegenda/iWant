<?php

namespace App\Component\Ebay\Search\Request\Model;

class SearchRequestModel
{
    /**
     * @var string $keywords
     */
    private $keywords;
    /**
     * SearchRequestModel constructor.
     * @param string $keywords
     */
    public function __construct(
        string $keywords
    ) {
        $this->keywords = $keywords;
    }
    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }
}