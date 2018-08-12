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
     * UniformedRequestModel constructor.
     * @param EtsyApiModel $etsyApiModel
     * @param EbayModels $ebayModels
     * @param BonanzaApiModel $bonanzaApiModel
     */
    public function __construct(
        EtsyApiModel $etsyApiModel,
        EbayModels $ebayModels,
        BonanzaApiModel $bonanzaApiModel
    ) {
        $this->etsyModel = $etsyApiModel;
        $this->ebayModels = $ebayModels;
        $this->bonanzaModel = $bonanzaApiModel;
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
}