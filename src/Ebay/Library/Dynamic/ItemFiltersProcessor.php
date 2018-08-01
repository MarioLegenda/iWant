<?php

namespace App\Ebay\Library\Dynamic;

use App\Ebay\Library\ItemFilter\ItemFilter;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Library\Tools\UrlifyInterface;
use App\Ebay\Library\Tools\LockedImmutableHashSet;

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
     * @inheritdoc
     */
    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        $message = sprintf(
            'Options cannot be set on %s',
            ItemFiltersProcessor::class
        );

        throw new \RuntimeException($message);
    }
    /**
     * @return string
     */
    public function getProcessed() : string
    {
        return rtrim($this->processed, '&');
    }
}