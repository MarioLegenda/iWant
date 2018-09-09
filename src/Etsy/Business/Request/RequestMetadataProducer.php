<?php

namespace App\Etsy\Business\Request;

use App\Etsy\Library\Processor\ItemFiltersProcessor;
use App\Etsy\Business\ItemFilter\ItemFilterFactory;
use App\Etsy\Library\Processor\ApiKeyProcessor;
use App\Etsy\Library\Processor\QueryProcessor;
use App\Etsy\Library\Processor\RequestBaseProcessor;
use App\Etsy\Library\RequestProducer;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Library\Http\Request;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;

class RequestMetadataProducer
{
    /**
     * @var EtsyApiModel $model
     */
    protected $model;
    /**
     * @var RequestBaseProcessor $requestBaseProcessor
     */
    protected $requestBaseProcessor;
    /**
     * @var ApiKeyProcessor $apiKeyProcessor
     */
    protected $apiKeyProcessor;
    /**
     * FindAllShopListingsActive constructor.
     * @param EtsyApiModel $model
     * @param RequestBaseProcessor $requestBaseProcessor
     * @param ApiKeyProcessor $apiKeyProcessor
     */
    public function __construct(
        EtsyApiModel $model,
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
        $processors = $this->createProcessors($this->model);

        $requestProducer = new RequestProducer($processors);

        return new Request($requestProducer->produce());
    }
    /**
     * @param EtsyApiModel $model
     * @return TypedArray
     */
    protected function createProcessors(EtsyApiModel $model): TypedArray
    {
        $queryProcessor = $this->createQueryProcessor($model);
        $itemFiltersProcessor = $this->createItemFiltersProcessor($model);

        $processors = TypedArray::create('integer', ProcessorInterface::class);

        $processors[] = $this->requestBaseProcessor;
        $processors[] = $queryProcessor;
        $processors[] = $itemFiltersProcessor;
        $processors[] = $this->apiKeyProcessor;

        return $processors;
    }
    /**
     * @param EtsyApiModel $model
     * @return QueryProcessor
     */
    protected function createQueryProcessor(EtsyApiModel $model)
    {
        return new QueryProcessor($model->getQueries());
    }
    /**
     * @param EtsyApiModel $model
     * @return ProcessorInterface
     */
    protected function createItemFiltersProcessor(EtsyApiModel $model): ProcessorInterface
    {
        $itemFiltersFactory = ItemFilterFactory::create(
            'App\Etsy\Library\ItemFilter',
            $model->getItemFilters()
        );

        $itemFilters = $itemFiltersFactory->createItemFilters();

        return  new ItemFiltersProcessor($itemFilters);
    }
}