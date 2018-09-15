<?php

namespace App\App\Business;

use App\App\Library\Response\MarketplaceFactoryResponse;
use App\App\Presentation\Model\SingleItemRequestModel;

class Finder
{
    /**
     * @var MarketplaceFactoryFinder $marketplaceFactoryFinder
     */
    private $marketplaceFactoryFinder;
    /**
     * @var MarketplaceFactoryResponse $marketplaceFactoryResponse
     */
    private $marketplaceFactoryResponse;
    /**
     * Finder constructor.
     * @param MarketplaceFactoryFinder $marketplaceFactoryFinder
     * @param MarketplaceFactoryResponse $marketplaceFactoryResponse
     */
    public function __construct(
        MarketplaceFactoryFinder $marketplaceFactoryFinder,
        MarketplaceFactoryResponse $marketplaceFactoryResponse
    ) {
        $this->marketplaceFactoryFinder = $marketplaceFactoryFinder;
        $this->marketplaceFactoryResponse = $marketplaceFactoryResponse;
    }

    public function getSingleItem(SingleItemRequestModel $model)
    {
        $marketplace = $model->getMarketplace();

        $singleItem = $this->marketplaceFactoryFinder
            ->getSingleItem($model->getMarketplace(), $model->getItemId());

        return $this->marketplaceFactoryResponse->createSingleItemResponse(
            $model->getMarketplace(), $singleItem
        );
    }


}