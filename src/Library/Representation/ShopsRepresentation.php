<?php

namespace App\Library\Representation;

use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\NativeTaxonomyRepository;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Util\Util;

class ShopsRepresentation
{
    /**
     * @var NativeTaxonomyRepository $nativeTaxonomyRepository
     */
    private $nativeTaxonomyRepository;
    /**
     * @var MarketplaceRepresentation $marketplaceRepresentation
     */
    private $marketplaceRepresentation;
    /**
     * ShopsRepresentation constructor.
     * @param NativeTaxonomyRepository $nativeTaxonomyRepository
     * @param MarketplaceRepresentation $marketplaceRepresentation
     */
    public function __construct(
        NativeTaxonomyRepository $nativeTaxonomyRepository,
        MarketplaceRepresentation $marketplaceRepresentation
    ) {
        $this->nativeTaxonomyRepository = $nativeTaxonomyRepository;
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
                'name' => 'am-autoparts',
                'global_id' => GlobalIdInformation::EBAY_MOTOR,
                'category' => $allNormalizedCategories['Autoparts & Mechanics'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'thrift.books',
                'global_id' => GlobalIdInformation::EBAY_US,
                'category' => $allNormalizedCategories['Books, Music & Movies'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'rebuy-shop',
                'global_id' => GlobalIdInformation::EBAY_DE,
                'category' => $allNormalizedCategories['Books, Music & Movies'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'www.csl-computer.com',
                'global_id' => GlobalIdInformation::EBAY_DE,
                'category' => $allNormalizedCategories['Computers, Mobile & Games'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'argos',
                'global_id' => GlobalIdInformation::EBAY_GB,
                'category' => $allNormalizedCategories['Home & Garden'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'decluttr_store',
                'global_id' => GlobalIdInformation::EBAY_US,
                'category' => $allNormalizedCategories['Computers, Mobile & Games'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'get_importcds',
                'global_id' => GlobalIdInformation::EBAY_US,
                'category' => $allNormalizedCategories['Books, Music & Movies'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'discover-books',
                'global_id' => GlobalIdInformation::EBAY_US,
                'category' => $allNormalizedCategories['Books, Music & Movies'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'ppretail',
                'global_id' => GlobalIdInformation::EBAY_GB,
                'category' => null,
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => true,
            ],
            [
                'name' => 'superdrystore',
                'global_id' => GlobalIdInformation::EBAY_GB,
                'category' => $allNormalizedCategories['Fashion'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
            ],
            [
                'name' => 'blowitoutahere',
                'global_id' => GlobalIdInformation::EBAY_US,
                'category' => null,
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => true,
            ],
            [
                'name' => 'betterworldbooks',
                'global_id' => GlobalIdInformation::EBAY_US,
                'category' =>  $allNormalizedCategories['Books, Music & Movies'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => false,
            ],
            [
                'name' => 'mediamarkt',
                'global_id' => GlobalIdInformation::EBAY_DE,
                'category' =>  null,
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => true,
            ],
            [
                'name' => 'bhfo',
                'global_id' => GlobalIdInformation::EBAY_US,
                'category' =>  $allNormalizedCategories['Fashion'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => false,
            ],
            [
                'name' => 'kayfast1',
                'global_id' => GlobalIdInformation::EBAY_GB,
                'category' =>  $allNormalizedCategories['Autoparts & Mechanics'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => false,
            ],
            [
                'name' => 'no.1outlet',
                'global_id' => GlobalIdInformation::EBAY_DE,
                'category' =>  $allNormalizedCategories['Fashion'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => false,
            ],
            [
                'name' => '*memoryking*',
                'global_id' => GlobalIdInformation::EBAY_DE,
                'category' =>  $allNormalizedCategories['Computers, Mobile & Games'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => false,
            ],
            [
                'name' => 'gregmorriscards',
                'global_id' => GlobalIdInformation::EBAY_US,
                'category' =>  $allNormalizedCategories['Antiques, Art & Collectibles'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => false,
            ],
            [
                'name' => 'pharmapacks',
                'global_id' => GlobalIdInformation::EBAY_US,
                'category' =>  $allNormalizedCategories['Health & Beauty'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => false,
            ],
            [
                'name' => 'wordery',
                'global_id' => GlobalIdInformation::EBAY_GB,
                'category' =>  $allNormalizedCategories['Books, Music & Movies'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => false,
            ],
            [
                'name' => 'lmelectrical',
                'global_id' => GlobalIdInformation::EBAY_GB,
                'category' =>  $allNormalizedCategories['Home & Garden'],
                'marketplace' => $this->marketplaceRepresentation->ebay,
                'uncategorised' => false,
            ],
            [
                'name' => 'ModernMud',
                'global_id' => null,
                'category' => $allNormalizedCategories['Crafts & Handmade'],
                'marketplace' => $this->marketplaceRepresentation->etsy,
            ],
            [
                'name' => 'Barruntando',
                'global_id' => null,
                'category' => $allNormalizedCategories['Crafts & Handmade'],
                'marketplace' => $this->marketplaceRepresentation->etsy,
            ],
            [
                'name' => 'cumbucachic',
                'global_id' => null,
                'category' => $allNormalizedCategories['Crafts & Handmade'],
                'marketplace' => $this->marketplaceRepresentation->etsy,
            ],
            [
                'name' => 'CindySearles',
                'global_id' => null,
                'category' => $allNormalizedCategories['Crafts & Handmade'],
                'marketplace' => $this->marketplaceRepresentation->etsy,
            ],
            [
                'name' => 'Shekhtwoman',
                'global_id' => null,
                'category' => $allNormalizedCategories['Crafts & Handmade'],
                'marketplace' => $this->marketplaceRepresentation->etsy,
            ],
            [
                'name' => 'PurpleFishBowl2',
                'global_id' => null,
                'category' => $allNormalizedCategories['Crafts & Handmade'],
                'marketplace' => $this->marketplaceRepresentation->etsy,
            ],
        ];
    }
    /**
     * @return iterable
     */
    private function createNormalizedCategoryAssociativeRepresentation(): iterable
    {
        $allNormalizedCategories = Util::createGenerator($this->nativeTaxonomyRepository->findAll());

        $associativeNormalized = TypedArray::create('string', NativeTaxonomy::class);
        foreach ($allNormalizedCategories as $entry) {
            /** @var NativeTaxonomy $normalizedCategory */
            $normalizedCategory = $entry['item'];

            $associativeNormalized[$normalizedCategory->getName()] = $normalizedCategory;
        }

        return $associativeNormalized;
    }
}