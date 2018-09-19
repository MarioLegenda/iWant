<?php

namespace App\Component\Selector\Etsy\Selector;

use App\Doctrine\Entity\ApplicationShop;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Response\ShippingProfileEntriesResponseModel;

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
     * @var ShippingProfileEntriesResponseModel $shippingProfileEntriesResponseModel
     */
    private $shippingProfileEntriesResponseModel;
    /**
     * SearchProduct constructor.
     * @param EtsyApiResponseModelInterface $model
     * @param ShippingProfileEntriesResponseModel $shippingProfileEntriesResponseModel
     * @param ApplicationShop $applicationShop
     */
    public function __construct(
        EtsyApiResponseModelInterface $model,
        ShippingProfileEntriesResponseModel $shippingProfileEntriesResponseModel,
        ApplicationShop $applicationShop
    ) {
        $this->applicationShop = $applicationShop;
        $this->responseModels = $model;
        $this->shippingProfileEntriesResponseModel = $shippingProfileEntriesResponseModel;
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
    /**
     * @return ShippingProfileEntriesResponseModel
     */
    public function getShippingInfo(): ShippingProfileEntriesResponseModel
    {
        return $this->shippingProfileEntriesResponseModel;
    }
}