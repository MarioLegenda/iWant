<?php

namespace App\Ebay\Library\Processor;

use App\Ebay\Presentation\Model\Query;
use App\Library\Tools\LockedImmutableHashSet;
use App\Ebay\Presentation\Model\CallTypeInterface;
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
        $final = '';

        if ($this->callType->hasQueries()) {

            $queries = $this->callType->getQueries();
            /** @var Query $query */
            foreach ($queries as $query) {
                $final.=sprintf(
                    '%s=%s&',
                    $query->getName(),
                    $query->getValue()
                );
            }
        }

        $this->processed = rtrim($final, '&');

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
        return '&';
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
}