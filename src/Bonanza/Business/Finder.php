<?php

namespace App\Bonanza\Business;

use App\Bonanza\Business\ItemFilter\ItemFilterFactory;
use App\Bonanza\Library\Processor\ApiKeyPostHeaderProcessor;
use App\Bonanza\Library\Processor\ItemFiltersProcessor;
use App\Bonanza\Library\Processor\RequestBaseProcessor;
use App\Bonanza\Library\Response\BonanzaApiResponseModel;
use App\Bonanza\Library\Response\BonanzaApiResponseModelInterface;
use App\Bonanza\Presentation\Model\BonanzaApiModelInterface;
use App\Bonanza\Source\FinderSource;
use App\Bonanza\Library\Processor\CallTypeProcessor;
use App\Cache\Implementation\RequestCacheImplementation;
use App\Library\Processor\ProcessorInterface;
use App\Library\Util\TypedRecursion;
use App\Library\Http\Request;

class Finder
{
    /**
     * @var RequestBaseProcessor $requestBaseProcessor
     */
    private $requestBaseProcessor;
    /**
     * @var FinderSource $finderSource
     */
    private $finderSource;
    /**
     * @var ApiKeyPostHeaderProcessor $apiKeyPostHeaderProcessor
     */
    private $apiKeyPostHeaderProcessor;
    /**
     * @var RequestCacheImplementation $cacheImplementation
     */
    private $cacheImplementation;
    /**
     * Finder constructor.
     * @param FinderSource $finderSource
     * @param RequestBaseProcessor $requestBaseProcessor
     * @param ApiKeyPostHeaderProcessor $apiKeyPostHeaderProcessor
     * @param RequestCacheImplementation $cacheImplementation
     */
    public function __construct(
        FinderSource $finderSource,
        RequestBaseProcessor $requestBaseProcessor,
        ApiKeyPostHeaderProcessor $apiKeyPostHeaderProcessor,
        RequestCacheImplementation $cacheImplementation
    ) {
        $this->finderSource = $finderSource;
        $this->apiKeyPostHeaderProcessor = $apiKeyPostHeaderProcessor;
        $this->requestBaseProcessor = $requestBaseProcessor;
        $this->cacheImplementation = $cacheImplementation;
    }
    /**
     * @param BonanzaApiModelInterface $model
     * @return BonanzaApiResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function search(BonanzaApiModelInterface $model): BonanzaApiResponseModelInterface
    {
        $callTypeProcessor = new CallTypeProcessor($model->getCallType());
        $itemFiltersProcessor = $this->createItemFiltersProcessor($model);

        $request = $this->createRequest(
            $callTypeProcessor,
            $itemFiltersProcessor
        );

        if (!$this->cacheImplementation->isRequestStored($request)) {
            $resource = $this->finderSource->getResource($request);

            $resource = $this->cacheImplementation->store($request, $resource);

            return $this->createModelFromResource($resource);
        }

        return $this->createModelFromResource(
            $this->cacheImplementation->getFromStoreByRequest($request)
        );
    }
    /**
     * @param string $resource
     * @return BonanzaApiResponseModel
     */
    private function createModelFromResource(string $resource): BonanzaApiResponseModelInterface
    {
        return new BonanzaApiResponseModel(json_decode($resource, true));
    }
    /**
     * @param ProcessorInterface $callTypeProcessor
     * @param ProcessorInterface $itemFiltersProcessor
     * @return Request
     */
    private function createRequest(
        ProcessorInterface $callTypeProcessor,
        ProcessorInterface $itemFiltersProcessor
    ): Request {
        $callType = $callTypeProcessor->process()->getProcessed();
        $itemFilters = $itemFiltersProcessor->process()->getProcessed();

        $request = new Request(
            $this->requestBaseProcessor->process()->getProcessed(),
            $this->apiKeyPostHeaderProcessor->process()->getProcessed(),
            sprintf(
                '%s%s',
                $callType,
                $itemFilters
            )
        );

        return $request;
    }
    /**
     * @param BonanzaApiModelInterface $model
     * @return ItemFiltersProcessor
     */
    private function createItemFiltersProcessor(BonanzaApiModelInterface $model): ItemFiltersProcessor
    {
        $itemFiltersFactory = new ItemFilterFactory();

        $itemFilters = $itemFiltersFactory->createFromMetadataIterable(
            $model->getItemFilters()->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION)
        );

        $itemFiltersProcessor = new ItemFiltersProcessor($itemFilters);

        return $itemFiltersProcessor;
    }
}