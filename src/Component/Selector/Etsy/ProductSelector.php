<?php

namespace App\Component\Selector\Etsy;

use App\Component\Selector\Etsy\Selector\SearchProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Repository\CountryRepository;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Response\ResponseItem\Country;
use App\Etsy\Library\Response\ShippingProfileEntriesResponseModel;
use App\Etsy\Library\Type\MethodType;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Etsy\Presentation\Model\ItemFilterModel;
use App\Etsy\Presentation\Model\Query;
use App\Library\Information\WorldwideShipping;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Util;
use App\Doctrine\Entity\Country as InternalCountry;

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
     * @var CountryRepository $countryRepository
     */
    private $countryRepository;
    /**
     * ProductSelector constructor.
     * @param EtsyApiEntryPoint $etsyApiEntryPoint
     * @param CountryRepository $countryRepository
     */
    public function __construct(
        CountryRepository $countryRepository,
        EtsyApiEntryPoint $etsyApiEntryPoint
    ) {
        $this->etsyApiEntryPoint = $etsyApiEntryPoint;
        $this->countryRepository = $countryRepository;
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

                        $this->searchProducts[] = new SearchProduct(
                            $responseModel,
                            $this->getShippingInformation($listingId),
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
    /**
     * @param string $countryId
     * @return EtsyApiModel
     */
    private function createCountryModel(string $countryId): EtsyApiModel
    {
        $methodType = MethodType::fromKey('getCountry');

        $queries = TypedArray::create('integer', Query::class);

        $listingIdQuery = new Query(sprintf('/countries/%s?', $countryId));

        $queries[] = $listingIdQuery;

        $itemFilters = TypedArray::create('integer', ItemFilterModel::class);

        $model = new EtsyApiModel(
            $methodType,
            $itemFilters,
            $queries
        );

        return $model;
    }

    private function getShippingInformation(string $listingId): array
    {
        $shippingInfoModel = $this->createShippingInfoModel($listingId);

        /** @var ShippingProfileEntriesResponseModel $shippingInfo */
        $shippingInfo = $this->etsyApiEntryPoint->findAllListingShippingProfileEntries($shippingInfoModel);

        $shippingInfoGen = Util::createGenerator($shippingInfo->getResults()->toArray());

        foreach ($shippingInfoGen as $entry) {
            $item = $entry['item'];

            if ($item['destinationCountryName'] === 'Everywhere Else') {
                return [(string) WorldwideShipping::fromValue()];
            }
        }

        $shippingInfoGen = Util::createGenerator($shippingInfo->getResults()->toArray());
        $countries = [];

        $etsyCountryNames = [];
        foreach ($shippingInfoGen as $entry) {
            $item = $entry['item'];

            $countryId = $item['originCountryId'];

            /** @var Country $etsyCountry */
            $etsyCountry = $this->etsyApiEntryPoint->findCountryByCountryId($this->createCountryModel($countryId))->getResults()[0];

            if (in_array($etsyCountry->getName(), $etsyCountryNames)) {
                continue;
            }

            $etsyCountryNames[] = $etsyCountry->getName();

            /** @var InternalCountry $internalCountry */
            $internalCountry = $this->countryRepository->findOneBy([
                'alpha2Code' => $etsyCountry->getIsoCountryCode(),
            ]);

            $countries[] = $internalCountry->toArray();
        }

        if (empty($countries)) {
            $message = sprintf(
                'Etsy shipping countries are not populated. This is an internal error and should be corrected in %s',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return $countries;
    }
}