<?php

namespace App\Cache\Implementation;

use App\Cache\Cache\ApiRequestCache;
use App\Doctrine\Entity\RequestCache;
use App\Library\Http\Request;
use App\Library\Util\Util;

class RequestCacheImplementation
{
    /**
     * @var ApiRequestCache $apiRequestCache
     */
    private $apiRequestCache;
    /**
     * CacheImplementation constructor.
     * @param ApiRequestCache $apiRequestCache
     */
    public function __construct(
        ApiRequestCache $apiRequestCache
    ) {
        $this->apiRequestCache = $apiRequestCache;
    }
    /**
     * @param Request $request
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function isRequestStored(
        Request $request
    ): bool {
        $uniqueName = $this->createUniqueNameFromRequest($request);

        $cache = $this->apiRequestCache->get($uniqueName);

        return $cache instanceof RequestCache;
    }
    /**
     * @param Request $request
     * @param string $response
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function store(
        Request $request,
        string $response
    ): string {
        $uniqueName = $this->createUniqueNameFromRequest($request);

        $cache = $this->apiRequestCache->get($uniqueName);

        if (!$cache instanceof RequestCache) {
            $this->apiRequestCache->set(
                $uniqueName,
                $response,
                $this->calculateTTL()
            );

            return $response;
        }

        $expiresAt = $cache->getExpiresAt();

        /**
         * If a cache entry is expired, delete the old entry and add a new one
         */
        if (Util::toDateTime()->getTimestamp() > $expiresAt) {
            $this->apiRequestCache->delete($uniqueName);

            $this->apiRequestCache->set(
                $request,
                $response,
                $this->calculateTTL()
            );
        }

        return $response;
    }
    /**
     * @param Request $request
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getFromStoreByRequest(Request $request): string
    {
        $uniqueName = $this->createUniqueNameFromRequest($request);

        return $this->apiRequestCache->get($uniqueName)->getResponse();
    }
    /**
     * @param Request $request
     * @return string
     */
    private function createUniqueNameFromRequest(Request $request): string
    {
        $baseUrl = $request->getBaseUrl();
        $headers = $request->getHeaders();

        if (is_array($headers)) {
            foreach ($headers as $headerName => $header) {
                $baseUrl.=$headerName.$header;
            }
        }

        $serializedData = serialize($request->getData());

        $uniqueName = base64_encode(sprintf('%s%s', $baseUrl, $serializedData));

        return $uniqueName;
    }
    /**
     * @return int
     */
    private function calculateTTL(): int
    {
        $currentTimestamp = Util::toDateTime()->getTimestamp();
        $hours48 = 60 * 60 * 24;

        return $currentTimestamp + $hours48;
    }
}