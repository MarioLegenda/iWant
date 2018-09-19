<?php

namespace App\Component\Selector\Ebay\Selector;

use App\Doctrine\Entity\ApplicationShop;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;

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
     * @param FindingApiResponseModelInterface $model
     * @param ApplicationShop $applicationShop
     */
    public function __construct(
        FindingApiResponseModelInterface $model,
        ApplicationShop $applicationShop
    ) {
        $this->applicationShop = $applicationShop;
        $this->responseModels = $model;
    }
    /**
     * @return XmlFindingApiResponseModel
     */
    public function getResponseModels(): XmlFindingApiResponseModel
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