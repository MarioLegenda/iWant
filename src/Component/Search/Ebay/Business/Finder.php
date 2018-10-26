<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Business\Factory\PresentationModelFactory;
use App\Component\Search\Ebay\Model\Request\SearchRequestModel;
use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;
use App\Component\Search\Ebay\Business\Factory\EbayModelFactory;
use App\Component\Search\Ebay\Model\Response\EbaySiteSearchResponseModel;
use App\Component\Search\Ebay\Model\Response\SearchResponseModel;
use App\Doctrine\Entity\Country;
use App\Doctrine\Repository\CountryRepository;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Library\Information\WorldwideShipping;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Representation\ApplicationShopRepresentation;
use App\Library\Util\Environment;
use App\Library\Util\SlackImplementation;
use App\Library\Util\Util;

class Finder
{
    /**
     * @var Environment $environment
     */
    private $environment;
    /**
     * @var SlackImplementation $slackImplementation
     */
    private $slackImplementation;
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
     * @param Environment $environment
     * @param SlackImplementation $slackImplementation
     * @param FindingApiEntryPoint $findingApiEntryPoint
     * @param EbayModelFactory $ebayModelFactory
     * @param ApplicationShopRepresentation $applicationShopRepresentation
     * @param PresentationModelFactory $presentationModelFactory
     * @param CountryRepository $countryRepository
     */
    public function __construct(
        Environment $environment,
        SlackImplementation $slackImplementation,
        FindingApiEntryPoint $findingApiEntryPoint,
        EbayModelFactory $ebayModelFactory,
        ApplicationShopRepresentation $applicationShopRepresentation,
        PresentationModelFactory $presentationModelFactory,
        CountryRepository $countryRepository
    ) {
        $this->environment = $environment;
        $this->slackImplementation = $slackImplementation;
        $this->findingApiEntryPoint = $findingApiEntryPoint;
        $this->ebayModelFactory = $ebayModelFactory;
        $this->applicationShopRepresentation = $applicationShopRepresentation;
        $this->presentationModelFactory = $presentationModelFactory;
        $this->countryRepository = $countryRepository;
    }

    public function findEbayProductsAdvanced(EbaySearchModel $model): ResponseModelInterface
    {
        /** @var FindingApiModel $findingApiModel */
        $findingApiModel = $this->ebayModelFactory->createRequestModel($model);

        return $this->findingApiEntryPoint->findItemsAdvanced($findingApiModel);
    }
    /**
     * @param EbaySearchModel $model
     * @return iterable
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Http\Client\Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findEbayProductsInEbayStores(EbaySearchModel $model): iterable
    {
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