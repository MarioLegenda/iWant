<?php

namespace App\Etsy\Library\Processor;

use App\Library\Tools\LockedImmutableHashSet;
use App\Library\Processor\ProcessorInterface;

class RequestBaseProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * @var LockedImmutableHashSet $etsyApi
     */
    private $etsyApi;
    /**
     * FindingApiRequestBaseProcessor constructor.
     * @param iterable $etsyApi
     */
    public function __construct(iterable $etsyApi)
    {
        $this->etsyApi = LockedImmutableHashSet::create($etsyApi);
    }
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface
    {
        $this->processed = $this->etsyApi['base_url'];

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
            'Method %s::setOptions() is not implemented and cannot be used',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
}