<?php

namespace App\Etsy\Library;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;

class RequestProducer
{
    /**
     * @var TypedArray|ProcessorInterface[] $processors
     */
    private $processors;
    /**
     * RequestProducer constructor.
     * @param TypedArray $processors
     */
    public function __construct(
        TypedArray $processors
    ) {
        $this->processors = $processors;
    }
    /**
     * @return string
     */
    public function produce(): string
    {
        $processed = '';

        foreach ($this->processors as $processor) {
            $processed.= $processor->process()->getProcessed().$processor->getDelimiter();
        }

        return rtrim($processed, '&');
    }
}