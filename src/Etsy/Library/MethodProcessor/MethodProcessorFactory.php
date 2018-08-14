<?php

namespace App\Etsy\Library\MethodProcessor;

use App\Library\Processor\ProcessorInterface;

class MethodProcessorFactory
{
    /**
     * @var MethodProcessorFactory $instance
     */
    private static $instance;
    /**
     * @var string $namespacePrefix
     */
    private $namespacePrefix;
    /**
     * @param string $namespacePrefix
     * @return MethodProcessorFactory
     */
    public static function create(string $namespacePrefix)
    {
        static::$instance = (static::$instance instanceof static) ? static::$instance : new static($namespacePrefix);

        return static::$instance;
    }
    /**
     * ItemFilterClassFactory constructor.
     * @param string $namespacePrefix
     */
    private function __construct(
        string $namespacePrefix
    ) {
        $this->namespacePrefix = $namespacePrefix;
    }
    /**
     * @return string
     */
    public function getNamespacePrefix(): string
    {
        return $this->namespacePrefix;
    }
    /**
     * @param string $itemFilterClassName
     * @return ProcessorInterface
     */
    public function getItemFilterMethodProcessor(string $itemFilterClassName): ProcessorInterface
    {
        $class = sprintf(
            '%s\%s',
            static::$instance->getNamespacePrefix(),
            $itemFilterClassName
        );

        return new $class();
    }
}