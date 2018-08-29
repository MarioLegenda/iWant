<?php

namespace App\Web\Factory;

use App\Bonanza\Library\Response\BonanzaApiResponseModelInterface;
use App\Bonanza\Library\Response\ResponseItem\Item;
use App\Library\Information\ShopType;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Util;
use App\Web\Model\Response\ImageGallery;
use App\Web\Model\Response\SellerInfo;
use App\Web\Model\Response\ShippingInfo;
use App\Web\Model\Response\UniformedResponseModel;

class BonanzaResponseModelFactory implements ModelFactoryInterface
{
    /**
     * @param BonanzaApiResponseModelInterface $model
     * @return TypedArray
     */
    public function createModels($model): TypedArray
    {
        $this->validate($model);

        $models = TypedArray::create('integer', UniformedResponseModel::class);

        $items = Util::createGenerator($model->getResponseByResponseType()->getItems());

        /** @var Item $item */
        foreach ($items as $item) {
            $entry = $item['item'];
            $models[] = $this->createModel($model, $entry);
        }

        return $models;
    }
    /**
     * @param BonanzaApiResponseModelInterface $model
     * @param Item $singleItem
     * @return UniformedResponseModel
     */
    private function createModel(
        BonanzaApiResponseModelInterface $model,
        Item $singleItem
    ): UniformedResponseModel {
        $itemId = $singleItem->getItemId();
        $title = $singleItem->getTitle();
        $description = $singleItem->getDescription();
        $price = to_float($singleItem->getListingInfo()->getPrice());
        $shippingInfo = $this->createShippingInfo($model, $singleItem);
        $sellerInfo = $this->createSellerInfo($model, $singleItem);
        $imageGallery = $this->createImageGallery($model, $singleItem);
        $viewItemUrl = $singleItem->getViewItemUrl();
        $availableInYourCountry = true;

        return new UniformedResponseModel(
            ShopType::fromKey('bonanza'),
            $itemId,
            $title,
            $description,
            $price,
            $shippingInfo,
            $sellerInfo,
            $imageGallery,
            $viewItemUrl,
            $availableInYourCountry
        );
    }
    /**
     * @param BonanzaApiResponseModelInterface $model
     * @param Item $singleItem
     * @return SellerInfo
     */
    private function createSellerInfo(
        BonanzaApiResponseModelInterface $model,
        Item $singleItem
    ): SellerInfo {
        return new SellerInfo(
            $singleItem->getSellerInfo()->getSellerUserName()
        );
    }

    public function createShippingInfo(
        BonanzaApiResponseModelInterface $model,
        Item $singleItem
    ): ShippingInfo {
        return new ShippingInfo(
            $singleItem->getShippingInfo()->getShippingServiceCost(),
            $singleItem->getShippingInfo()->getShipToLocations()
        );
    }
    /**
     * @param BonanzaApiResponseModelInterface $model
     * @param Item $singleItem
     * @return ImageGallery
     */
    private function createImageGallery(
        BonanzaApiResponseModelInterface $model,
        Item $singleItem
    ): ImageGallery {
        return new ImageGallery($singleItem->getGalleryUrl());
    }
    /**
     * @param BonanzaApiResponseModelInterface $model
     */
    private function validate($model)
    {
        if (!$model instanceof BonanzaApiResponseModelInterface) {
            $message = sprintf(
                '%s::createModel() has to receive an instance of %s',
                get_class($this),
                BonanzaApiResponseModelInterface::class
            );

            throw new \RuntimeException($message);
        }
    }
}