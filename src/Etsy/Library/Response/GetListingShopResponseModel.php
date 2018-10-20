<?php

namespace App\Etsy\Library\Response;

use App\Etsy\Library\Response\ResponseItem\ListingShop;
use App\Etsy\Library\Response\ResponseItem\ResultsInterface;
use App\Library\Tools\LockedImmutableGenericHashSet;
use App\Library\Tools\UnlockedImmutableHashSet;

class GetListingShopResponseModel implements EtsyApiResponseModelInterface
{
    /**
     * @var iterable $responseData
     */
    private $responseData;
    /**
     * @var UnlockedImmutableHashSet $responseObjects
     */
    private $responseObjects;
    /**
     * FindAllShopListingsFeaturedResponseModel constructor.
     * @param iterable|LockedImmutableGenericHashSet $response
     */
    public function __construct(iterable $response)
    {
        $this->responseData = $response->toArray();

        $this->responseObjects = UnlockedImmutableHashSet::create(array_keys($response->toArray()));
    }
    /**
     * @inheritdoc
     */
    public function getCount(): int
    {
        if (!isset($this->responseObjects['count'])) {
            $this->responseObjects['count'] = (int) $this->responseData['count'];
        }

        return $this->responseObjects['count'];
    }
    /**
     * @return ResultsInterface
     */
    public function getResults(): ResultsInterface
    {
        if (!isset($this->responseObjects['results'])) {
            $this->responseObjects['results'] = new ListingShop($this->responseData['results']);
        }

        return $this->responseObjects['results'];
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'count' => $this->getCount(),
            'results' => $this->getResults()->toArray(),
        ];
    }
}