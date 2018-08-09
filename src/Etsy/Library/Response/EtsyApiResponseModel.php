<?php

namespace App\Etsy\Library\Response;

use App\Etsy\Library\Response\ResponseItem\Results;
use App\Library\Tools\LockedImmutableGenericHashSet;
use App\Library\Tools\UnlockedImmutableHashSet;

class EtsyApiResponseModel implements EtsyApiResponseModelInterface
{
    /**
     * @var LockedImmutableGenericHashSet $responseData
     */
    private $responseData;
    /**
     * @var UnlockedImmutableHashSet $responseData
     */
    private $responseObjects;
    /**
     * EtsyApiResponseModel constructor.
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
     * @return Results
     */
    public function getResults(): Results
    {
        if (!isset($this->responseObjects['results'])) {
            $this->responseObjects['results'] = new Results($this->responseData['results']);
        }

        return $this->responseObjects['results'];
    }
}