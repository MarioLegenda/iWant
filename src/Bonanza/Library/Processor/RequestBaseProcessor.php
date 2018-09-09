<?php

namespace App\Bonanza\Library\Processor;

use App\Library\Processor\ProcessorInterface;
use App\Library\Tools\LockedImmutableHashSet;

class RequestBaseProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * @var LockedImmutableHashSet $bonanzaApi
     */
    private $bonanzaApi;
    /**
     * FindingApiRequestBaseProcessor constructor.
     * @param iterable $bonanzaApi
     */
    public function __construct(
        iterable $bonanzaApi
    ) {
        $this->bonanzaApi = LockedImmutableHashSet::create($bonanzaApi);
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
    public function process(): ProcessorInterface
    {
        $this->processed = $this->bonanzaApi['base_url'];

        return $this;
    }
    /**
     * @inheritdoc
     */
    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        $message = sprintf(
            'Adding options is disabled in %s',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @inheritdoc
     */
    public function getDelimiter(): string
    {
        return '?';
    }
}