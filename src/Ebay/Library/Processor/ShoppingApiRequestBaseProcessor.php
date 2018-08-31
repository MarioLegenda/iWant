<?php

namespace App\Ebay\Library\Processor;

use App\Library\Processor\ProcessorInterface;
use App\Library\Tools\LockedImmutableHashSet;

class ShoppingApiRequestBaseProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * @var array $ebayShoppingApiMetadata
     */
    private $ebayShoppingApiMetadata;
    /**
     * ShoppingApiRequestBaseProcessor constructor.
     * @param array $ebayShoppingApiMetadata
     */
    public function __construct(array $ebayShoppingApiMetadata)
    {
        $this->ebayShoppingApiMetadata = LockedImmutableHashSet::create($ebayShoppingApiMetadata);
    }
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface
    {
        $baseUrl = $this->ebayShoppingApiMetadata['base_url'];
        $names = $this->ebayShoppingApiMetadata['names'];
        $configParams = $this->ebayShoppingApiMetadata['params']->toArray();

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
    public function getDelimiter(): string
    {
        return '&';
    }
    /**
     * @return mixed|string
     */
    public function getProcessed()
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
            'Method %s::setOptions() is not implemented and should not be used',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
}