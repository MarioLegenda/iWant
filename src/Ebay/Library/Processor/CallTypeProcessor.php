<?php

namespace App\Ebay\Library\Processor;

use App\Ebay\Library\Tools\LockedImmutableHashSet;
use App\Ebay\Presentation\Model\CallTypeInterface;
use App\Library\Infrastructure\Helper\TypedArray;

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
        $queryName = $this->callType->getQueryName();

        $normalizedQueryValues = $this->normalizeQueryValues($this->callType->getQueryValues());

        $this->processed = sprintf(
            '%s=%s',
            $queryName,
            $normalizedQueryValues
        );

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