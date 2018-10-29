<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\PreparedItemsSearchModel;
use App\Component\Search\Ebay\Model\Response\Image;
use App\Component\Search\Ebay\Model\Response\Price;
use App\Component\Search\Ebay\Model\Response\SearchResponseModel;
use App\Component\Search\Ebay\Model\Response\Title;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Util\Util;

class ModelPreparationFactory
{
    /**
     * @param PreparedItemsSearchModel $model
     * @param array $searchResults
     * @return TypedArray|SearchResponseModel
     */
    public function prepareSearchItems(
        PreparedItemsSearchModel $model,
        array $searchResults
    ): TypedArray {
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
                new Title($item['title']['original']),
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