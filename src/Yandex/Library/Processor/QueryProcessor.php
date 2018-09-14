<?php

namespace App\Yandex\Library\Processor;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;
use App\Library\Tools\LockedImmutableHashSet;
use App\Yandex\Presentation\Model\Query;

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
    public function __construct(TypedArray $queries)
    {
        $this->queries = $queries;
    }

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
    /**
     * @return string
     */
    public function getProcessed(): string
    {
        return $this->processed;
    }
}