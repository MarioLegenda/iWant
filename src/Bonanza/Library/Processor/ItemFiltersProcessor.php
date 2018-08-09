<?php

namespace App\Bonanza\Library\Processor;

use App\Bonanza\Library\Dynamic\DynamicInterface;
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

        /** @var DynamicInterface|DynamicInterface $itemFilter */
        foreach ($this->itemFilters as $itemFilter) {
            if ($itemFilter instanceof UrlifyInterface) {
                $itemFilter->validateDynamic();

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
    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return '&';
    }
}