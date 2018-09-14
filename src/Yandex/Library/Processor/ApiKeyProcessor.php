<?php

namespace App\Yandex\Library\Processor;

use App\Library\Processor\ProcessorInterface;
use App\Library\Tools\LockedImmutableHashSet;

class ApiKeyProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * @var array $yandexApi
     */
    private $yandexApi;
    /**
     * ApiKeyProcessor constructor.
     * @param array $yandexApi
     */
    public function __construct(
        array $yandexApi
    ) {
        $this->yandexApi = LockedImmutableHashSet::create($yandexApi);
    }
    /**
     * @return string
     */
    public function getProcessed(): string
    {
        return $this->processed;
    }
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface
    {
        $this->processed = sprintf(
            '%s=%s',
            'key',
            $this->yandexApi['api_key']
        );

        return $this;
    }

    public function getDelimiter(): string
    {
        return '&';
    }

    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        $message = sprintf(
            'Method %s::setOptions() is disabled',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
}