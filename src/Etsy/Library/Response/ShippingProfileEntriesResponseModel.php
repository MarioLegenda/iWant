<?php

namespace App\Etsy\Library\Response;

use App\Etsy\Library\Response\ResponseItem\Results;
use App\Etsy\Library\Response\ResponseItem\ResultsInterface;
use App\Etsy\Library\Response\ResponseItem\ShippingProfileEntriesResults;
use App\Library\Tools\LockedImmutableGenericHashSet;
use App\Library\Tools\UnlockedImmutableHashSet;

class ShippingProfileEntriesResponseModel implements EtsyApiResponseModelInterface
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
     * @return ResultsInterface
     */
    public function getResults(): ResultsInterface
    {
        if (!isset($this->responseObjects['results'])) {
            $this->responseObjects['results'] = new ShippingProfileEntriesResults($this->responseData['results']);
        }

        return $this->responseObjects['results'];
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
}