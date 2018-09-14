<?php

namespace App\Yandex\Business\Request;

use App\Library\Http\Request;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;
use App\Yandex\Library\Processor\ApiKeyProcessor;
use App\Yandex\Library\Processor\CallTypeProcessor;
use App\Yandex\Library\Processor\QueryProcessor;
use App\Yandex\Library\Processor\RequestBaseProcessor;
use App\Yandex\Library\RequestProducer;
use App\Yandex\Presentation\Model\YandexRequestModelInterface;

class DetectLanguage
{
    /**
     * @var ApiKeyProcessor $apiKeyProcessor
     */
    private $apiKeyProcessor;
    /**
     * @var YandexRequestModelInterface $model
     */
    private $model;
    /**
     * @var RequestBaseProcessor $requestBaseProcessor
     */
    private $requestBaseProcessor;
    /**
     * GetSupportedLanguages constructor.
     * @param YandexRequestModelInterface $model
     * @param RequestBaseProcessor $requestBaseProcessor
     * @param ApiKeyProcessor $apiKeyProcessor
     */
    public function __construct(
        YandexRequestModelInterface $model,
        RequestBaseProcessor $requestBaseProcessor,
        ApiKeyProcessor $apiKeyProcessor
    ) {
        $this->model = $model;
        $this->requestBaseProcessor = $requestBaseProcessor;
        $this->apiKeyProcessor = $apiKeyProcessor;
    }
    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        $requestProducer = new RequestProducer($this->createProcessors());

        return new Request($requestProducer->produce());
    }
    /**
     * @return TypedArray
     */
    private function createProcessors(): TypedArray
    {
        $processors = TypedArray::create('integer', ProcessorInterface::class);

        $queryProcessor = new QueryProcessor($this->model->getQueries());

        $processors[] = $this->requestBaseProcessor;
        $processors[] = new CallTypeProcessor($this->model->getCallType());
        $processors[] = $this->apiKeyProcessor;
        $processors[] = $queryProcessor;

        return $processors;
    }
}