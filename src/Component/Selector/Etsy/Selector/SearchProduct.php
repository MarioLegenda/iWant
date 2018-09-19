<?php

namespace App\Component\Selector\Etsy\Selector;

use App\Doctrine\Entity\ApplicationShop;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;

class SearchProduct
{
    /**
     * @var XmlFindingApiResponseModel $responseModels
     */
    private $responseModels;
    /**
     * @var ApplicationShop $applicationShop
     */
    private $applicationShop;
    /**
     * SearchProduct constructor.
     * @param EtsyApiResponseModelInterface $model
     * @param ApplicationShop $applicationShop
     */
    public function __construct(
        EtsyApiResponseModelInterface $model,
        ApplicationShop $applicationShop
    ) {
        $this->applicationShop = $applicationShop;
        $this->responseModels = $model;
    }
    /**
     * @return EtsyApiResponseModelInterface
     */
    public function getResponseModels(): EtsyApiResponseModelInterface
    {
        return $this->responseModels;
    }
    /**
     * @return ApplicationShop
     */
    public function getApplicationShop(): ApplicationShop
    {
        return $this->applicationShop;
    }
}