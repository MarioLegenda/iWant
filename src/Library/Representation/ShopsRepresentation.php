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
    /**
     * ShopsRepresentation constructor.
     * @param NormalizedCategoryRepository $normalizedCategoryRepository
     */
    public function __construct(
        NormalizedCategoryRepository $normalizedCategoryRepository
    ) {
        $this->normalizedCategoryRepository = $normalizedCategoryRepository;
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
                'marketplace' => MarketplaceType::fromValue('Ebay'),
            ],
            [
                'name' => 'medimops',
                'global_id' => GlobalIdInformation::EBAY_DE,
                'category' => $allNormalizedCategories['Books, Music & Movies'],
                'marketplace' => MarketplaceType::fromValue('Ebay'),
            ],
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