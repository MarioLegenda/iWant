<?php

namespace App\Component\TodayProducts\Selector\Ebay;

use App\Component\TodayProducts\Selector\Ebay\Selector\SearchProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Entity\Country;
use App\Doctrine\Repository\CountryRepository;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Ebay\Library\Response\FindingApi\ResponseItem\RootItem;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Library\Information\WorldwideShipping;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Util;

class ProductSelector implements SubjectSelectorInterface
{
    /**
     * @var \SplObserver[] $observers
     */
    private $observers;
    /**
     * @var SearchProduct[] $searchProducts
     */
    private $searchProducts;
    /**
     * @var FindingApiEntryPoint $findingApiEntryPoint
     */
    private $findingApiEntryPoint;
    /**
     * @var CountryRepository $countryRepository
     */
    private $countryRepository;
    /**
     * ProductSelector constructor.
     * @param FindingApiEntryPoint $findingApiEntryPoint
     * @param CountryRepository $countryRepository
     */
    public function __construct(
        CountryRepository $countryRepository,
        FindingApiEntryPoint $findingApiEntryPoint
    ) {
        $this->findingApiEntryPoint = $findingApiEntryPoint;
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
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function notify(ApplicationShop $applicationShop): void
    {

        /** @var \SplObserver|ObserverSelectorInterface $observer */
        foreach ($this->observers as $observer) {
            $model = $observer->update($this);

            /** @var FindingApiResponseModelInterface $responseModel */
            $responseModel = $this->findingApiEntryPoint->findItemsInEbayStores($model);

            /** @var RootItem $rootItem */
            $rootItem = $responseModel->getRoot();

            if ($rootItem->getSearchResultsCount() > 0) {
                $this->searchProducts[] = new SearchProduct(
                    $responseModel,
                    $applicationShop,
                    $this->getShippingInformation($responseModel->getSearchResults()[0])
                );

                $this->observers = [];

                break;
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
     * @param Item $singleItem
     * @return array
     */
    private function getShippingInformation(Item $singleItem): array
    {
        $shipsToLocations = $singleItem->getShippingInfo()->getShipToLocations();

        if (count($shipsToLocations) === 1) {
            try {
                $location = WorldwideShipping::fromValue($shipsToLocations[0]);

                return [(string) $location];
            } catch (\Exception $e) {
                $countryGen = Util::createGenerator($shipsToLocations);
                $countries = [];

                foreach ($countryGen as $entry) {
                    $alpha2Code = $entry['item'];

                    /** @var Country $country */
                    $country = $this->countryRepository->findOneBy([
                        'alpha2Code' => $alpha2Code,
                    ]);

                    $countries[] = $country->toArray();
                }

                return $countries;
            }
        }

        $message = sprintf(
            'Bad code. Unreachable code detected in %s',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
}