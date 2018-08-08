<?php

namespace App\Etsy\Library\Method;

use App\Library\Tools\LockedImmutableHashSet;
use App\Library\Processor\ProcessorInterface;

class FindAllListingActive implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface
    {
        $this->processed = '/listings/active';

        return $this;
    }
    /**
     * @return string
     */
    public function getProcessed(): string
    {
        return $this->processed;
    }
    /**
     * @param LockedImmutableHashSet $options
     * @return ProcessorInterface
     */
    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        $message = sprintf(
            'Method %s::setOptions() is not implemented and cannot be used',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return '?';
    }

    /**
     * @return iterable
     */
    public static function getValidParameters(): iterable
    {
        return [
            'limit',
            'offset',
            'page',
            'keywords',
            'sort_on',
            'sort_order',
            'min_price',
            'max_price',
            'color',
            'color_accuracy',
            'tags',
            'category',
            'location',
            'lat',
            'lon',
            'region',
            'geo_level',
            'accepts_gift_cards',
            'translate_keywords',
        ];
    }
}