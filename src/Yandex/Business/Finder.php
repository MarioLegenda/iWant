<?php

namespace App\Yandex\Business;

use App\Library\Http\Request;
use App\Yandex\Business\Request\GetSupportedLanguages;
use App\Yandex\Library\Processor\ApiKeyProcessor;
use App\Yandex\Library\Processor\RequestBaseProcessor;
use App\Yandex\Library\Response\ResponseModelInterface;
use App\Yandex\Library\Response\SupportedLanguagesResponse;
use App\Yandex\Presentation\Model\YandexRequestModelInterface;
use App\Yandex\Source\FinderSource;

class Finder
{
    /**
     * @var FinderSource $finderSource
     */
    private $finderSource;
    /**
     * @var RequestBaseProcessor $requestBaseProcessor
     */
    private $requestBaseProcessor;
    /**
     * @var ApiKeyProcessor $apiKeyProcessor
     */
    private $apiKeyProcessor;
    /**
     * Finder constructor.
     * @param FinderSource $finderSource
     * @param RequestBaseProcessor $requestBaseProcessor
     * @param ApiKeyProcessor $apiKeyProcessor
     */
    public function __construct(
        FinderSource $finderSource,
        RequestBaseProcessor $requestBaseProcessor,
        ApiKeyProcessor $apiKeyProcessor
    ) {
        $this->finderSource = $finderSource;
        $this->requestBaseProcessor = $requestBaseProcessor;
        $this->apiKeyProcessor = $apiKeyProcessor;
    }
    /**
     * @param YandexRequestModelInterface $model
     * @return ResponseModelInterface
     */
    public function getSupportedLanguages(YandexRequestModelInterface $model): ResponseModelInterface
    {
        $getSupportedLanguages = new GetSupportedLanguages(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        /** @var Request $request */
        $request = $getSupportedLanguages->getRequest();

        $resource = $this->finderSource->getApiResource($request);

        return $this->createResponse($resource);
    }
    /**
     * @param string $response
     * @return SupportedLanguagesResponse
     */
    private function createResponse(string $response)
    {
        $responseArray = json_decode($response, true);

        return new SupportedLanguagesResponse($responseArray['langs']);
    }
}