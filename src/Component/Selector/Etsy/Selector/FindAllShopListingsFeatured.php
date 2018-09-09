<?php

namespace App\Component\Selector\Etsy\Selector;

use App\Component\Selector\Etsy\ObserverSelectorInterface;
use App\Doctrine\Entity\ApplicationShop;
use App\Etsy\Library\ItemFilter\ItemFilterType;
use App\Etsy\Library\Type\MethodType;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Etsy\Presentation\Model\ItemFilterMetadata;
use App\Etsy\Presentation\Model\ItemFilterModel;
use App\Etsy\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;

class FindAllShopListingsFeatured implements ObserverSelectorInterface
{
    /**
     * @var ApplicationShop $applicationShop
     */
    private $applicationShop;
    /**
     * FindAllShopListingsFeatured constructor.
     * @param ApplicationShop $applicationShop
     */
    public function __construct(ApplicationShop $applicationShop)
    {
        $this->applicationShop = $applicationShop;
    }
    /**
     * @param \SplSubject $subject
     * @return EtsyApiModel
     */
    public function update(\SplSubject $subject): EtsyApiModel
    {
        return $this->createModel();
    }
    /**
     * @return EtsyApiModel
     */
    private function createModel(): EtsyApiModel
    {
        $methodType = MethodType::fromKey('findAllShopListingsFeatured');

        $queries = $this->getQueries();
        $itemFilters = $this->getItemFilters();

        return new EtsyApiModel(
            $methodType,
            $itemFilters,
            $queries
        );
    }
    /**
     * @return iterable|TypedArray
     */
    private function getQueries(): iterable
    {
        $queries = TypedArray::create('integer', Query::class);

        $shopsPart = new Query('/shops/');
        $shopId = new Query(sprintf('%s/listings/featured?', $this->applicationShop->getApplicationName()));

        $queries[] = $shopsPart;
        $queries[] = $shopId;

        return $queries;
    }
    /**
     * @return iterable|TypedArray
     */
    private function getItemFilters(): iterable
    {
        $itemFilters = TypedArray::create('integer', ItemFilterModel::class);

        $limitMetadata = new ItemFilterMetadata(
            ItemFilterType::fromKey('Limit'),
            [1]
        );

        $itemFilters[] = new ItemFilterModel($limitMetadata);

        return $itemFilters;
    }
}