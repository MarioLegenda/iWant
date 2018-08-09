<?php

namespace App\Bonanza\Library\ItemFilter;

class ItemFilterClassFactory
{
    /**
     * @var ItemFilterClassFactory $instance
     */
    private static $instance;
    /**
     * @var string $namespacePrefix
     */
    private $namespacePrefix;
    /**
     * @param string $namespacePrefix
     * @return ItemFilterClassFactory
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
     * @return string
     */
    public function getItemFilterClass(string $itemFilterClassName): string
    {
        return sprintf(
            '%s\ItemFilter\%s',
            static::$instance->getNamespacePrefix(),
            $itemFilterClassName
        );
    }
}