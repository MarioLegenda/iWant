<?php

namespace App\Bonanza\Business\ItemFilter;

use App\Bonanza\Library\Dynamic\DynamicConfiguration;
use App\Bonanza\Library\Dynamic\DynamicErrors;
use App\Bonanza\Library\Dynamic\DynamicInterface;
use App\Bonanza\Library\Dynamic\DynamicMetadata;
use App\Bonanza\Library\ItemFilter\ItemFilterClassFactory;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Util;
use App\Bonanza\Presentation\Model\ItemFilter as ItemFilterModel;

class ItemFilterFactory
{
    /**
     * @param iterable $metadataIterable
     * @return TypedArray
     */
    public function createFromMetadataIterable(iterable $metadataIterable): TypedArray
    {
        $itemFiltersGen = Util::createGenerator($metadataIterable);

        $itemFilters = TypedArray::create('string', DynamicInterface::class);
        foreach ($itemFiltersGen as $item) {
            /** @var ItemFilterModel $itemFilterModel */
            $itemFilterModel = $item['item'];

            $itemFilterMetadata = $itemFilterModel->getItemFilterMetadata();

            $itemFilters[$itemFilterMetadata->getNameValue()] = $this->create($itemFilterModel->getItemFilterMetadata()->toArray());
        }

        return $itemFilters;
    }
    /**
     * @param array $metadata
     * @return DynamicInterface
     */
    private function create(array $metadata): DynamicInterface
    {
        $class = ItemFilterClassFactory::create('App\Bonanza\Library')
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