<?php

namespace App\Ebay\Source;

use App\Ebay\Source\Repository\FindingApiRepository;

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
     * @param string $url
     * @return string
     */
    public function getFindingApiResource(string $url): string
    {
        return $this->repository->getResource($url);
    }
}