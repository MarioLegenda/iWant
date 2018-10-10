<?php

namespace App\Ebay\Source;

use App\Cache\Implementation\RequestCacheImplementation;
use App\Ebay\Source\Repository\FindingApiRepository;
use App\Library\Http\Request;
use App\Library\Response;

class FinderSource
{
    /**
     * @var FindingApiRepository $repository
     */
    private $repository;
    /**
     * FinderSource constructor.
     * @param FindingApiRepository $repository
     */
    public function __construct(
        FindingApiRepository $repository
    ) {
        $this->repository = $repository;
    }
    /**
     * @param Request $request
     * @return Response
     */
    public function getApiResource(Request $request): Response
    {
        /** @var Response $response */
        return $this->repository->getResource($request);
    }
}