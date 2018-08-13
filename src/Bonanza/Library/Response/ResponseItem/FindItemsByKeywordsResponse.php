<?php

namespace App\Bonanza\Library\Response\ResponseItem;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class FindItemsByKeywordsResponse implements ResponseItemsInterface, ArrayNotationInterface
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
        iterable $findItemsByKeywordsResponse
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
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'totalEntries' => $this->getTotalEntries(),
            'items' => $this->getItems()->toArray(),
        ];
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