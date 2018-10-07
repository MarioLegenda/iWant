<?php

namespace App\Component\Search\Ebay\Business\Factory\Metadata;

use App\Library\Infrastructure\Helper\TypedArray;

class MetadataCollection
{
    /**
     * @var TypedArray $metadata
     */
    private $metadata;
    /**
     * MetadataCollection constructor.
     * @param TypedArray $metadata
     */
    public function __construct(
        TypedArray $metadata
    ) {
        $this->metadata = $metadata;
    }
    /**
     * @return TypedArray
     */
    public function getMetadata(): TypedArray
    {
        return $this->metadata;
    }
}