<?php

namespace App\Bonanza\Library\Processor;

use App\Library\Processor\ProcessorInterface;
use App\Library\Tools\LockedImmutableHashSet;

class ApiKeyPostHeaderProcessor implements ProcessorInterface
{
    /**
     * @param LockedImmutableHashSet $options
     */
    private $options;
    /**
     * @var array $processed
     */
    private $processed;
    /**
     * @var LockedImmutableHashSet $bonanzaApi
     */
    private $bonanzaApi;
    /**
     * RequestBaseProcessor constructor.
     * @param iterable $bonanzaApi
     */
    public function __construct(
        iterable $bonanzaApi
    ) {
        $this->bonanzaApi = LockedImmutableHashSet::create($bonanzaApi);
    }
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface
    {
        $names = $this->bonanzaApi['names'];
        $configParams = $this->bonanzaApi['params']->toArray();
        $headers = [];
        foreach ($names as $key => $name) {
            if (array_key_exists($key, $configParams) and is_string($configParams[$key])) {
                $headers[$name] = $configParams[$key];
            }
        }

        $this->processed = $headers;

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
     * @return string
     */
    public function getDelimiter(): string
    {
        $message = sprintf(
            '%s is used for constructing header values for POST method and cannot be used for GET methods',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @return array
     */
    public function getProcessed(): array
    {
        return $this->processed;
    }
}