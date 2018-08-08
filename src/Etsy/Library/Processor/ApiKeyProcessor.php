<?php

namespace App\Etsy\Library\Processor;

use App\Library\Tools\LockedImmutableHashSet;
use App\Library\Processor\ProcessorInterface;

class ApiKeyProcessor implements ProcessorInterface
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
     * RequestBaseProcessor constructor.
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
        $apiKeyName = $this->etsyApi['names']['api_key'];

        $this->processed = sprintf(
            '%s=%s',
            $apiKeyName,
            $this->etsyApi['api_key']
        );

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
     */
    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        $message = sprintf(
            'Method %s::setOptions() is not implemented and cannot be used',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }

    public function getDelimiter(): string
    {
        return '';
    }
}