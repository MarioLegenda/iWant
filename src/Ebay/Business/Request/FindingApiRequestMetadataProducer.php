<?php

namespace App\Ebay\Business\Request;

use App\Ebay\Business\ItemFilter\ItemFilterFactory;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Processor\CallTypeProcessor;
use App\Ebay\Library\Processor\ItemFiltersProcessor;
use App\Ebay\Library\Processor\FindingApiRequestBaseProcessor;
use App\Ebay\Library\RequestProducer;
use App\Ebay\Library\Type\OperationType;
use App\Ebay\Presentation\Model\CallTypeInterface;
use App\Library\Http\Request;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;
use App\Library\Tools\LockedImmutableHashSet;
use App\Library\Util\TypedRecursion;

class FindingApiRequestMetadataProducer
{
    /**
     * @var FindingApiRequestModelInterface $model
     */
    protected $model;
    /**
     * @var FindingApiRequestBaseProcessor $requestBaseProcessor
     */
    protected $requestBaseProcessor;
    /**
     * FindItemsByKeywords constructor.
     * @param FindingApiRequestModelInterface $model
     * @param FindingApiRequestBaseProcessor $requestBaseProcessor
     */
    public function __construct(
        FindingApiRequestModelInterface $model,
        FindingApiRequestBaseProcessor $requestBaseProcessor
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
    protected function createProcessors(FindingApiRequestModelInterface $model): TypedArray
    {        /** @var CallTypeInterface $callType */
        $callType = $model->getCallType();

        $userParams = LockedImmutableHashSet::create([
            'operation_name' => (string) OperationType::fromValue($callType->getOperationName()),
        ]);

        $this->requestBaseProcessor->setOptions($userParams);
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
    protected function createItemFilters(FindingApiRequestModelInterface $model): TypedArray
    {
        $itemFilterFactory = new ItemFilterFactory();

        return $itemFilterFactory->createFromMetadataIterable(
            $model->getItemFilters()->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION)
        );
    }
}