<?php

namespace App\Etsy\Library\Processor;

use App\Library\Processor\ProcessorInterface;
use App\Library\Tools\LockedImmutableHashSet;

class ShippingInfoProcessor implements ProcessorInterface
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
        $this->processed = 'shipping/info';
    }
    /**
     * @return string
     */
    public function getProcessed(): string
    {
        return $this->processed;
    }
    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return '';
    }
    /**
     * @param LockedImmutableHashSet $options
     * @return ProcessorInterface
     */
    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        $message = sprintf(
            '%s::setOptions() is disabled for %s',
            get_class($this),
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
}