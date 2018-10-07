<?php

namespace App\Ebay\Source;

use App\Cache\Implementation\RequestCacheImplementation;
use App\Ebay\Source\Repository\FindingApiRepository;
use App\Library\Http\Request;
use App\Library\Response;

class FinderSource
{
    /**
     * @var RequestCacheImplementation $cacheImplementation
     */
    private $cacheImplementation;
    /**
     * @var FindingApiRepository $repository
     */
    private $repository;
    /**
     * FinderSource constructor.
     * @param FindingApiRepository $repository
     * @param RequestCacheImplementation $requestCacheImplementation
     */
    public function __construct(
        FindingApiRepository $repository,
        RequestCacheImplementation $requestCacheImplementation
    ) {
        $this->repository = $repository;
        $this->cacheImplementation = $requestCacheImplementation;
    }
    /**
     * @param Request $request
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getApiResource(Request $request): Response
    {
        $this->cacheImplementation->deleteIfExpired($request);

        if (
            $this->cacheImplementation->isRequestStored($request) and
            !$this->cacheImplementation->isExpired($request)
        ) {
            $responseString = $this->cacheImplementation->getFromStoreByRequest($request);

            return $this->createResponse($responseString, $request);
        }

        /** @var Response $response */
        $response = $this->repository->getResource($request);

        $this->cacheImplementation->store($request, $response->getResponseString());

        return $response;
    }

    /**
     * @param string $response
     * @param Request $request
     * @return Response
     */
    private function createResponse(
        string $response,
        Request $request
    ): Response {
        return new Response(
            $request,
            $response,
            200
        );
    }
}