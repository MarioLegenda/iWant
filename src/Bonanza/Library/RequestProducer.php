<?php

namespace App\Bonanza\Library;

use App\Library\Processor\ProcessorInterface;
use App\Library\Infrastructure\Helper\TypedArray;

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
     * @return Request
     */
    public function produce(): string
    {
        $processed = '';

        foreach ($this->processors as $processor) {
            $processed.= $processor->process()->getProcessed().$processor->getDelimiter();
        }

        return $processed;
    }
}