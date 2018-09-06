<?php

namespace App\Library\Representation;

use App\Doctrine\Entity\NormalizedCategory;
use App\Doctrine\Repository\NormalizedCategoryRepository;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Util\Util;

class ShopsRepresentation
{
    /**
     * @var NormalizedCategoryRepository $normalizedCategoryRepository
     */
    private $normalizedCategoryRepository;

    private $marketplaceRepresentation;
    /**
     * ShopsRepresentation constructor.
     * @param NormalizedCategoryRepository $normalizedCategoryRepository
     * @param MarketplaceRepresentation $marketplaceRepresentation
     */
    public function __construct(
        NormalizedCategoryRepository $normalizedCategoryRepository,
        MarketplaceRepresentation $marketplaceRepresentation
    ) {
        $this->normalizedCategoryRepository = $normalizedCategoryRepository;
        $this->marketplaceRepresentation = $marketplaceRepresentation;
    }
    /**
     * @return array
     */
    public function getRepresentation()
    {
        $allNormalizedCategories = $this->createNormalizedCategoryAssociativeRepresentation();

        return [
            [
                'name' => 'musicmagpie',
                'global_id' => GlobalIdInformation::EBAY_GB,
                'category' => $allNormalizedCategories['Books, Music & Movies'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'medimops',
                'global_id' => GlobalIdInformation::EBAY_DE,
                'category' => $allNormalizedCategories['Books, Music & Movies'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'worldofbooks08',
                'global_id' => GlobalIdInformation::EBAY_GB,
                'category' => $allNormalizedCategories['Books, Music & Movies'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'ModernMud',
                'global_id' => null,
                'category' => $allNormalizedCategories['Crafts & Handmade'],
                'marketplace' => $this->marketplaceRepresentation->etsy,
            ]
        ];
    }
    /**
     * @return iterable
     */
    private function createNormalizedCategoryAssociativeRepresentation(): iterable
    {
        $allNormalizedCategories = Util::createGenerator($this->normalizedCategoryRepository->findAll());

        $associativeNormalized = TypedArray::create('string', NormalizedCategory::class);
        foreach ($allNormalizedCategories as $entry) {
            /** @var NormalizedCategory $normalizedCategory */
            $normalizedCategory = $entry['item'];

            $associativeNormalized[$normalizedCategory->getName()] = $normalizedCategory;
        }

        return $associativeNormalized;
    }
}