<?php

namespace App\Component\Search\Ebay\Business;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\PreparedItemsSearchModel;
use App\Component\Search\Ebay\Model\Response\Image;
use App\Component\Search\Ebay\Model\Response\Nan;
use App\Component\Search\Ebay\Model\Response\Price;
use App\Component\Search\Ebay\Model\Response\SearchResponseModel;
use App\Component\Search\Ebay\Model\Response\Title;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Util\Util;
use App\Translation\TranslationCenter;

class ModelPreparationFactory
{
    /**
     * @var TranslationCenter $translationService
     */
    private $translationService;
    /**
     * @var SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    private $searchResponseCacheImplementation;
    /**
     * ModelPreparationFactory constructor.
     * @param TranslationCenter $translationService
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    public function __construct(
        TranslationCenter $translationService,
        SearchResponseCacheImplementation $searchResponseCacheImplementation
    ) {
        $this->translationService = $translationService;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
    }
    /**
     * @param PreparedItemsSearchModel $model
     * @return TypedArray
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function prepareSearchItems(
        PreparedItemsSearchModel $model
    ): TypedArray {
        if (!$this->searchResponseCacheImplementation->isStored($model->getUniqueName())) {
            return null;
        }

        $searchResults = json_decode($this->searchResponseCacheImplementation->getStored($model->getUniqueName()), true);

        $indexes = $this->createPaginationIndexes($model->getPagination());

        $paginatedResults = [];
        for ($i = $indexes['firstIndex']; $i <= $indexes['lastIndex']; $i++) {
            if (array_key_exists($i, $searchResults)) {
                $paginatedResults[] = $searchResults[$i];
            }
        }

        $searchResponseModels = TypedArray::create('integer', SearchResponseModel::class);

        $storedResponseGen = Util::createGenerator($paginatedResults);

        foreach ($storedResponseGen as $entry) {
            $item = $entry['item'];

            $searchResponseModels[] = new SearchResponseModel(
                $item['uniqueName'],
                $item['itemId'],
                new Title($this->translationService->translateSingle(
                    'title',
                    $item['itemId'],
                    $item['title']['original'],
                    $model->getLocale()
                )),
                new Image((is_string($item['image']['url'])) ? $item['image']['url'] : Nan::fromValue()),
                $item['shopName'],
                new Price($item['price']['currency'], $item['price']['price']),
                $item['viewItemUrl'],
                MarketplaceType::fromValue($item['marketplace']),
                $item['staticUrl'],
                $item['taxonomyName'],
                $item['shippingLocations'],
                $item['globalId']
            );
        }

        return $searchResponseModels;
    }
    /**
     * @param Pagination $pagination
     * @return array
     */
    private function createPaginationIndexes(Pagination $pagination)
    {
        return [
            'firstIndex' => ($pagination->getPage() * $pagination->getLimit()) - $pagination->getLimit(),
            'lastIndex' => ($pagination->getPage() * $pagination->getLimit()) - 1,
        ];
    }
}