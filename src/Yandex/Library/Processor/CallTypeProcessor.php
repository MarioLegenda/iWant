<?php

namespace App\Yandex\Library\Processor;


use App\Library\Processor\ProcessorInterface;
use App\Library\Tools\LockedImmutableHashSet;
use App\Yandex\Library\Request\CallType;

class CallTypeProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * @var CallType $callType
     */
    private $callType;
    /**
     * CallTypeProcessor constructor.
     * @param CallType $callType
     */
    public function __construct(
        CallType $callType
    ) {
        $this->callType = $callType;
    }
    /**
     * @param LockedImmutableHashSet $options
     * @return ProcessorInterface
     */
    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        $message = sprintf(
            'Method %s::setOptions() is disabled',
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

    public function process(): ProcessorInterface
    {
        $this->processed = (string) $this->callType->getValue();

        return $this;
    }
    /**
     * @return string
     */
    public function getProcessed(): string
    {
        return $this->processed;
    }
}