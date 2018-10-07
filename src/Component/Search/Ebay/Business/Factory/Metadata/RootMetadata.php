<?php

namespace App\Component\Search\Ebay\Business\Factory\Metadata;

use App\Library\Infrastructure\Helper\TypedArray;

class RootMetadata
{
    /**
     * @var string $globalId
     */
    private $globalId;
    /**
     * @var TypedArray $shops
     */
    private $shops;
    /**
     * @var TaxonomyMetadata $taxonomyMetadata
     */
    private $taxonomyMetadata;
    /**
     * RootMetadata constructor.
     * @param string $globalId
     * @param TypedArray $shops
     * @param TaxonomyMetadata $taxonomyMetadata
     */
    public function __construct(
        string $globalId,
        TypedArray $shops,
        TaxonomyMetadata $taxonomyMetadata = null
    ) {
        $this->globalId = $globalId;
        $this->shops = $shops;
        $this->taxonomyMetadata = $taxonomyMetadata;
    }
    /**
     * @return string
     */
    public function getGlobalId(): string
    {
        return $this->globalId;
    }
    /**
     * @return TypedArray
     */
    public function getShops(): TypedArray
    {
        return $this->shops;
    }
    /**
     * @return TaxonomyMetadata
     */
    public function getTaxonomyMetadata(): ?TaxonomyMetadata
    {
        return $this->taxonomyMetadata;
    }


}