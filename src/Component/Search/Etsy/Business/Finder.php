<?php

namespace App\Component\Search\Etsy\Business;

use App\Component\Search\Etsy\Business\Factory\PresentationModelFactory;
use App\Component\Search\Etsy\Model\Request\SearchModel;
use App\Component\Search\Etsy\Model\Response\SearchResponseModel;
use App\Doctrine\Repository\CountryRepository;
use App\Etsy\Library\ItemFilter\ItemFilterType;
use App\Etsy\Library\Type\MethodType;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Etsy\Presentation\Model\ItemFilterMetadata;
use App\Etsy\Presentation\Model\ItemFilterModel;
use App\Etsy\Presentation\Model\Query;
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
     * @var EtsyApiEntryPoint $etsyApiEntryPoint
     */
    private $etsyApiEntryPoint;
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
     * @param EtsyApiEntryPoint $etsyApiEntryPoint
     * @param ApplicationShopRepresentation $applicationShopRepresentation
     * @param PresentationModelFactory $presentationModelFactory
     * @param CountryRepository $countryRepository
     */
    public function __construct(
        Environment $environment,
        SlackImplementation $slackImplementation,
        EtsyApiEntryPoint $etsyApiEntryPoint,
        ApplicationShopRepresentation $applicationShopRepresentation,
        PresentationModelFactory $presentationModelFactory,
        CountryRepository $countryRepository
    ) {
        $this->etsyApiEntryPoint = $etsyApiEntryPoint;
        $this->environment = $environment;
        $this->slackImplementation = $slackImplementation;
        $this->applicationShopRepresentation = $applicationShopRepresentation;
        $this->presentationModelFactory = $presentationModelFactory;
        $this->countryRepository = $countryRepository;
    }
    /**
     * @param SearchModel $model
     * @return iterable
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findEtsyProducts(SearchModel $model): iterable
    {
        $findAllListingModel = $this->createFindAllListingsRequestModel($model);

        $responses = $this->etsyApiEntryPoint->findAllListingActive($findAllListingModel);

        $responseModelsGen = Util::createGenerator($responses->getResults()->toArray());

        $searchResponseModels = TypedArray::create('integer', SearchResponseModel::class);

        foreach ($responseModelsGen as $entry) {
            $item = $entry['item'];

            $listingId = $item['listing_id'];

            $listingImages = $this->etsyApiEntryPoint->findAllListingImages(
                $this->createFindAllListingImagesRequestModel($listingId)
            );

            $listingShop = $this->etsyApiEntryPoint->findGetListingShop(
                $this->createGetListingShopRequestModel($listingId)
            );

            $searchResponseModels[] = $this->presentationModelFactory->createModel(
                $item,
                $listingImages->getResults()->toArray(),
                $listingShop->getResults()
            );
        }

        return $searchResponseModels;
    }
    /**
     * @param string $listingId
     * @return EtsyApiModel
     */
    private function createGetListingShopRequestModel(string $listingId): EtsyApiModel
    {
        $methodType = MethodType::fromKey('getListingShop');

        $queries = TypedArray::create('integer', Query::class);

        $shopListingQuery = new Query(sprintf('/shops/listing/%s?', $listingId));

        $queries[] = $shopListingQuery;

        return new EtsyApiModel(
            $methodType,
            TypedArray::create('integer', ItemFilterModel::class),
            $queries
        );
    }
    /**
     * @param string $listingId
     * @return EtsyApiModel
     */
    private function createFindAllListingImagesRequestModel(string $listingId): EtsyApiModel
    {
        $methodType = MethodType::fromKey('findAllListingImages');

        $queries = TypedArray::create('integer', Query::class);

        $listingImageQuery = new Query(sprintf('/listings/%s/images?', $listingId));

        $queries[] = $listingImageQuery;

        $itemFilters = TypedArray::create('integer', ItemFilterModel::class);

        return new EtsyApiModel(
            $methodType,
            $itemFilters,
            $queries
        );
    }
    /**
     * @param SearchModel $model
     * @return EtsyApiModel
     */
    private function createFindAllListingsRequestModel(SearchModel $model): EtsyApiModel
    {
        $methodType = MethodType::fromKey('findAllListingActive');

        $queries = TypedArray::create('integer', Query::class);

        $listingsActiveQuery = new Query('/listings/active?');

        $queries[] = $listingsActiveQuery;

        $itemFilters = TypedArray::create('integer', ItemFilterModel::class);

        $this->addRequiredItemFilters($model, $itemFilters);
        $this->addOptionalItemFilters($model, $itemFilters);

        return new EtsyApiModel(
            $methodType,
            $itemFilters,
            $queries
        );
    }
    /**
     * @param SearchModel $model
     * @param TypedArray $itemFilters
     */
    private function addRequiredItemFilters(
        SearchModel $model,
        TypedArray $itemFilters
    ) {
        $keywordsModelMetadata = new ItemFilterModel(new ItemFilterMetadata(
            ItemFilterType::fromKey('Keywords'),
            [$model->getKeyword()]
        ));

        $pagination = $model->getPagination();

        $limitModelMetadata = new ItemFilterModel(new ItemFilterMetadata(
            ItemFilterType::fromKey('Limit'),
            [$pagination->getLimit()]
        ));

        $pageModelMetadata = new ItemFilterModel(new ItemFilterMetadata(
            ItemFilterType::fromKey('Page'),
            [$pagination->getPage()]
        ));

        $itemFilters[] = $keywordsModelMetadata;
        $itemFilters[] = $limitModelMetadata;
        $itemFilters[] = $pageModelMetadata;
    }
    /**
     * @param SearchModel $model
     * @param TypedArray $itemFilters
     */
    private function addOptionalItemFilters(
        SearchModel $model,
        TypedArray $itemFilters
    ) {
        if ($model->isLowestPrice()) {
            $itemFilters[] = new ItemFilterModel(new ItemFilterMetadata(
                ItemFilterType::fromKey('SortOn'),
                ['Price']
            ));

            $itemFilters[] = new ItemFilterModel(new ItemFilterMetadata(
                ItemFilterType::fromKey('SortOrder'),
                ['Down']
            ));
        }

        if ($model->isHighestPrice()) {
            $itemFilters[] = new ItemFilterModel(new ItemFilterMetadata(
                ItemFilterType::fromKey('SortOn'),
                ['Price']
            ));

            $itemFilters[] = new ItemFilterModel(new ItemFilterMetadata(
                ItemFilterType::fromKey('SortOrder'),
                ['Up']
            ));
        }
    }
}