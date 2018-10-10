<?php

namespace App\Component\Search\Ebay\Business;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Factory\PresentationModelFactory;
use App\Component\Search\Ebay\Model\Request\SearchRequestModel;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Business\Factory\EbayModelFactory;
use App\Doctrine\Entity\Country;
use App\Doctrine\Repository\CountryRepository;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Library\Information\WorldwideShipping;
use App\Library\Representation\ApplicationShopRepresentation;
use App\Library\Util\Util;

class Finder
{
    /**
     * @var FindingApiEntryPoint $findingApiEntryPoint
     */
    private $findingApiEntryPoint;
    /**
     * @var EbayModelFactory $ebayModelFactory
     */
    private $ebayModelFactory;
    /**
     * @var ApplicationShopRepresentation $applicationShopRepresentation
     */
    private $applicationShopRepresentation;
    /**
     * @var PresentationModelFactory $presentationModelFactory
     */
    private $presentationModelFactory;
    /**
     * @var CountryRepository $countryRepository
     */
    private $countryRepository;
    /**
     * Finder constructor.
     * @param FindingApiEntryPoint $findingApiEntryPoint
     * @param EbayModelFactory $ebayModelFactory
     * @param ApplicationShopRepresentation $applicationShopRepresentation
     * @param PresentationModelFactory $presentationModelFactory
     * @param CountryRepository $countryRepository
     */
    public function __construct(
        FindingApiEntryPoint $findingApiEntryPoint,
        EbayModelFactory $ebayModelFactory,
        ApplicationShopRepresentation $applicationShopRepresentation,
        PresentationModelFactory $presentationModelFactory,
        CountryRepository $countryRepository
    ) {
        $this->findingApiEntryPoint = $findingApiEntryPoint;
        $this->ebayModelFactory = $ebayModelFactory;
        $this->applicationShopRepresentation = $applicationShopRepresentation;
        $this->presentationModelFactory = $presentationModelFactory;
        $this->countryRepository = $countryRepository;
    }
    /**
     * @param SearchModel $model
     * @return iterable
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findEbayProducts(SearchModel $model): iterable
    {
        $responses = $this->getResponses($model);

        $responsesGen = Util::createGenerator($responses);

        $searchResponseModels = [];
        foreach ($responsesGen as $entry) {
            /** @var FindingApiResponseModelInterface $item */
            $item = $entry['item']['response'];
            $globalId = $entry['key'];
            $globalIdInformation = $entry['item']['globalIdInformation'];
            $searchResults = $item->getSearchResults();

            if (!empty($searchResults)) {
                $searchResultsGen = Util::createGenerator($item->getSearchResults());

                $temp = [];
                foreach ($searchResultsGen as $searchResultEntry) {
                    /** @var Item $item */
                    $item = $searchResultEntry['item'];
                    $storeName = $item->getStoreInfo()->getStoreName();
                    $applicationShop = $this->applicationShopRepresentation->getShop('applicationName', $storeName);

                    $searchResultResponseModel = $this->presentationModelFactory->createModel(
                        $item,
                        $applicationShop,
                        $this->createShippingLocations($item)
                    );

                    if (!array_key_exists('globalId', $temp)) {
                        $temp['globalId'] = $globalId;
                        $temp['globalIdInformation'] = $globalIdInformation;
                    }

                    $temp['items'][] = $searchResultResponseModel;
                }

                $searchResponseModels[] = $temp;
            }
        }

        return $searchResponseModels;
    }
    /**
     * @param SearchModel $model
     * @return iterable
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getResponses(SearchModel $model): iterable
    {
        /** @var SearchRequestModel[] $requestModels */
        $requestModels = $this->ebayModelFactory->createRequestModels($model);

        $responses = [];
        /** @var SearchRequestModel $requestModel */
        foreach ($requestModels as $requestModel) {
            $response = $this->findingApiEntryPoint
                ->findItemsInEbayStores($requestModel->getEntryPointModel());

            $globalId = $requestModel->getMetadata()->getGlobalId();
            $globalIdInformation = GlobalIdInformation::instance()->getTotalInformation($globalId);

            $responses[$requestModel->getMetadata()->getGlobalId()] = [
                'globalIdInformation' => $globalIdInformation,
                'response' => $response,
            ];
        }

        return $responses;
    }
    /**
     * @param Item $singleItem
     * @return array
     */
    public function createShippingLocations(Item $singleItem)
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