<?php

namespace App\Etsy\Library\Processor;

use App\Etsy\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;
use App\Library\Tools\LockedImmutableHashSet;

class QueryProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * @var TypedArray $queries
     */
    private $queries;
    /**
     * QueryProcessor constructor.
     * @param TypedArray $queries
     */
    public function __construct(
        TypedArray $queries
    ) {
        $this->queries = $queries;
    }
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface
    {
        $processed = '';

        /** @var Query $query */
        foreach ($this->queries as $query) {
            $processed.=$query->getQuery();
        }

        $this->processed = $processed;

        return $this;
    }
    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return '';
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

    public function getProcessed()
    {
        return $this->processed;
    }
}