<?php

namespace App\Bonanza\Library\Processor;

use App\Library\Processor\ProcessorInterface;
use App\Library\Tools\LockedImmutableHashSet;

class RequestBaseProcessor implements ProcessorInterface
{
    /**
     * @param LockedImmutableHashSet $options
     */
    private $options;
    /**
     * @var string $processed
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
        if (!$this->options instanceof LockedImmutableHashSet) {
            $message = sprintf(
                'Options have to be set for %s',
                RequestBaseProcessor::class
            );

            throw new \RuntimeException($message);
        }

        $baseUrl = $this->bonanzaApi['base_url'];
        $names = $this->bonanzaApi['names'];
        $configParams = $this->bonanzaApi['params']->toArray();
        $userParams = $this->options->toArray();

        foreach ($names as $key => $name) {
            $currentProduct = '';
            if (array_key_exists($key, $configParams) and is_string($configParams[$key])) {
                $currentProduct=sprintf(
                    '%s=%s',
                    $name,
                    $configParams[$key]
                );
            }

            if (array_key_exists($key, $configParams)) {
                $param = $configParams[$key];

                if (is_null($param)) {
                    $currentProduct=sprintf(
                        '%s',
                        $name,
                        $configParams[$key]
                    );
                }
            }

            if (array_key_exists($key, $userParams)) {
                $currentProduct=sprintf(
                    '%s=%s',
                    $name,
                    $userParams[$key]
                );
            }

            if (!empty($currentProduct)) {
                $baseUrl.=$currentProduct.'&';
            }
        }

        $this->processed =  rtrim($baseUrl, '&');

        return $this;
    }
    /**
     * @inheritdoc
     */
    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        if ($options->isEmpty()) {
            $message = sprintf(
                'Options cannot be empty for class %s',
                RequestBaseProcessor::class
            );

            throw new \RuntimeException($message);
        }

        $this->options = $options;

        return $this;
    }
    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return '&';
    }
    /**
     * @return string
     */
    public function getProcessed(): string
    {
        return $this->processed;
    }
}