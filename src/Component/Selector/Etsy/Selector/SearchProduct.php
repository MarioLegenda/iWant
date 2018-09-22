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
     * @var array $shippingInformation
     */
    private $shippingInformation;
    /**
     * @var array $image
     */
    private $image;
    /**
     * SearchProduct constructor.
     * @param EtsyApiResponseModelInterface $model
     * @param array $shippingInformation
     * @param ApplicationShop $applicationShop
     * @param array $image
     */
    public function __construct(
        EtsyApiResponseModelInterface $model,
        array $shippingInformation,
        ApplicationShop $applicationShop,
        array $image
    ) {
        $this->applicationShop = $applicationShop;
        $this->responseModels = $model;
        $this->shippingInformation = $shippingInformation;
        $this->image = $image;
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
     * @return array
     */
    public function getShippingInformation(): array
    {
        return $this->shippingInformation;
    }
    /**
     * @return array
     */
    public function getImage(): array
    {
        return $this->image;
    }
}