<?php

namespace App\Web;

use App\Bonanza\Presentation\BonanzaApiEntryPoint;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Web\Factory\BonanzaResponseModelFactory;
use App\Web\Factory\EtsyResponseModelFactory;
use App\Web\Factory\FindingApi\FindingApiPresentationModelFactory;
use App\Web\Factory\FindingApi\FindingApiResponseModelFactory;
use App\Web\Model\Request\UniformedRequestModel;
use Symfony\Component\HttpFoundation\JsonResponse;

class UniformedEntryPoint
{
    /**
     * @var FindingApiPresentationModelFactory $findingApiPresentationModelFactory
     */
    private $findingApiPresentationModelFactory;
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
     * @param FindingApiPresentationModelFactory $findingApiPresentationModelFactory
     * @param EtsyApiEntryPoint $etsyApiEntryPoint
     * @param FindingApiEntryPoint $findingApiEntryPoint
     * @param BonanzaApiEntryPoint $bonanzaApiEntryPoint
     * @param EtsyResponseModelFactory $etsyModelFactory
     * @param FindingApiResponseModelFactory $findingApiModelFactory
     * @param BonanzaResponseModelFactory $bonanzaModelFactory
     */
    public function __construct(
        FindingApiPresentationModelFactory $findingApiPresentationModelFactory,
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

        $this->findingApiPresentationModelFactory = $findingApiPresentationModelFactory;
    }
    /**
     * @param UniformedRequestModel $model
     * @return array
     */
    public function getPresentationModels(UniformedRequestModel $model): array
    {
        $findingApiModels = $this->createFindingApiResponseModels($model);

        return [
            'ebay' => $findingApiModels->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION),
        ];
    }
    /**
     * @param UniformedRequestModel $model
     * @return TypedArray
     */
    private function createFindingApiResponseModels(UniformedRequestModel $model): TypedArray
    {
        $findingApiModel = $this->findingApiPresentationModelFactory->createFromModel($model);

        /** @var FindingApiResponseModelInterface $findingApiResponseModel */
        $findingApiResponseModel = $this->findingApiEntryPoint->findItemsAdvanced($findingApiModel);

        return $this->findingApiModelFactory->createModels($findingApiResponseModel);
    }
}