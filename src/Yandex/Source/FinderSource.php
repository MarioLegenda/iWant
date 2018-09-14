<?php

namespace App\Yandex\Source;

use App\Library\Http\Request;
use App\Library\Response;
use App\Yandex\Source\Repository\Repository;

class FinderSource
{
    /**
     * @var Repository $repository
     */
    private $repository;
    /**
     * FinderSource constructor.
     * @param Repository $repository
     */
    public function __construct(
        Repository $repository
    ) {
        $this->repository = $repository;
    }
    /**
     * @param Request $request
     * @return string
     */
    public function getApiResource(Request $request): string
    {
        /** @var Response $response */
        $response = $this->repository->getResource($request);

        return $response->getResponseString();
    }
}