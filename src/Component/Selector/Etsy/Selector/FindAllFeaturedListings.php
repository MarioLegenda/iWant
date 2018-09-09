<?php

namespace App\Component\Selector\Etsy\Selector;

use App\Component\Selector\Etsy\ObserverSelectorInterface;
use App\Doctrine\Entity\ApplicationShop;
use App\Etsy\Library\ItemFilter\ItemFilterType;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Type\MethodType;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Etsy\Presentation\Model\ItemFilterMetadata;
use App\Etsy\Presentation\Model\ItemFilterModel;
use App\Etsy\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;

class FindAllFeaturedListings implements ObserverSelectorInterface
{
    /**
     * @var ApplicationShop $applicationShop
     */
    private $applicationShop;
    /**
     * FindAllFeaturedListings constructor.
     * @param ApplicationShop $applicationShop
     */
    public function __construct(ApplicationShop $applicationShop)
    {
        $this->applicationShop = $applicationShop;
    }

    public function update(\SplSubject $subject): ?EtsyApiModel
    {
        $methodType = MethodType::fromKey('findAllShopListingsFeatured');

        $queries = TypedArray::create('integer', Query::class);

        $shopsPart = new Query('/shops/');
        $shopId = new Query(sprintf('%s/listings/featured?', $this->applicationShop->getApplicationName()));

        $queries[] = $shopsPart;
        $queries[] = $shopId;

        $itemFilters = TypedArray::create('integer', ItemFilterModel::class);

        $limitMetadata = new ItemFilterMetadata(
            ItemFilterType::fromKey('Limit'),
            [1]
        );

        $itemFilters[] = new ItemFilterModel($limitMetadata);

        return new EtsyApiModel(
            $methodType,
            $itemFilters,
            $queries
        );
    }
}