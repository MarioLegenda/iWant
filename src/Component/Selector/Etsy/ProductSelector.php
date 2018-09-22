<?php

namespace App\Component\Selector\Etsy;

use App\Component\Selector\Etsy\Factory\RequestModelFactory;
use App\Component\Selector\Etsy\Selector\SearchProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Repository\CountryRepository;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Response\ResponseItem\Country;
use App\Etsy\Library\Response\ShippingProfileEntriesResponseModel;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
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
     * @var RequestModelFactory $requestModelFactory
     */
    private $requestModelFactory;
    /**
     * ProductSelector constructor.
     * @param EtsyApiEntryPoint $etsyApiEntryPoint
     * @param CountryRepository $countryRepository
     * @param RequestModelFactory $requestModelFactory
     */
    public function __construct(
        CountryRepository $countryRepository,
        RequestModelFactory $requestModelFactory,
        EtsyApiEntryPoint $etsyApiEntryPoint
    ) {
        $this->etsyApiEntryPoint = $etsyApiEntryPoint;
        $this->countryRepository = $countryRepository;
        $this->searchProducts = TypedArray::create('integer', SearchProduct::class);
        $this->requestModelFactory = $requestModelFactory;
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

                        $this->searchProducts[] = new SearchProduct(
                            $responseModel,
                            $this->getShippingInformation($listingId),
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
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getShippingInformation(string $listingId): array
    {
        $shippingInfoModel = $this->requestModelFactory->createShippingInfoModel($listingId);

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
            $etsyCountry = $this->etsyApiEntryPoint->findCountryByCountryId($this->requestModelFactory->createCountryModel($countryId))->getResults()[0];

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