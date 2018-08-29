<?php

namespace App\Web\Factory;

use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Response\ResponseItem\Result;
use App\Library\Information\ShopType;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Util;
use App\Web\Model\Response\DeferrableHttpDataObject;
use App\Web\Model\Response\DeferrableHttpDataObjectInterface;
use App\Web\Model\Response\UniformedResponseModel;

class EtsyResponseModelFactory implements ModelFactoryInterface
{
    /**
     * @param EtsyApiResponseModelInterface $model
     * @return TypedArray
     */
    public function createModels($model): TypedArray
    {
        $models = TypedArray::create('integer', UniformedResponseModel::class);

        $items = Util::createGenerator($model->getResults());

        foreach ($items as $item) {
            $entry = $item['item'];

            $models[] = $this->createModel($model, $entry);
        }

        return $models;
    }
    /**
     * @param EtsyApiResponseModelInterface $model
     * @param Result $item
     * @return UniformedResponseModel
     */
    private function createModel(
        EtsyApiResponseModelInterface $model,
        Result $item
    ): UniformedResponseModel {
        $this->validate($model);

        return new UniformedResponseModel(
            ShopType::fromKey('etsy'),
            (string) $item->getListingId(),
            $item->getTitle(),
            $item->getDescription(),
            $item->getPrice(),
            $this->createShippingInfo($model, $item),
            $this->createSellerInfo($model, $item),
            $this->createImageGallery($model, $item),
            $item->getUrl(),
            false
        );
    }
    /**
     * @param EtsyApiResponseModelInterface $model
     * @param Result $item
     * @return DeferrableHttpDataObjectInterface
     */
    private function createImageGallery(
        EtsyApiResponseModelInterface $model,
        Result $item
    ): DeferrableHttpDataObjectInterface {
        return new DeferrableHttpDataObject([
            'listingId' => (string) $item->getListingId(),
        ]);
    }
    /**
     * @param EtsyApiResponseModelInterface $model
     * @param Result $item
     * @return DeferrableHttpDataObjectInterface
     */
    private function createSellerInfo(
        EtsyApiResponseModelInterface $model,
        Result $item
    ): DeferrableHttpDataObjectInterface {
        return new DeferrableHttpDataObject([
            'userId' => (string) $item->getUserId(),
        ]);
    }
    /**
     * @param EtsyApiResponseModelInterface $model
     * @param Result $item
     * @return DeferrableHttpDataObjectInterface
     */
    private function createShippingInfo(
        EtsyApiResponseModelInterface $model,
        Result $item
    ): DeferrableHttpDataObjectInterface {
        return new DeferrableHttpDataObject([
            'listingId' => (string) $item->getListingId()
        ]);
    }
    /**
     * @param EtsyApiResponseModelInterface $model
     */
    private function validate($model)
    {
        if (!$model instanceof EtsyApiResponseModelInterface) {
            $message = sprintf(
                '%s::createModel() has to receive an instance of %s',
                get_class($this),
                EtsyApiResponseModelInterface::class
            );

            throw new \RuntimeException($message);
        }
    }
}