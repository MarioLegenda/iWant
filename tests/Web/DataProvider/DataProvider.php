<?php

namespace App\Tests\Web\DataProvider;

use App\Tests\Etsy\DataProvider\DataProvider as EtsyDataProvider;
use App\Tests\Bonanza\DataProvider\DataProvider as BonanzaDataProvider;
use App\Tests\Ebay\FindingApi\DataProvider\DataProvider as EbayDataProvider;
use App\Web\Model\Request\EbayModels;
use App\Web\Model\Request\UniformedRequestModel;

class DataProvider
{
    /**
     * @var EtsyDataProvider $etsyDataProvider
     */
    private $etsyDataProvider;
    /**
     * @var EbayDataProvider $ebayDataProvider
     */
    private $ebayDataProvider;
    /**
     * @var BonanzaDataProvider $bonanzaDataProvider
     */
    private $bonanzaDataProvider;
    /**
     * DataProvider constructor.
     * @param EtsyDataProvider $etsyDataProvider
     * @param EbayDataProvider $ebayDataProvider
     * @param BonanzaDataProvider $bonanzaDataProvider
     */
    public function __construct(
        EtsyDataProvider $etsyDataProvider,
        EbayDataProvider $ebayDataProvider,
        BonanzaDataProvider $bonanzaDataProvider
    ) {
        $this->etsyDataProvider = $etsyDataProvider;
        $this->ebayDataProvider = $ebayDataProvider;
        $this->bonanzaDataProvider = $bonanzaDataProvider;
    }
    /**
     * @return EtsyDataProvider
     */
    public function getEtsyDataProvider(): EtsyDataProvider
    {
        return $this->etsyDataProvider;
    }
    /**
     * @return EbayDataProvider
     */
    public function getEbayDataProvider(): EbayDataProvider
    {
        return $this->ebayDataProvider;
    }
    /**
     * @return BonanzaDataProvider
     */
    public function getBonanzaDataProvider(): BonanzaDataProvider
    {
        return $this->bonanzaDataProvider;
    }
    /**
     * @param string $groupBy
     * @return UniformedRequestModel
     */
    public function getUniformedRequestModel(string $groupBy): UniformedRequestModel
    {
        $etsyModel = $this->etsyDataProvider->getEtsyApiModel();
        $ebayModel = $this->ebayDataProvider->getFindItemsByKeywordsData(['boots', 'mountain']);
        $bonanzaModel = $this->bonanzaDataProvider->getFindItemsByKeywordsData(['boots', 'mountain']);

        return new UniformedRequestModel(
            $etsyModel,
            new EbayModels($ebayModel),
            $bonanzaModel,
            $groupBy
        );
    }
}