<?php

namespace App\Web\Controller;

use App\Bonanza\Presentation\BonanzaApiEntryPoint;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
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
     * UniformedRequestController constructor.
     * @param EtsyApiEntryPoint $etsyApiEntryPoint
     * @param FindingApiEntryPoint $findingApiEntryPoint
     * @param BonanzaApiEntryPoint $bonanzaApiEntryPoint
     */
    public function __construct(
        EtsyApiEntryPoint $etsyApiEntryPoint,
        FindingApiEntryPoint $findingApiEntryPoint,
        BonanzaApiEntryPoint $bonanzaApiEntryPoint
    ) {
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

        return new JsonResponse();
    }
}