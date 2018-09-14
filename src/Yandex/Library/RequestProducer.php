<?php

namespace App\Yandex\Library;

use App\Library\Infrastructure\Helper\TypedArray;

class RequestProducer
{
    /**
     * @var TypedArray $processors
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