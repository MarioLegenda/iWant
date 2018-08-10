<?php

namespace App\Ebay\Source;

use App\Ebay\Source\Repository\FindingApiRepository;
use App\Library\Http\Request;

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
     * @return string
     */
    public function getFindingApiListing(Request $request): string
    {
        return $this->repository->getResource($request);
    }
}