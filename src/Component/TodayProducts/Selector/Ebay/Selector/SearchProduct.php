<?php

namespace App\Component\TodayProducts\Selector\Ebay\Selector;

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
     * @var array $shippingInformation
     */
    private $shippingInformation;
    /**
     * SearchProduct constructor.
     * @param FindingApiResponseModelInterface $model
     * @param ApplicationShop $applicationShop
     * @param array $shippingInformation
     */
    public function __construct(
        FindingApiResponseModelInterface $model,
        ApplicationShop $applicationShop,
        array $shippingInformation
    ) {
        $this->applicationShop = $applicationShop;
        $this->responseModels = $model;
        $this->shippingInformation = $shippingInformation;
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
    /**
     * @return array
     */
    public function getShippingInformation(): array
    {
        return $this->shippingInformation;
    }
}