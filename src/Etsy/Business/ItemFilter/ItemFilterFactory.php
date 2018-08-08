<?php

namespace App\Etsy\Business\ItemFilter;

use App\Etsy\Library\Dynamic\DynamicConfiguration;
use App\Etsy\Library\Dynamic\DynamicErrors;
use App\Etsy\Library\Dynamic\DynamicInterface;
use App\Etsy\Library\Dynamic\DynamicMetadata;
use App\Etsy\Presentation\Model\ItemFilterModel;
use App\Library\Infrastructure\Helper\TypedArray;

class ItemFilterFactory
{
    /**
     * @var ItemFilterFactory $instance
     */
    private static $instance;
    /**
     * @var string $namespacePrefix
     */
    private $namespacePrefix;
    /**
     * @var TypedArray|ItemFilterModel $itemFilters
     */
    private $itemFilters;

    public static function create(
        string $namespacePrefix,
        TypedArray $itemFilters
    ) {
        static::$instance = (static::$instance instanceof ItemFilterFactory) ?
            static::$instance :
            new static($namespacePrefix, $itemFilters);

        return static::$instance;
    }
    /**
     * ItemFilterFactory constructor.
     * @param string $namespacePrefix
     * @param TypedArray $itemFilters
     */
    private function __construct(
        string $namespacePrefix,
        TypedArray $itemFilters
    ) {
        $this->itemFilters = $itemFilters;
        $this->namespacePrefix = $namespacePrefix;
    }

    /**
     * @return TypedArray|DynamicInterface[]
     */
    public function createItemFilters(): TypedArray
    {
        $itemFilters = TypedArray::create('string', DynamicInterface::class);
        /** @var ItemFilterModel $itemFilter */
        foreach ($this->itemFilters as $itemFilter) {
            $itemFilterMetadata = $itemFilter->getItemFilterMetadata();
            $name = $itemFilterMetadata->getName();

            $class = sprintf(
                '%s\%s',
                $this->namespacePrefix,
                $name->getKey()
            );

            $dynamicMetadata = $this->createDynamicMetadata(
                $name->getValue(),
                $itemFilterMetadata->getValue()
            );

            $dynamicConfiguration = $this->createDynamicConfiguration();
            $dynamicErrors = $this->createDynamicErrors();

            $itemFilters[$name->getKey()] = new $class(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );
        }

        return $itemFilters;
    }
    /**
     * @param string $name
     * @param array $value
     * @return DynamicMetadata
     */
    private function createDynamicMetadata(
        string $name,
        array $value
    ): DynamicMetadata {
        return new DynamicMetadata(
            $name,
            $value
        );
    }
    /**
     * @return DynamicConfiguration
     */
    private function createDynamicConfiguration(): DynamicConfiguration
    {
        return new DynamicConfiguration(false, false);
    }
    /**
     * @return DynamicErrors
     */
    private function createDynamicErrors(): DynamicErrors
    {
        return new DynamicErrors();
    }
}