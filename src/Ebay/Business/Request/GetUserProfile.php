<?php

namespace App\Ebay\Business\Request;

use App\Ebay\Business\ItemFilter\ItemFilterFactory;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Processor\CallTypeProcessor;
use App\Ebay\Library\Processor\ItemFiltersProcessor;
use App\Ebay\Library\Processor\RequestBaseProcessor;
use App\Ebay\Library\Processor\ShoppingApiRequestBaseProcessor;
use App\Ebay\Library\RequestProducer;
use App\Library\Http\Request;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;
use App\Library\Util\TypedRecursion;

class GetUserProfile
{
    /**
     * @var FindingApiRequestModelInterface $model
     */
    private $model;
    /**
     * @var RequestBaseProcessor $requestBaseProcessor
     */
    private $requestBaseProcessor;
    /**
     * FindItemsByKeywords constructor.
     * @param FindingApiRequestModelInterface $model
     * @param ShoppingApiRequestBaseProcessor $requestBaseProcessor
     */
    public function __construct(
        FindingApiRequestModelInterface $model,
        ShoppingApiRequestBaseProcessor $requestBaseProcessor
    ) {
        $this->model = $model;
        $this->requestBaseProcessor = $requestBaseProcessor;
    }
    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        $requestProducer = new RequestProducer($this->createProcessors($this->model));

        return new Request($requestProducer->produce());
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return TypedArray
     */
    private function createProcessors(FindingApiRequestModelInterface $model): TypedArray
    {
        $callType = $model->getCallType();

        $itemFiltersProcessor = new ItemFiltersProcessor($this->createItemFilters($model));
        $callTypeProcessor = new CallTypeProcessor($callType);

        return TypedArray::create('integer', ProcessorInterface::class, [
            $this->requestBaseProcessor,
            $itemFiltersProcessor,
            $callTypeProcessor
        ]);
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return TypedArray
     */
    private function createItemFilters(FindingApiRequestModelInterface $model): TypedArray
    {
        $itemFilterFactory = new ItemFilterFactory();

        return $itemFilterFactory->createFromMetadataIterable(
            $model->getItemFilters()->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION)
        );
    }
}