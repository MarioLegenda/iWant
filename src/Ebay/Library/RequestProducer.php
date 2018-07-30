<?php

namespace App\Ebay\Library;

use App\Ebay\Library\Dynamic\ProcessorInterface;
use App\Library\Infrastructure\Helper\TypedArray;

class RequestProducer
{
    /**
     * @var RequestBase $requestBase
     */
    private $requestBase;
    /**
     * @var TypedArray|ProcessorInterface[] $processors
     */
    private $processors;
    /**
     * RequestProducer constructor.
     * @param RequestBase $requestBase
     * @param TypedArray $processors
     */
    public function __construct(
        RequestBase $requestBase,
        TypedArray $processors
    ) {
        $this->requestBase = $requestBase;
        $this->processors = $processors;
    }

    public function produce(): string
    {
        $processed = '';

        foreach ($this->processors as $processor) {
            $processed.= $processor->process()->getProcessed();
        }


    }
}