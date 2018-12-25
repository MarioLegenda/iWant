<?php

namespace App\Ebay\Business\Request;

use App\Ebay\Business\ItemFilter\ItemFilterFactory;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Library\Processor\CallTypeProcessor;
use App\Ebay\Library\Processor\ItemFiltersProcessor;
use App\Ebay\Library\Processor\ShoppingApiRequestBaseProcessor;
use App\Ebay\Library\RequestProducer;
use App\Ebay\Presentation\Model\CallTypeInterface;
use App\Library\Http\Request;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;
use App\Library\Util\TypedRecursion;

class ShoppingApiRequestMetadataProducer
{
    /**
     * @var ShoppingApiRequestModelInterface $model
     */
    protected $model;
    /**
     * @var ShoppingApiRequestBaseProcessor $requestBaseProcessor
     */
    protected $requestBaseProcessor;
    /**
     * FindItemsByKeywords constructor.
     * @param ShoppingApiRequestModelInterface $model
     * @param ShoppingApiRequestBaseProcessor $requestBaseProcessor
     */
    public function __construct(
        ShoppingApiRequestModelInterface $model,
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
     * @param ShoppingApiRequestModelInterface $model
     * @return TypedArray
     */
    protected function createProcessors(ShoppingApiRequestModelInterface $model): TypedArray
    {
        /** @var CallTypeInterface $callType */
        $callType = $model->getCallType();

        $processors = [];

        $processors[] = $this->requestBaseProcessor;

        if ($model->hasItemFilters()) {
            $processors[] = new ItemFiltersProcessor($this->createItemFilters($model));
        }

        $processors[] = new CallTypeProcessor($callType);

        return TypedArray::create(
            'integer',
            ProcessorInterface::class,
            $processors
        );
    }
    /**
     * @param ShoppingApiRequestModelInterface $model
     * @return TypedArray
     */
    protected function createItemFilters(ShoppingApiRequestModelInterface $model): TypedArray
    {
        $itemFilterFactory = new ItemFilterFactory();

        return $itemFilterFactory->createFromMetadataIterable(
            $model->getItemFilters()->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION)
        );
    }
}