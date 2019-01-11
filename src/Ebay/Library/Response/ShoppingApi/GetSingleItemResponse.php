<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\ShoppingApi\Json\Error;
use App\Ebay\Library\Response\ShoppingApi\Json\Item;
use App\Ebay\Library\Response\ShoppingApi\Json\Root;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;
use App\Symfony\Facade\CountryRepresentation;

class GetSingleItemResponse implements ResponseModelInterface, ArrayNotationInterface
{
    /**
     * @var Root $root
     */
    private $root;
    /**
     * @var Item $item
     */
    private $item;
    /**
     * @var array
     */
    private $errors = [];
    /**
     * @var array $response
     */
    private $response;
    /**
     * Response constructor.
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }
    /**
     * @return Root
     */
    public function getRoot(): Root
    {
        if ($this->root instanceof Root) {
            return $this->root;
        }

        $this->root = new Root(
            $this->response['Ack'],
            $this->response['Timestamp'],
            $this->response['Version']
        );

        unset($this->response['Ack']);
        unset($this->response['Timestamp']);
        unset($this->response['Version']);

        return $this->root;
    }
    /**
     * @return Item
     */
    public function getSingleItem(): Item
    {
        if ($this->item instanceof Item) {
            return $this->item;
        }

        $item = $this->response['Item'];

        $this->item = new Item(
            $item['ItemID'],
            $item['Title'],
            $item['Description'],
            CountryRepresentation::getByAlpha2Code($item['Country']),
            $item['StartTime'],
            $item['EndTime'],
            $item['ViewItemURLForNaturalSearch'],
            $item['ListingType'],
            $item['Location'],
            get_value_or_null($item, 'PaymentMethods'),
            get_value_or_null($item, 'GalleryURL'),
            get_value_or_null($item, 'PictureURL'),
            get_value_or_null($item, 'Quantity'),
            get_value_or_null($item, 'Seller'),
            get_value_or_null($item, 'BidCount'),
            get_value_or_null($item, 'ConvertedCurrentPrice'),
            $item['CurrentPrice'],
            $item['ListingStatus'],
            get_value_or_null($item, 'QuantitySold'),
            get_value_or_null($item, 'ShipToLocations'),
            get_value_or_null($item, 'HitCount'),
            get_value_or_null($item, 'AutoPay'),
            get_value_or_null($item, 'HandlingTime'),
            (function(array $item) {
                if (!isset($item['ConditionID']) and !isset($item['ConditionDisplayName']) and !isset($item['ConditionDescription'])) {
                    return null;
                }

                return [
                    'ConditionId' => isset($item['ConditionID']) ? $item['ConditionID'] : null,
                    'ConditionDisplayName' => isset($item['ConditionDisplayName']) ? $item['ConditionDisplayName'] : null,
                    'ConditionDescription' => isset($item['ConditionDescription']) ? $item['ConditionDescription'] : null,
                ];
            })($item),
            get_value_or_null($item, 'GlobalShipping'),
            get_value_or_null($item, 'AvailableForPickupDropOff'),
            get_value_or_null($item, 'EligibleForPickupDropOff'),
            get_value_or_null($item, 'BestOfferEnabled'),
            get_value_or_null($item, 'BuyItNowAvailable'),
            get_value_or_null($item, 'Storefront')
        );

        unset($this->response['Item']);

        return $this->item;
    }
    /**
     * @param mixed $default
     * @return array
     */
    public function getErrors($default = null)
    {
        $errors = Util::createGenerator($this->response['Errors']);

        foreach ($errors as $entry) {
            $item = $entry['item'];

            $this->errors[] = new Error(
                $item['ShortMessage'],
                $item['LongMessage'],
                $item['ErrorCode'],
                $item['SeverityCode'],
                $item['ErrorClassification']
            );
        }

        unset($this->response['Errors']);

        return $this->errors;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'root' => $this->getRoot()->toArray(),
            'singleItem'  => $this->getSingleItem()->toArray(),
        ];
    }
}