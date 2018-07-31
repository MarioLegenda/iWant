<?php

namespace App\Ebay\Business;

use App\Ebay\Business\ItemFilter\ItemFilterFactory;
use App\Ebay\Library\ItemFilter\ItemFilterInterface;
use App\Ebay\Presentation\Model\ItemFilter as ItemFilterModel;
use App\Ebay\Library\ItemFilter\ItemFilterClassFactory;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\RequestBase;
use App\Ebay\Library\Tools\LockedImmutableHashSet;
use App\Ebay\Library\Type\OperationType;
use App\Ebay\Source\FinderSource;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterDynamic;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Util;
use App\Ebay\Library\Dynamic\ItemFiltersProcessor;

class Finder
{
    /**
     * @var FinderSource $finderSource
     */
    private $finderSource;
    /**
     * @var RequestBase $requestBase
     */
    private $requestBase;
    /**
     * Finder constructor.
     * @param FinderSource $finderSource
     * @param RequestBase $requestBase
     */
    public function __construct(
        FinderSource $finderSource,
        RequestBase $requestBase
    ) {
        $this->finderSource = $finderSource;
        $this->requestBase = $requestBase;
    }
    /**
     * @param FindingApiRequestModelInterface $model
     */
    public function query(FindingApiRequestModelInterface $model)
    {
        $userParams = LockedImmutableHashSet::create([
            'operation_name' => (string) OperationType::fromValue($model->getCallType()->getOperationName()),
        ]);

        $mainUrl = $this->requestBase->getBaseUrl($userParams);

        $processor = new ItemFiltersProcessor($this->createItemFilters($model));

        $processedItemFilterString = $processor->process()->getProcessed();
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return TypedArray
     */
    private function createItemFilters(FindingApiRequestModelInterface $model): TypedArray
    {
        $itemFilterFactory = new ItemFilterFactory();
        $itemFiltersGen = Util::createGenerator($model->getItemFilters()->toArray());

        $itemFilters = TypedArray::create('integer', ItemFilterInterface::class);
        foreach ($itemFiltersGen as $item) {
            /** @var ItemFilterModel $itemFilterModel */
            $itemFilterModel = $item['item'];
            /** @var ItemFilterDynamic $itemFilter */
            $itemFilters[] = $itemFilterFactory->create($itemFilterModel->getItemFilterMetadata()->toArray());
        }

        return $itemFilters;
    }
}