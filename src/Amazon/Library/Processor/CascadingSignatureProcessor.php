<?php

namespace App\Amazon\Library\Processor;

use App\Amazon\Library\Processor\Signature\FinalProcessor;
use App\Amazon\Library\Processor\Signature\SignatureData;
use App\Amazon\Library\Processor\Signature\SignatureProcessorInterface;
use App\Ebay\Library\Tools\LockedImmutableHashSet;
use App\Library\Processor\ProcessorInterface;

class CascadingSignatureProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * @var ProcessorInterface[] $processors
     */
    private $processors;
    /**
     * @var SignatureData $data
     */
    private $data;
    /**
     * CascadingSignatureProcessor constructor.
     * @param SignatureData $data
     * @param iterable|SignatureProcessorInterface[] $processors
     */
    public function __construct(SignatureData $data, iterable $processors)
    {
        $this->processors = $processors;
        $this->data = $data;
    }
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface
    {
        /** @var SignatureData $signature */
        $signature = null;
        /** @var SignatureProcessorInterface $processor */
        foreach ($this->processors as $processor) {
            $signature = $processor->process($this->data);

            if (is_null($signature)) {
                $message = sprintf(
                    'Processor %s failed to process and returned null',
                    get_class($processor)
                );

                throw new \RuntimeException($message);
            }
        }

        $this->processed = $signature->get(FinalProcessor::class);

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
     * @throws \RuntimeException
     */
    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        $message = sprintf(
            '%s::setOptions() is not implemented and cannot be used',
            CascadingSignatureProcessor::class
        );

        throw new \RuntimeException($message);
    }
}