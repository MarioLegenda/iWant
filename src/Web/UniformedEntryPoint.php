<?php

namespace App\Web;

use App\Bonanza\Presentation\BonanzaApiEntryPoint;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Type\OperationType;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Web\Factory\BonanzaResponseModelFactory;
use App\Web\Factory\EtsyResponseModelFactory;
use App\Web\Factory\FindingApi\FindingApiResponseModelFactory;
use App\Web\Library\Converter\Ebay\FindingApiItemFilterConverter;
use App\Web\Library\Converter\Ebay\Observer\LowestPriceObserver;
use App\Web\Library\Converter\Ebay\Observer\PriceRangeObserver;
use App\Web\Library\Converter\Ebay\Observer\QualityObserver;
use App\Web\Model\Request\UniformedRequestModel;

class UniformedEntryPoint
{
    /**
     * @var FindingApiItemFilterConverter $findingApiItemFilterConverter
     */
    private $findingApiItemFilterConverter;
    /**
     * @var EtsyApiEntryPoint $etsyEntryPoint
     */
    private $etsyEntryPoint;
    /**
     * @var FindingApiEntryPoint $findingApiEntryPoint
     */
    private $findingApiEntryPoint;
    /**
     * @var BonanzaApiEntryPoint $bonanzaEntryPoint
     */
    private $bonanzaEntryPoint;
    /**
     * @var EtsyResponseModelFactory $etsyModelFactory
     */
    private $etsyModelFactory;
    /**
     * @var FindingApiResponseModelFactory $findingApiModelFactory
     */
    private $findingApiModelFactory;
    /**
     * @var BonanzaResponseModelFactory $bonanzaModelFactory
     */
    private $bonanzaModelFactory;
    /**
     * UniformedRequestController constructor.
     * @param FindingApiItemFilterConverter $findingApiItemFilterConverter
     * @param EtsyApiEntryPoint $etsyApiEntryPoint
     * @param FindingApiEntryPoint $findingApiEntryPoint
     * @param BonanzaApiEntryPoint $bonanzaApiEntryPoint
     * @param EtsyResponseModelFactory $etsyModelFactory
     * @param FindingApiResponseModelFactory $findingApiModelFactory
     * @param BonanzaResponseModelFactory $bonanzaModelFactory
     */
    public function __construct(
        FindingApiItemFilterConverter $findingApiItemFilterConverter,
        EtsyApiEntryPoint $etsyApiEntryPoint,
        FindingApiEntryPoint $findingApiEntryPoint,
        BonanzaApiEntryPoint $bonanzaApiEntryPoint,
        EtsyResponseModelFactory $etsyModelFactory,
        FindingApiResponseModelFactory $findingApiModelFactory,
        BonanzaResponseModelFactory $bonanzaModelFactory
    ) {
        $this->etsyModelFactory = $etsyModelFactory;
        $this->findingApiModelFactory = $findingApiModelFactory;
        $this->bonanzaModelFactory = $bonanzaModelFactory;

        $this->etsyEntryPoint = $etsyApiEntryPoint;
        $this->findingApiEntryPoint = $findingApiEntryPoint;
        $this->bonanzaEntryPoint = $bonanzaApiEntryPoint;

        $this->findingApiItemFilterConverter = $findingApiItemFilterConverter;
    }
    /**
     * @param UniformedRequestModel $model
     * @return TypedArray
     */
    public function getPresentationModels(UniformedRequestModel $model): TypedArray
    {
        $findingApiModel = $this->createFindingApiRequestModel($model);


    }

    private function createFindingApiRequestModel(UniformedRequestModel $model): FindingApiRequestModelInterface
    {

        $itemFilters = $this->findingApiItemFilterConverter
            ->initializeWithModel($model)
            ->attach(new LowestPriceObserver())
            ->attach(new QualityObserver())
            ->attach(new PriceRangeObserver())
            ->notify();

        $model = new FindingApiModel(
            OperationType::fromKey('findItemsAdvanced'),
            $itemFilters
        );
    }
}