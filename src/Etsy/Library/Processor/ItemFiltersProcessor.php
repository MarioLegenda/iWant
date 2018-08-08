<?php

namespace App\Etsy\Library\Processor;

use App\Ebay\Library\Dynamic\DynamicInterface;
use App\Ebay\Library\ItemFilter\ItemFilterInterface;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Tools\LockedImmutableHashSet;
use App\Library\Processor\ProcessorInterface;
use App\Library\UrlifyInterface;

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
    public function process() : ProcessorInterface
    {
        $finalProduct = '';
        $count = 0;

        /** @var ItemFilterInterface|DynamicInterface $itemFilter */
        foreach ($this->itemFilters as $itemFilter) {
            if ($itemFilter instanceof UrlifyInterface) {
                $itemFilter->validateDynamic();

                $finalProduct.=$itemFilter->urlify($count).$this->getDelimiter();
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
    public function getDelimiter(): string
    {
        return '&';
    }
    /**
     * @return string
     */
    public function getProcessed() : string
    {
        return rtrim($this->processed, '&');
    }
}