<?php

namespace App\Ebay\Business\ItemFilter;

use App\Ebay\Library\Dynamic\DynamicConfiguration;
use App\Ebay\Library\Dynamic\DynamicErrors;
use App\Ebay\Library\Dynamic\DynamicMetadata;
use App\Ebay\Library\ItemFilter\ItemFilterClassFactory;
use App\Ebay\Library\ItemFilter\ItemFilterInterface;

class ItemFilterFactory
{
    /**
     * @param array $metadata
     * @return ItemFilterInterface
     */
    public function create(array $metadata): ItemFilterInterface
    {
        $class = ItemFilterClassFactory::create('App\Ebay\Library')
            ->getItemFilterClass($metadata['name']);

        $dynamicMetadata = $this->createDynamicMetadata(
            $metadata['name'],
            $metadata['value']
        );

        $dynamicConfiguration = $this->createDynamicConfiguration($metadata['value']);

        return new $class($dynamicMetadata, $dynamicConfiguration, new DynamicErrors());
    }
    /**
     * @param string $name
     * @param array $value
     * @return DynamicMetadata
     */
    private function createDynamicMetadata(string $name, array $value): DynamicMetadata
    {
        return new DynamicMetadata($name, $value);
    }
    /**
     * @param array $value
     * @return DynamicConfiguration
     */
    private function createDynamicConfiguration(array $value): DynamicConfiguration
    {
        return new DynamicConfiguration(
            (count($value) > 1),
            false
        );
    }
}