<?php

namespace App\Etsy\Library\Response;

use App\Etsy\Library\Response\ResponseItem\Results;
use App\Etsy\Library\Response\ResponseItem\ResultsInterface;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Tools\LockedImmutableGenericHashSet;
use App\Library\Tools\UnlockedImmutableHashSet;

class FindAllListingActiveResponseModel implements EtsyApiResponseModelInterface, ArrayNotationInterface
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
     * FindAllListingActiveResponseModel constructor.
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
            $this->responseObjects['results'] = new Results($this->responseData['results']);
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