<?php

namespace App\Component\Selector\Ebay\Selector;

use App\Component\Selector\Ebay\ObserverSelectorInterface;
use App\Doctrine\Entity\ApplicationShop;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\FindingApi\Model\FindItemsInEbayStores;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;

class SelectorTwo implements ObserverSelectorInterface
{
    /**
     * @var ApplicationShop $applicationShop
     */
    private $applicationShop;
    /**
     * SelectorOne constructor.
     * @param ApplicationShop $applicationShop
     */
    public function __construct(
        ApplicationShop $applicationShop
    ) {
        $this->applicationShop = $applicationShop;
    }
    /**
     * @param \SplSubject $subject
     * @return FindingApiModel|null
     */
    public function update(\SplSubject $subject): ?FindingApiRequestModelInterface
    {
        return $this->createModel();
    }
    /**
     * @return TypedArray
     */
    private function getQueries(): TypedArray
    {
        $queries = TypedArray::create('integer', Query::class);

        $queries[] = new Query(
            'storeName',
            $this->applicationShop->getApplicationName()
        );

        $queries[] = new Query(
            'GLOBAL-ID',
            $this->applicationShop->getGlobalId()
        );

        $queries[] = new Query(
            'paginationInput.entriesPerPage',
            1
        );

        return $queries;
    }
    /**
     * @return TypedArray
     */
    private function getItemFilters(): TypedArray
    {
        $itemFilters = TypedArray::create('integer', ItemFilter::class);

        $freeShippingOnly = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            ItemFilterConstants::FREE_SHIPPING_ONLY,
            [true]
        ));

        $featuredOnly = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            ItemFilterConstants::FEATURED_ONLY,
            [true]
        ));

        $getItFastOnly = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            ItemFilterConstants::GET_IT_FAST_ONLY,
            [true]
        ));

        $hideDuplicatedItems = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            ItemFilterConstants::HIDE_DUPLICATE_ITEMS,
            [true]
        ));

        $itemFilters[] = $freeShippingOnly;
        $itemFilters[] = $featuredOnly;
        $itemFilters[] = $getItFastOnly;
        $itemFilters[] = $hideDuplicatedItems;

        return $itemFilters;
    }
    /**
     * @return FindingApiRequestModelInterface
     */
    private function createModel(): FindingApiRequestModelInterface
    {
        $queries = $this->getQueries();
        $itemFilters = $this->getItemFilters();

        $findItemsInEbayStores = new FindItemsInEbayStores($queries);

        return new FindingApiModel($findItemsInEbayStores, $itemFilters);
    }
}