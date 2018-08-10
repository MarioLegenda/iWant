<?php

namespace App\Bonanza\Business;

use App\Bonanza\Business\ItemFilter\ItemFilterFactory;
use App\Bonanza\Library\Processor\ApiKeyPostHeaderProcessor;
use App\Bonanza\Library\Processor\ItemFiltersProcessor;
use App\Bonanza\Library\Processor\RequestBaseProcessor;
use App\Bonanza\Presentation\Model\BonanzaApiModelInterface;
use App\Bonanza\Source\FinderSource;
use App\Bonanza\Library\Processor\CallTypeProcessor;
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
     * Finder constructor.
     * @param FinderSource $finderSource
     * @param RequestBaseProcessor $requestBaseProcessor
     * @param ApiKeyPostHeaderProcessor $apiKeyPostHeaderProcessor
     */
    public function __construct(
        FinderSource $finderSource,
        RequestBaseProcessor $requestBaseProcessor,
        ApiKeyPostHeaderProcessor $apiKeyPostHeaderProcessor
    ) {
        $this->finderSource = $finderSource;
        $this->apiKeyPostHeaderProcessor = $apiKeyPostHeaderProcessor;
        $this->requestBaseProcessor = $requestBaseProcessor;
    }
    /**
     * @param BonanzaApiModelInterface $model
     */
    public function search(BonanzaApiModelInterface $model)
    {
        $callTypeProcessor = new CallTypeProcessor($model->getCallType());
        $itemFiltersProcessor = $this->createItemFiltersProcessor($model);

        $request = $this->createRequest(
            $callTypeProcessor,
            $itemFiltersProcessor
        );

        $this->finderSource->getResource($request);
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