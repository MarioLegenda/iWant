<?php

namespace App\Ebay\Source;

use App\Ebay\Source\Repository\FindingApiRepository;
use App\Library\Http\Request;
use App\Library\Http\Response\ResponseModelInterface;

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
     * @return ResponseModelInterface
     */
    public function getApiResource(Request $request): ResponseModelInterface
    {
        /** @var ResponseModelInterface $response */
        return $this->repository->getResource($request);
    }
}