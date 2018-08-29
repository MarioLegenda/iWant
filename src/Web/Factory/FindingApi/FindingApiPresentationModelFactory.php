<?php

namespace App\Web\Factory\FindingApi;

use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\FindingApi\Model\FindItemsAdvanced;
use App\Ebay\Presentation\FindingApi\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Web\Library\Converter\Ebay\FindingApiItemFilterConverter;
use App\Web\Library\Converter\Ebay\Observer\LowestPriceObserver;
use App\Web\Library\Converter\Ebay\Observer\PriceRangeObserver;
use App\Web\Library\Converter\Ebay\Observer\QualityObserver;
use App\Web\Model\Request\UniformedRequestModel;

class FindingApiPresentationModelFactory
{
    /**
     * @var FindingApiItemFilterConverter $converter
     */
    private $converter;
    /**
     * FindingApiPresentationModelFactory constructor.
     * @param FindingApiItemFilterConverter $converter
     */
    public function __construct(
        FindingApiItemFilterConverter $converter
    ) {
        $this->converter = $converter;
    }
    /**
     * @param UniformedRequestModel $model
     * @return FindingApiRequestModelInterface
     */
    public function createFromModel(UniformedRequestModel $model): FindingApiRequestModelInterface
    {
        $itemFilters = $this->converter
            ->initializeWithModel($model)
            ->attach(new LowestPriceObserver())
            ->attach(new QualityObserver())
            ->attach(new PriceRangeObserver())
            ->notify();

        $keywordsQuery = new Query('keywords', $model->getKeywords());

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $keywordsQuery;

        $findItemsAdvanced = new FindItemsAdvanced(
            $queries
        );

        $model = new FindingApiModel(
            $findItemsAdvanced,
            $itemFilters
        );

        return $model;
    }
}