<?php

namespace App\Ebay\Library;

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
     * @return string
     */
    public function produce(): string
    {
        $processed = '';

        foreach ($this->processors as $processor) {
            $processor->process();

            if (empty($processor->getProcessed())) {
                continue;
            }

            $processed.= $processor->getProcessed().$processor->getDelimiter();
        }

        return rtrim($processed, '&');
    }
}