<?php

namespace App\Web\Model\Request;

use App\Bonanza\Presentation\Model\BonanzaApiModel;
use App\Etsy\Presentation\Model\EtsyApiModel;

class UniformedRequestModel
{
    /**
     * @var EtsyApiModel $etsyModel
     */
    private $etsyModel;
    /**
     * @var EbayModels $ebayModels
     */
    private $ebayModels;
    /**
     * @var BonanzaApiModel $bonanzaModel
     */
    private $bonanzaModel;
    /**
     * @var string $groupBy
     */
    private $groupBy;
    /**
     * UniformedRequestModel constructor.
     * @param EtsyApiModel $etsyApiModel
     * @param EbayModels $ebayModels
     * @param BonanzaApiModel $bonanzaApiModel
     * @param string $groupingType
     */
    public function __construct(
        EtsyApiModel $etsyApiModel,
        EbayModels $ebayModels,
        BonanzaApiModel $bonanzaApiModel,
        string $groupingType
    ) {
        $this->etsyModel = $etsyApiModel;
        $this->ebayModels = $ebayModels;
        $this->bonanzaModel = $bonanzaApiModel;
        $this->groupBy = $groupingType;
    }
    /**
     * @return EtsyApiModel
     */
    public function getEtsyModel(): EtsyApiModel
    {
        return $this->etsyModel;
    }
    /**
     * @return EbayModels
     */
    public function getEbayModels(): EbayModels
    {
        return $this->ebayModels;
    }
    /**
     * @return BonanzaApiModel
     */
    public function getBonanzaModel(): BonanzaApiModel
    {
        return $this->bonanzaModel;
    }
    /**
     * @return string
     */
    public function getGroupBy(): string
    {
        return $this->groupBy;
    }
}