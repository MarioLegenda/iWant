<?php

namespace App\Bonanza\Library\Processor;

use App\Library\Tools\LockedImmutableHashSet;
use App\Bonanza\Presentation\Model\CallTypeInterface;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;

class CallTypeProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * @var CallTypeInterface $callType
     */
    private $callType;
    /**
     * CallTypeProcessor constructor.
     * @param CallTypeInterface $callType
     */
    public function __construct(CallTypeInterface $callType)
    {
        $this->callType = $callType;
    }
    /**
     * @inheritdoc
     */
    public function process(): ProcessorInterface
    {
        $this->processed = $this->callType->getOperationName().$this->getDelimiter();

        return $this;
    }
    /**
     * @inheritdoc
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
        return '=';
    }

    /**
     * @inheritdoc
     */
    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        $message = sprintf(
            'Options cannot be set on %s',
            CallTypeProcessor::class
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param $queryValues
     * @return string
     */
    private function normalizeQueryValues(TypedArray $queryValues): string
    {
        $queryValues = (count($queryValues) === 1) ? $queryValues->toArray()[0] : $queryValues->toArray();

        $normalized = null;
        if (is_array($queryValues)) {
            $normalized = implode(',', $queryValues);
        } else if (is_string($queryValues)) {
            $normalized = $queryValues;
        }

        return $normalized;
    }
}