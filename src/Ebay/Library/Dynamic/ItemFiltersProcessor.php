<?php

namespace Ebay\Library\Dynamic;

use App\Ebay\Library\Dynamic\ProcessorInterface;
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
     * @var TypedArray $itemFilterStorage
     */
    private $itemFilters;

    public function __construct(TypedArray $itemFilterStorage)
    {
        $this->itemFilters = $itemFilterStorage;
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

        $this->processed = $finalProduct.'&';

        return $this;
    }
    /**
     * @return string
     */
    public function getProcessed() : string
    {
        return $this->processed;
    }
}