<?php

namespace App\Amazon\Library\Processor;

use App\Amazon\Library\Dynamic\DynamicInterface;
use App\Library\Tools\LockedImmutableHashSet;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;
use App\Library\UrlifyInterface;

class ItemFiltersProcessor implements ProcessorInterface
{
    /**
     * @var TypedArray $itemFilters
     */
    private $itemFilters;
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * ItemFiltersProcessor constructor.
     * @param TypedArray $itemFilters
     */
    public function __construct(TypedArray $itemFilters)
    {
        $this->itemFilters = $itemFilters;
    }
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface
    {
        $finalProduct = '';

        /** @var DynamicInterface $itemFilter */
        foreach ($this->itemFilters as $itemFilter) {
            if ($itemFilter instanceof UrlifyInterface) {
                $itemFilter->validateDynamic();

                $finalProduct.=$itemFilter->urlify().'&';
            }
        }

        $this->processed = $finalProduct;

        return $this;
    }
    /**
     * @return string
     */
    public function getProcessed(): string
    {
        return $this->processed;
    }

    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        // TODO: Implement setOptions() method.
    }

    public function getDelimiter(): string
    {
        // TODO: Implement getDelimiter() method.
    }
}