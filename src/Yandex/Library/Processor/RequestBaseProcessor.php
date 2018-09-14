<?php

namespace App\Yandex\Library\Processor;

use App\Library\Processor\ProcessorInterface;
use App\Library\Tools\LockedImmutableHashSet;

class RequestBaseProcessor implements ProcessorInterface
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
     * RequestBaseProcessor constructor.
     * @param array $yandexApi
     */
    public function __construct(
        array $yandexApi
    ) {
        $this->yandexApi = LockedImmutableHashSet::create($yandexApi);
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
    public function getDelimiter(): string
    {
        return '';
    }
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface
    {
        $baseUrl = $this->yandexApi['base_url'];
        $names = $this->yandexApi['names'];

        $configParams = (isset($this->yandexApi['params'])) ? $this->yandexApi['params']->toArray() : [];

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

            if (!empty($currentProduct)) {
                $baseUrl.=$currentProduct.'&';
            }
        }

        $this->processed =  rtrim($baseUrl, '&');

        return $this;

    }
    /**
     * @return string
     */
    public function getProcessed(): string
    {
        return $this->processed;
    }
}