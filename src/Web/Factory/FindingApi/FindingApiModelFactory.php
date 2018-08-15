<?php

namespace App\Web\Factory\FindingApi;

use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Library\Information\ShopType;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Util;
use App\Web\Factory\ModelFactoryInterface;
use App\Web\Model\Response\DeferrableHttpDataObject;
use App\Web\Model\Response\DeferrableHttpDataObjectInterface;
use App\Web\Model\Response\ImageGallery;
use App\Web\Model\Response\SellerInfo;
use App\Web\Model\Response\ShippingInfo;
use App\Web\Model\Response\UniformedResponseModel;

class FindingApiModelFactory implements ModelFactoryInterface
{
    /**
     * @param FindingApiResponseModelInterface $model
     * @return TypedArray
     */
    public function createModels($model): TypedArray
    {
        $this->validate($model);

        $models = TypedArray::create('integer', UniformedResponseModel::class);

        $items = Util::createGenerator($model->getSearchResults());

        /** @var Item $item */
        foreach ($items as $item) {
            $entry = $item['item'];

            $models[] = $this->createModel($model, $entry);
        }

        return $models;
    }
    /**
     * @param FindingApiResponseModelInterface $model
     * @param Item
     * @return UniformedResponseModel
     */
    private function createModel(
        FindingApiResponseModelInterface $model,
        Item $item
    ): UniformedResponseModel {
        return new UniformedResponseModel(
            ShopType::fromKey('ebay'),
            (string) $item->getItemId(),
            $item->getTitle(),
            $item->getSubtitle(''),
            $item->getSellingStatus()->getCurrentPrice()['currentPrice'],
            $this->createShippingInfo($model, $item),
            $this->createSellerInfo($model, $item),
            $this->createImageGallery($model, $item),
            $item->getViewItemUrl(),
            false
        );
    }
    /**
     * @param FindingApiResponseModelInterface $model
     * @param Item $item
     * @return DeferrableHttpDataObjectInterface
     */
    private function createImageGallery(
        FindingApiResponseModelInterface $model,
        Item $item
    ): DeferrableHttpDataObjectInterface {
        return new ImageGallery($item->getGalleryUrl());
    }
    /**
     * @param FindingApiResponseModelInterface $model
     * @param Item $item
     * @return SellerInfo|DeferrableHttpDataObjectInterface
     */
    private function createSellerInfo(
        FindingApiResponseModelInterface $model,
        Item $item
    ): DeferrableHttpDataObjectInterface {
        return new SellerInfo('No seller specified');
    }
    /**
     * @param FindingApiResponseModelInterface $model
     * @param Item $item
     * @return ShippingInfo|DeferrableHttpDataObjectInterface
     */
    private function createShippingInfo(
        FindingApiResponseModelInterface $model,
        Item $item
    ): DeferrableHttpDataObjectInterface {
        return new ShippingInfo(
            $item->getShippingInfo()->getShippingServiceCost()['amount'],
            $item->getShippingInfo()->getShipToLocations()
        );
    }
    /**
     * @param FindingApiResponseModelInterface $model
     */
    private function validate($model)
    {
        if (!$model instanceof FindingApiResponseModelInterface) {
            $message = sprintf(
                '%s::createModel() has to receive an instance of %s',
                get_class($this),
                FindingApiResponseModelInterface::class
            );

            throw new \RuntimeException($message);
        }
    }
}