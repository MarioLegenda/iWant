<?php

namespace App\Web\Library;

use App\Library\Util\Environment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseEnvironmentHandler
{
    /**
     * @var Environment $environment
     */
    private $environment;
    /**
     * @var int $browserSingleItemCacheTtl
     */
    private $browserSingleItemCacheTtl;
    /**
     * @var int $browserSearchResponseCacheTtl
     */
    private $browserSearchResponseCacheTtl;
    /**
     * @var int $browserItemTranslationCacheTtl
     */
    private $browserItemTranslationCacheTtl;
    /**
     * @var int $browserKeywordTranslationCacheTtl
     */
    private $browserKeywordTranslationCacheTtl;
    /**
     * ResponseEnvironmentHandler constructor.
     * @param Environment $environment
     * @param int $browserSingleItemCacheTtl
     * @param int $browserSearchResponseCacheTtl
     * @param int $browserItemTranslationCacheTtl
     * @param int $browserKeywordTranslationCacheTtl
     */
    public function __construct(
        Environment $environment,
        int $browserSingleItemCacheTtl,
        int $browserSearchResponseCacheTtl,
        int $browserItemTranslationCacheTtl,
        int $browserKeywordTranslationCacheTtl
    ) {
        $this->environment = $environment;
        $this->browserSingleItemCacheTtl = $browserSingleItemCacheTtl;
        $this->browserSearchResponseCacheTtl = $browserSearchResponseCacheTtl;
        $this->browserItemTranslationCacheTtl = $browserItemTranslationCacheTtl;
        $this->browserKeywordTranslationCacheTtl = $browserKeywordTranslationCacheTtl;
    }
    /**
     * @param Response $response
     * @return Response|JsonResponse
     */
    public function handleShippingResponseCache(Response $response): Response
    {
        if ((string) $this->environment === 'prod') {
            $response->setCache([
                'max_age' => $this->browserSingleItemCacheTtl,
            ]);

            return $response;
        }

        return $this->addNoCacheIfDevEnv($response);
    }
    /**
     * @param Response $response
     * @return Response|JsonResponse
     */
    public function handleSingleItemCache(Response $response): Response
    {
        if ((string) $this->environment === 'prod') {
            $response->setCache([
                'max_age' => $this->browserSingleItemCacheTtl,
            ]);

            return $response;
        }

        if ((string) $this->environment === 'dev')  {
            $response->headers->set('Cache-Control', 'no-cache');

            return $response;
        }
    }
    /**
     * @param Response $response
     * @return Response|JsonResponse
     */
    public function handleGetCountries(Response $response): Response
    {
        if ((string) $this->environment === 'prod') {
            $response->setCache([
                'max_age' => 60 * 60 * 24 * 30
            ]);

            return $response;
        }

        if ((string) $this->environment === 'dev')  {
            $response->headers->set('Cache-Control', 'no-cache');

            return $response;
        }
    }
    /**
     * @param Response $response
     * @return Response|JsonResponse
     */
    public function handleGetGlobalIdsInformation(Response $response): Response
    {
        if ((string) $this->environment === 'prod') {
            $response->setCache([
                'max_age' => 60 * 60 * 24 * 30
            ]);

            return $response;
        }

        return $this->addNoCacheIfDevEnv($response);
    }
    /**
     * @param Response $response
     * @return Response
     */
    public function handleGetProducts(Response $response): Response
    {
        $response->headers->set('Content-Type', 'application/json');

        if ((string) $this->environment === 'prod') {
            $response->setCache([
                'max_age' => $this->browserSearchResponseCacheTtl,
            ]);

            return $response;
        }

        return $this->addNoCacheIfDevEnv($response);
    }
    /**
     * @param Response $response
     * @return Response|JsonResponse
     */
    public function handlePrepareProducts(Response $response)
    {
        if ((string) $this->environment === 'prod') {
            $response->setCache([
                'max_age' => $this->browserSearchResponseCacheTtl,
            ]);

            return $response;
        }

        return $this->addNoCacheIfDevEnv($response);
    }
    /**
     * @param Response $response
     * @return Response
     */
    public function handleQuickLookGetSingleItem(Response $response): Response
    {
        if ((string) $this->environment === 'prod') {
            $response->setCache([
                'max_age' => $this->browserSearchResponseCacheTtl,
            ]);

            return $response;
        }

        return $this->addNoCacheIfDevEnv($response);
    }
    /**
     * @param Response $response
     * @return Response
     */
    private function addNoCacheIfDevEnv(Response $response): Response
    {
        if ((string) $this->environment === 'dev')  {
            $response->headers->set('Cache-Control', 'no-cache');

            return $response;
        }
    }
}