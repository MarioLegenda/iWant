<?php

namespace App\Component\Search\Ebay\Business\Factory;


use App\Component\Search\Ebay\Business\Factory\Metadata\MetadataCollection;
use App\Component\Search\Ebay\Business\Factory\Metadata\RootMetadata;
use App\Component\Search\Ebay\Business\Factory\Metadata\TaxonomyMetadata;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Entity\EbayRootCategory;
use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Doctrine\Repository\EbayRootCategoryRepository;
use App\Doctrine\Repository\NativeTaxonomyRepository;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;

class ModelFactoryMetadataCollector
{
    /**
     * @var ApplicationShopRepository $applicationShopRepository
     */
    private $applicationShopRepository;
    /**
     * @var NativeTaxonomyRepository $nativeTaxonomyRepository
     */
    private $nativeTaxonomyRepository;
    /**
     * @var EbayRootCategoryRepository $ebayRootCategoryRepository
     */
    private $ebayRootCategoryRepository;
    /**
     * ModelFactoryDataCollector constructor.
     * @param ApplicationShopRepository $applicationShopRepository
     * @param NativeTaxonomyRepository $nativeTaxonomyRepository
     * @param EbayRootCategoryRepository $ebayRootCategoryRepository
     */
    public function __construct(
        ApplicationShopRepository $applicationShopRepository,
        NativeTaxonomyRepository $nativeTaxonomyRepository,
        EbayRootCategoryRepository $ebayRootCategoryRepository
    ) {
        $this->applicationShopRepository = $applicationShopRepository;
        $this->nativeTaxonomyRepository = $nativeTaxonomyRepository;
        $this->ebayRootCategoryRepository = $ebayRootCategoryRepository;
    }
    /**
     * @param SearchModel $model
     * @return MetadataCollection
     */
    public function createData(SearchModel $model): MetadataCollection
    {
        return $this->normalizeMetadata($model);
    }
    /**
     * @param SearchModel $model
     * @return MetadataCollection
     */
    private function normalizeMetadata(SearchModel $model): MetadataCollection
    {
        /** @var ApplicationShop[] $applicationShops */
        $applicationShops = $this->applicationShopRepository->findBy([
            'marketplace' => (string) MarketplaceType::fromValue('Ebay'),
        ]);

        $rootMetadata = TypedArray::create('integer', RootMetadata::class);

        $metadataCollection = new MetadataCollection($rootMetadata);

        $globalIdNormalized = [];
        /** @var ApplicationShop $applicationShop */
        foreach ($applicationShops as $applicationShop) {
            if (!empty($model->getGlobalId())) {
                $globalId = $model->getGlobalId();

                if ($applicationShop->getGlobalId() === $globalId) {
                    $globalIdNormalized[$applicationShop->getGlobalId()][] = $applicationShop;

                }

                continue;
            }

            $globalIdNormalized[$applicationShop->getGlobalId()][] = $applicationShop;
        }

        /**
         * @var string $globalId
         * @var ApplicationShop $applicationShops
         */
        foreach ($globalIdNormalized as $globalId => $applicationShops) {
            /** @var null|TaxonomyMetadata $taxonomyMetadataFoundObject */
            $taxonomyMetadataFoundObject = null;

            if (isset($taxonomyMetadata[$globalId])) {
                $taxonomyMetadataFoundObject = $taxonomyMetadata[$globalId];
            }

            $root = new RootMetadata(
                $globalId,
                TypedArray::create('integer', ApplicationShop::class, $applicationShops),
                $taxonomyMetadataFoundObject
            );

            $rootMetadata[] = $root;
        }

        return $metadataCollection;
    }
}