<?php

namespace App\Etsy\Business;

use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Library\Http\Request;
use App\Library\Tools\LockedImmutableGenericHashSet;
use App\Etsy\Business\ItemFilter\ItemFilterFactory;
use App\Etsy\Library\Method\MethodProcessorFactory;
use App\Etsy\Library\Processor\ApiKeyProcessor;
use App\Etsy\Library\Processor\ItemFiltersProcessor;
use App\Etsy\Library\Processor\RequestBaseProcessor;
use App\Etsy\Library\RequestProducer;
use App\Etsy\Library\Response\EtsyApiResponseModel;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Etsy\Source\FinderSource;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;

class Finder
{
    /**
     * @var RequestBaseProcessor $requestBaseProcessor
     */
    private $requestBaseProcessor;
    /**
     * @var ApiKeyProcessor $apiKeyProcessor
     */
    private $apiKeyProcessor;
    /**
     * @var FinderSource $finderSource
     */
    private $finderSource;
    /**
     * Finder constructor.
     * @param RequestBaseProcessor $requestBaseProcessor
     * @param ApiKeyProcessor $apiKeyProcessor
     * @param FinderSource $finderSource
     */
    public function __construct(
        RequestBaseProcessor $requestBaseProcessor,
        ApiKeyProcessor $apiKeyProcessor,
        FinderSource $finderSource
    ) {
        $this->requestBaseProcessor = $requestBaseProcessor;
        $this->apiKeyProcessor = $apiKeyProcessor;
        $this->finderSource = $finderSource;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
     */
    public function search(EtsyApiModel $model): EtsyApiResponseModelInterface
    {
        $processors = $this->createProcessors($model);

        $requestProducer = new RequestProducer($processors);

        $request = new Request($requestProducer->produce());

        return $this->createResponseModel(
            $this->finderSource->getResource($request)
        );
    }
    /**
     * @param string $responseString
     * @return EtsyApiResponseModel
     */
    private function createResponseModel(string $responseString)
    {
        $responseData = json_decode($responseString, true);

        return new EtsyApiResponseModel(LockedImmutableGenericHashSet::create($responseData));
    }
    /**
     * @param EtsyApiModel $model
     * @return TypedArray
     */
    private function createProcessors(EtsyApiModel $model): TypedArray
    {
        $methodProcessor = $this->createMethodProcessor($model);
        $itemFiltersProcessor = $this->createItemFiltersProcessor($model);

        $processors = TypedArray::create('integer', ProcessorInterface::class);

        $processors[] = $this->requestBaseProcessor;
        $processors[] = $methodProcessor;
        $processors[] = $itemFiltersProcessor;
        $processors[] = $this->apiKeyProcessor;

        return $processors;
    }

    private function createMethodProcessor(EtsyApiModel $model): ProcessorInterface
    {
        return MethodProcessorFactory::create('App\Etsy\Library\Method')
            ->getItemFilterMethodProcessor($model->getMethodType()->getValue());
    }
    /**
     * @param EtsyApiModel $model
     * @return ProcessorInterface
     */
    private function createItemFiltersProcessor(EtsyApiModel $model): ProcessorInterface
    {
        $itemFiltersFactory = ItemFilterFactory::create(
            'App\Etsy\Library\ItemFilter',
            $model->getItemFilters()
        );

        $itemFilters = $itemFiltersFactory->createItemFilters();

        return  new ItemFiltersProcessor($itemFilters);
    }
}