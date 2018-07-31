<?php

namespace App\Ebay\Library\Dynamic;

use App\Ebay\Library\ItemFilter\ItemFilter;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Library\Tools\UrlifyInterface;

class ItemFiltersProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed = '';
    /**
     * @var TypedArray $itemFilters
     */
    private $itemFilters;

    public function __construct(TypedArray $itemFilters)
    {
        $this->itemFilters = $itemFilters;
    }
    /**
     * @return ProcessorInterface
     */
    public function process() : ProcessorInterface
    {
        $finalProduct = '';
        $count = 0;

        /** @var ItemFilter $itemFilter */
        foreach ($this->itemFilters as $itemFilter) {
            if ($itemFilter instanceof UrlifyInterface) {
                $finalProduct.=$itemFilter->urlify($count);
            }

            $count++;
        }

        $this->processed = $finalProduct;

        return $this;
    }
    /**
     * @return string
     */
    public function getProcessed() : string
    {
        return rtrim($this->processed, '&');
    }
}