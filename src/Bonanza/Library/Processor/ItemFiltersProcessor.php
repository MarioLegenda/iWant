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
    private $processed;
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
        $count = 0;

        $processedItemFilters = [];
        /** @var DynamicInterface|DynamicInterface $itemFilter */
        foreach ($this->itemFilters as $itemFilter) {
            if ($itemFilter instanceof UrlifyInterface) {
                $processedItemFilters[$itemFilter->getDynamicMetadata()->getName()] = $itemFilter->urlify();
            }

            $count++;
        }

        $this->processed = json_encode($processedItemFilters);

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
    public function getProcessed()
    {
        return $this->processed;
    }
    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return '&';
    }
}