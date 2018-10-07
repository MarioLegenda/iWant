<?php

namespace App\Component\Search\Ebay\Business\Factory\Metadata;

use App\Doctrine\Entity\NativeTaxonomy;
use App\Library\Infrastructure\Helper\TypedArray;

class TaxonomyMetadata
{
    /**
     * @var NativeTaxonomy $nativeTaxonomy
     */
    private $nativeTaxonomy;
    /**
     * @var TypedArray $ebayRootCategories
     */
    private $ebayRootCategories;
    /**
     * TaxonomyMetadata constructor.
     * @param NativeTaxonomy $nativeTaxonomy
     * @param TypedArray $ebayRootCategories
     */
    public function __construct(
        NativeTaxonomy $nativeTaxonomy,
        TypedArray $ebayRootCategories
    ) {
        $this->nativeTaxonomy = $nativeTaxonomy;
        $this->ebayRootCategories = $ebayRootCategories;
    }
    /**
     * @return NativeTaxonomy
     */
    public function getNativeTaxonomy(): NativeTaxonomy
    {
        return $this->nativeTaxonomy;
    }
    /**
     * @return TypedArray
     */
    public function getEbayRootCategories(): TypedArray
    {
        return $this->ebayRootCategories;
    }
    /**
     * @param string $globalId
     * @return array|null
     */
    public function getByGlobalId(string $globalId): ?array
    {
        if (isset($this->ebayRootCategories[$globalId])) {
            return $this->ebayRootCategories[$globalId];
        }

        return null;
    }
}