<?php

namespace App\Web;

use App\Bonanza\Presentation\BonanzaApiEntryPoint;
use App\Ebay\Library\Model\PresentationModelInterface;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Web\Factory\BonanzaModelFactory;
use App\Web\Factory\EtsyModelFactory;
use App\Web\Factory\FindingApi\FindingApiModelFactory;
use App\Web\Library\Grouping\Grouping;
use App\Web\Library\Grouping\Type\GroupTypes;
use App\Web\Model\Request\UniformedRequestModel;

class UniformedEntryPoint
{
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
     * @var EtsyModelFactory $etsyModelFactory
     */
    private $etsyModelFactory;
    /**
     * @var FindingApiModelFactory $findingApiModelFactory
     */
    private $findingApiModelFactory;
    /**
     * @var BonanzaModelFactory $bonanzaModelFactory
     */
    private $bonanzaModelFactory;
    /**
     * UniformedRequestController constructor.
     * @param EtsyApiEntryPoint $etsyApiEntryPoint
     * @param FindingApiEntryPoint $findingApiEntryPoint
     * @param BonanzaApiEntryPoint $bonanzaApiEntryPoint
     * @param EtsyModelFactory $etsyModelFactory
     * @param FindingApiModelFactory $findingApiModelFactory
     * @param BonanzaModelFactory $bonanzaModelFactory
     */
    public function __construct(
        EtsyApiEntryPoint $etsyApiEntryPoint,
        FindingApiEntryPoint $findingApiEntryPoint,
        BonanzaApiEntryPoint $bonanzaApiEntryPoint,
        EtsyModelFactory $etsyModelFactory,
        FindingApiModelFactory $findingApiModelFactory,
        BonanzaModelFactory $bonanzaModelFactory
    ) {
        $this->etsyModelFactory = $etsyModelFactory;
        $this->findingApiModelFactory = $findingApiModelFactory;
        $this->bonanzaModelFactory = $bonanzaModelFactory;

        $this->etsyEntryPoint = $etsyApiEntryPoint;
        $this->findingApiEntryPoint = $findingApiEntryPoint;
        $this->bonanzaEntryPoint = $bonanzaApiEntryPoint;
    }
    /**
     * @param UniformedRequestModel $model
     * @return TypedArray
     */
    public function getPresentationModels(UniformedRequestModel $model): TypedArray
    {
        $etsyResponse = $this->etsyEntryPoint->search($model->getEtsyModel());
        $findingApiResponse = $this->findingApiEntryPoint->findItemsByKeywords($model->getEbayModels()->getFindingApiModel());
        $bonanzaResponse = $this->bonanzaEntryPoint->search($model->getBonanzaModel());

        $etsyUniformedResponse = $this->etsyModelFactory->createModels($etsyResponse);
        $findingApiUniformedResponse = $this->findingApiModelFactory->createModels($findingApiResponse);
        $bonanzaUniformedResponse = $this->bonanzaModelFactory->createModels($bonanzaResponse);

        $combinedModels = TypedArray::create(
            'integer',
            PresentationModelInterface::class,
            array_merge(
                $etsyUniformedResponse->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION),
                $findingApiUniformedResponse->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION),
                $bonanzaUniformedResponse->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION)
            )
        );

        $groupByType = GroupTypes::getGroupTypes()[$model->getGroupBy()]::fromValue();

        return Grouping::inst()->groupBy(
            $groupByType,
            $combinedModels
        );
    }
}