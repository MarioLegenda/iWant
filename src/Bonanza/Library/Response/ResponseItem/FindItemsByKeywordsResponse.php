<?php

namespace App\Bonanza\Library\Response\ResponseItem;

use App\Library\Infrastructure\Helper\TypedArray;

class FindItemsByKeywordsResponse
{
    /**
     * @var iterable $findItemsByKeywordsResponse
     */
    private $findItemsByKeywordsResponse;
    /**
     * @var array $responseObjects
     */
    private $responseObjects = [
        'items' => null,
    ];
    /**
     * FindItemsByKeywordsResponse constructor.
     * @param iterable $findItemsByKeywordsResponse
     */
    public function __construct(
        Iterable $findItemsByKeywordsResponse
    ) {
        $this->findItemsByKeywordsResponse = $findItemsByKeywordsResponse;
    }
    /**
     * @return TypedArray
     */
    public function getItems(): TypedArray
    {
        if (!$this->responseObjects['items'] instanceof TypedArray) {
            $items = $this->findItemsByKeywordsResponse['item'];

            $createdItems = $this->createItems($items);

            $this->responseObjects['items'] = $createdItems;
        }

        return $this->responseObjects['items'];
    }
    /**
     * @return int
     */
    public function getTotalEntries(): int
    {
        return $this->findItemsByKeywordsResponse['paginationOutput']['totalEntries'];
    }
    /**
     * @param iterable $items
     * @return TypedArray|array
     */
    private function createItems(iterable $items)
    {
        $results = TypedArray::create('integer', Item::class);
        foreach ($items as $item) {
            $results[] = new Item($item);
        }

        return $results;
    }
}