<?php

namespace App\Web\Controller;

use App\Bonanza\Presentation\BonanzaApiEntryPoint;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Web\Factory\BonanzaModelFactory;
use App\Web\Factory\EtsyModelFactory;
use App\Web\Factory\FindingApi\FindingApiModelFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Web\Model\Request\UniformedRequestModel;

class UniformedRequestController
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
     * @return Response
     */
    public function search(UniformedRequestModel $model): Response
    {
        $etsyResponse = $this->etsyEntryPoint->search($model->getEtsyModel());
        $findingApiResponse = $this->findingApiEntryPoint->findItemsByKeywords($model->getEbayModels()->getFindingApiModel());
        $bonanzaResponse = $this->bonanzaEntryPoint->search($model->getBonanzaModel());

        $etsyUniformedResponse = $this->etsyModelFactory->createModels($etsyResponse);
        $findinApiUniformedResponse = $this->findingApiModelFactory->createModels($findingApiResponse);
        $bonanzaUniformedResponse = $this->bonanzaModelFactory->createModels($bonanzaResponse);

        return new JsonResponse();
    }
}