<?php

namespace App\Component\Selector\Etsy;

use App\Component\Selector\Etsy\Selector\SearchProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Response\ShippingProfileEntriesResponseModel;
use App\Etsy\Library\Type\MethodType;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Etsy\Presentation\Model\ItemFilterModel;
use App\Etsy\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;

class ProductSelector implements SubjectSelectorInterface
{
    /**
     * @var TypedArray|iterable|SearchProduct[] $searchProducts
     */
    private $searchProducts;
    /**
     * @var ObserverSelectorInterface[] $observers
     */
    private $observers;
    /**
     * @var EtsyApiEntryPoint $etsyApiEntryPoint
     */
    private $etsyApiEntryPoint;
    /**
     * ProductSelector constructor.
     * @param EtsyApiEntryPoint $etsyApiEntryPoint
     */
    public function __construct(
        EtsyApiEntryPoint $etsyApiEntryPoint
    ) {
        $this->etsyApiEntryPoint = $etsyApiEntryPoint;
        $this->searchProducts = TypedArray::create('integer', SearchProduct::class);
    }
    /**
     * @param ObserverSelectorInterface $observer
     */
    public function detach(ObserverSelectorInterface $observer)
    {
        $message = sprintf(
            'Method %s::detach() is disabled',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param ObserverSelectorInterface $observer
     * @return SubjectSelectorInterface
     */
    public function attach(ObserverSelectorInterface $observer): SubjectSelectorInterface
    {
        $this->observers[] = $observer;

        return $this;
    }
    /**
     * @param ApplicationShop $applicationShop
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function notify(ApplicationShop $applicationShop): void
    {
        /** @var ObserverSelectorInterface $observer */
        foreach ($this->observers as $observer) {
            $model = $observer->update($this);

            switch ((string) $model->getMethodType()) {
                case 'FindAllShopListingsFeatured':
                    /** @var EtsyApiResponseModelInterface $responseModel */
                    $responseModel = $this->etsyApiEntryPoint->findAllShopListingsFeatured($model);

                    if ($responseModel->getCount() > 0) {
                        $listingId = (string) $responseModel->getResults()[0]->getListingId();
                        $shippingInfoModel = $this->createShippingInfoModel($listingId);

                        /** @var ShippingProfileEntriesResponseModel $shippingInfo */
                        $shippingInfo = $this->etsyApiEntryPoint->findAllListingShippingProfileEntries($shippingInfoModel);

                        $this->searchProducts[] = new SearchProduct(
                            $responseModel,
                            $shippingInfo,
                            $applicationShop
                        );
                    }

                    $this->observers = [];

                    return;
                case 'findAllListingActive':
                    /** @var EtsyApiResponseModelInterface $responseModel */
                    $responseModel = $this->etsyApiEntryPoint->findAllListingActive($model);

                    if ($responseModel->getCount() > 0) {
                        $listingId = (string) $responseModel->getResults()[0]->getListingId();
                        $shippingInfoModel = $this->createShippingInfoModel($listingId);

                        /** @var ShippingProfileEntriesResponseModel $shippingInfo */
                        $shippingInfo = $this->etsyApiEntryPoint->findAllListingShippingProfileEntries($shippingInfoModel);

                        $this->searchProducts[] = new SearchProduct(
                            $responseModel,
                            $shippingInfo,
                            $applicationShop
                        );
                    }

                    $this->observers = [];

                    return;

            }
        }
    }
    /**
     * @return iterable|TypedArray
     */
    public function getProductResponseModels(): iterable
    {
        return $this->searchProducts;
    }
    /**
     * @param string $listingId
     * @return EtsyApiModel
     */
    private function createShippingInfoModel(string $listingId): EtsyApiModel
    {
        $methodType = MethodType::fromKey('findAllListingShippingProfileEntries');

        $queries = TypedArray::create('integer', Query::class);

        $listingIdQuery = new Query(sprintf('/listings/%s/shipping/info?', $listingId));

        $queries[] = $listingIdQuery;

        $itemFilters = TypedArray::create('integer', ItemFilterModel::class);

        $model = new EtsyApiModel(
            $methodType,
            $itemFilters,
            $queries
        );

        return $model;
    }
}