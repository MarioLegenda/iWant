<?php

namespace App\Component\Search;

use App\Cache\Implementation\PreparedResponseCacheImplementation;
use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Finder as EbayFinder;
use App\Component\Search\Ebay\Business\ModelPreparationFactory;
use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\PreparedItemsSearchModel;
use App\Component\Search\Ebay\Model\Response\Image;
use App\Component\Search\Ebay\Model\Response\Nan;
use App\Component\Search\Ebay\Model\Response\PreparedEbayResponse;
use App\Component\Search\Ebay\Model\Response\Price;
use App\Component\Search\Ebay\Model\Response\Title;
use App\Component\Search\Etsy\Business\Finder as EtsyFinder;
use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;
use App\Component\Search\Ebay\Model\Response\SearchResponseModel;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Ebay\Library\Response\FindingApi\ResponseItem\SearchResultsContainer;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;

class SearchComponent
{
    /**
     * @var EbayFinder $ebayFinder
     */
    private $ebayFinder;
    /**
     * @var EtsyFinder $etsyFinder
     */
    private $etsyFinder;
    /**
     * @var SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    private $searchResponseCacheImplementation;
    /**
     * @var PreparedResponseCacheImplementation $preparedResponseCacheImplementation
     */
    private $preparedResponseCacheImplementation;
    /**
     * @var ModelPreparationFactory $modelPreparationFactory
     */
    private $modelPreparationFactory;
    /**
     * SearchComponent constructor.
     * @param EbayFinder $ebayFinder
     * @param EtsyFinder $etsyFinder
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param PreparedResponseCacheImplementation $preparedResponseCacheImplementation
     * @param ModelPreparationFactory $modelPreparationFactory
     */
    public function __construct(
        EbayFinder $ebayFinder,
        EtsyFinder $etsyFinder,
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        PreparedResponseCacheImplementation $preparedResponseCacheImplementation,
        ModelPreparationFactory $modelPreparationFactory
    ) {
        $this->ebayFinder = $ebayFinder;
        $this->etsyFinder = $etsyFinder;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->preparedResponseCacheImplementation = $preparedResponseCacheImplementation;
        $this->modelPreparationFactory = $modelPreparationFactory;
    }

    public function findEbaySearchByUniqueName(PreparedItemsSearchModel $model): ?iterable
    {
        if (!$this->searchResponseCacheImplementation->isStored($model->getUniqueName())) {
            return null;
        }

        $storedResponse = json_decode($this->searchResponseCacheImplementation->getStored($model->getUniqueName()), true);

        return $this->modelPreparationFactory->prepareSearchItems(
            $model,
            $storedResponse
        );
    }
    /**
     * @param EbaySearchModel $model
     * @return PreparedEbayResponse
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Throwable
     */
    public function prepareEbayProductsAdvanced(EbaySearchModel $model): PreparedEbayResponse
    {
        $uniqueName = md5(serialize($model));

        if ($this->preparedResponseCacheImplementation->isStored($uniqueName)) {
            $response = json_decode($this->preparedResponseCacheImplementation->getStored($uniqueName), true);

            return new PreparedEbayResponse(
                $response['uniqueName'],
                $response['globalIdInformation'],
                $response['globalId'],
                $response['totalEntries'],
                $response['entriesPerPage'],
                $response['isError']
            );
        }

        $exceptionThrown = null;

        try {
            $response = $this->searchEbayAdvanced($model);
        } catch (\Throwable $e) {
            $exceptionThrown = $e;
        }

        $globalId = $model->getGlobalId();
        $totalEntries = $response->getPaginationOutput()->getTotalEntries();
        $entriesPerPage = $response->getPaginationOutput()->getEntriesPerPage();

        $preparedEbayResponse = new PreparedEbayResponse(
            $uniqueName,
            GlobalIdInformation::instance()->getTotalInformation($globalId),
            $globalId,
            $totalEntries,
            $entriesPerPage,
            $exceptionThrown instanceof \Exception
        );

        /** @var SearchResultsContainer $searchResults */
        $searchResults = $response->getSearchResults();

        if ($searchResults->isEmpty()) {
            return $preparedEbayResponse;
        }

        $searchResponseModels = TypedArray::create('integer', SearchResponseModel::class);

        $searchResultsGen = Util::createGenerator($searchResults);

        foreach ($searchResultsGen as $entry) {
            /** @var Item $item */
            $item = $entry['item'];

            $itemId = $item->getItemId();
            $title = new Title($item->getTitle());
            $image = new Image($item->dynamicSingleItemChoice(function(Item $item) {
                $methods = ['getPictureURLSuperSize', 'getPictureURLLarge', 'getGalleryPlusPictureURL', 'getGalleryUrl'];

                foreach ($methods as $method) {
                    $image = $item->{$method}();

                    if (is_string($image)) {
                        return $image;
                    }
                }

                return Nan::fromValue();
            }));

            $shopName = $item->dynamicSingleItemChoice(function(Item $item) {
                if ($item->getStoreInfo() !== null) {
                    return $item->getStoreInfo()->getStoreName();
                }

                if ($item->getSellerInfo() !== null) {
                    return $item->getSellerInfo()->getSellerUsername();
                }

                return 'Unknown';
            });

            $price = new Price(
                $item->getSellingStatus()->getCurrentPrice()['currencyId'],
                $item->getSellingStatus()->getCurrentPrice()['currentPrice']
            );

            $viewItemUrl = $item->getViewItemUrl();
            $marketplaceType = MarketplaceType::fromValue('Ebay');
            $staticUrl = sprintf(
                '/item/Ebay/%s/%s',
                \URLify::filter($item->getTitle()),
                $itemId
            );

            $taxonomyName = 'Invalid taxonomy';
            $shippingLocations = [];

            $searchResponseModels[] = new SearchResponseModel(
                $uniqueName,
                $itemId,
                $title,
                $image,
                $shopName,
                $price,
                $viewItemUrl,
                $marketplaceType,
                $staticUrl,
                $taxonomyName,
                $shippingLocations,
                $globalId
            );
        }

        $this->preparedResponseCacheImplementation->store(
            $uniqueName,
            json_encode($preparedEbayResponse->toArray())
        );

        $this->searchResponseCacheImplementation->store(
            $uniqueName,
            $model->getPagination()->getPage(),
            json_encode($searchResponseModels->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION))
        );

        if ($exceptionThrown instanceof \Exception) {
            throw $exceptionThrown;
        }

        return $preparedEbayResponse;
    }
    /**
     * @param EbaySearchModel $model
     * @return FindingApiResponseModelInterface
     */
    private function searchEbayAdvanced(EbaySearchModel $model): ResponseModelInterface
    {
        return $this->ebayFinder->findEbayProductsAdvanced($model);
    }
}