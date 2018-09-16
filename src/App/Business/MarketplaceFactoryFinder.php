<?php

namespace App\App\Business;

use App\Doctrine\Entity\SingleProductItem;
use App\Doctrine\Repository\SingleProductItemRepository;
use App\Ebay\Business\Request\StaticRequestConstructor;
use App\Ebay\Library\Response\ShoppingApi\GetSingleItemResponse;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Presentation\Model\Query;
use App\Ebay\Presentation\ShoppingApi\EntryPoint\ShoppingApiEntryPoint;
use App\Ebay\Presentation\ShoppingApi\Model\GetSingleItem;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Representation\MarketplaceRepresentation;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;

class MarketplaceFactoryFinder
{
    /**
     * @var ShoppingApiEntryPoint $shoppingApiEntryPoint
     */
    private $shoppingApiEntryPoint;
    /**
     * @var EtsyApiEntryPoint $etsyApiEntryPoint
     */
    private $etsyApiEntryPoint;
    /**
     * @var MarketplaceRepresentation $marketplaceRepresentation
     */
    private $marketplaceRepresentation;
    /**
     * @var SingleProductItemRepository $singleProductItemRepository
     */
    private $singleProductItemRepository;
    /**
     * MarketplaceFactoryFinder constructor.
     * @param ShoppingApiEntryPoint $shoppingApiEntryPoint
     * @param EtsyApiEntryPoint $etsyApiEntryPoint
     * @param MarketplaceRepresentation $marketplaceRepresentation
     * @param SingleProductItemRepository $singleProductItemRepository
     */
    public function __construct(
        MarketplaceRepresentation $marketplaceRepresentation,
        ShoppingApiEntryPoint $shoppingApiEntryPoint,
        EtsyApiEntryPoint $etsyApiEntryPoint,
        SingleProductItemRepository $singleProductItemRepository
    ) {
        $this->marketplaceRepresentation = $marketplaceRepresentation;
        $this->shoppingApiEntryPoint = $shoppingApiEntryPoint;
        $this->etsyApiEntryPoint = $etsyApiEntryPoint;
        $this->singleProductItemRepository = $singleProductItemRepository;
    }

    public function getSingleItem(
        MarketplaceType $marketplace,
        string $itemId
    ): SingleProductItem {
        $existingItem = $this->singleProductItemRepository->getSingleItemByMarketplaceAndItemId(
            $marketplace,
            $itemId
        );

        if ($existingItem instanceof SingleProductItem) {
            return $existingItem;
        }

        if ($this->marketplaceRepresentation->ebay->equals($marketplace)) {
            $model = StaticRequestConstructor::createSingleItemRequest($itemId);

            /** @var GetSingleItemResponse $singleResponseModel */
            $singleResponseModel = $this->shoppingApiEntryPoint->getSingleItem($model);

            $singleProductEntity = $this->createEntityFromEbayModel($marketplace, $singleResponseModel);

            $this->singleProductItemRepository->persistAndFlush($singleProductEntity);

            return $singleProductEntity;
        } else if ($this->marketplaceRepresentation->etsy->equals($marketplace)) {

        }

        $message = sprintf(
            'Product from marketplace %s with item id %s not found',
            (string) $marketplace,
            $itemId
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param MarketplaceType $marketplace
     * @param GetSingleItemResponse $response
     * @return SingleProductItem
     */
    private function createEntityFromEbayModel(
        MarketplaceType $marketplace,
        GetSingleItemResponse $response
    ): SingleProductItem {
        $singleProduct = $response->getSingleItem();
        return new SingleProductItem(
            $singleProduct->getItemId(),
            $marketplace,
            $singleProduct->getTitle(),
            $singleProduct->getDescription()
        );
    }
}