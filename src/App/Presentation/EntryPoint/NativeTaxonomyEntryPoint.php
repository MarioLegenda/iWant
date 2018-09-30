<?php

namespace App\App\Presentation\EntryPoint;

use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\EbayRootCategoryRepository;
use App\Doctrine\Repository\NativeTaxonomyRepository;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;

class NativeTaxonomyEntryPoint
{
    /**
     * @var NativeTaxonomyRepository $nativeTaxonomyRepository
     */
    private $nativeTaxonomyRepository;
    /**
     * @var EbayRootCategoryRepository $ebayRootCategoryRepository
     */
    private $ebayRootCategoryRepository;
    /**
     * NativeTaxonomyEntryPoint constructor.
     * @param NativeTaxonomyRepository $nativeTaxonomyRepository
     * @param EbayRootCategoryRepository $ebayRootCategoryRepository
     */
    public function __construct(
        NativeTaxonomyRepository $nativeTaxonomyRepository,
        EbayRootCategoryRepository $ebayRootCategoryRepository
    ) {
        $this->nativeTaxonomyRepository = $nativeTaxonomyRepository;
        $this->ebayRootCategoryRepository = $ebayRootCategoryRepository;
    }
    /**
     * @return TypedArray
     */
    public function getNativeTaxonomies(): TypedArray
    {
        return TypedArray::create('integer', NativeTaxonomy::class, $this->nativeTaxonomyRepository->findAll());
    }
}