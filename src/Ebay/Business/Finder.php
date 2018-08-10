<?php

namespace App\Ebay\Business;

use App\Ebay\Business\ItemFilter\ItemFilterFactory;
use App\Ebay\Library\Processor\CallTypeProcessor;
use App\Library\Http\Request;
use App\Library\Processor\ProcessorInterface;
use App\Ebay\Library\RequestProducer;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Ebay\Presentation\FindingApi\Model\CallTypeInterface;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Processor\RequestBaseProcessor;
use App\Library\Tools\LockedImmutableHashSet;
use App\Ebay\Library\Type\OperationType;
use App\Ebay\Source\FinderSource;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Library\Processor\ItemFiltersProcessor;
use App\Library\Util\TypedRecursion;

class Finder
{
    /**
     * @var FinderSource $finderSource
     */
    private $finderSource;
    /**
     * @var RequestBaseProcessor $requestBase
     */
    private $requestBase;
    /**
     * Finder constructor.
     * @param FinderSource $finderSource
     * @param RequestBaseProcessor $requestBase
     */
    public function __construct(
        FinderSource $finderSource,
        RequestBaseProcessor $requestBase
    ) {
        $this->finderSource = $finderSource;
        $this->requestBase = $requestBase;
    }
    /**
     * @return FindingApiResponseModelInterface
     * @param FindingApiRequestModelInterface $model
     */
    public function query(FindingApiRequestModelInterface $model): FindingApiResponseModelInterface
    {
        $resource = $this->getRawResource($model);

        return $this->createModelResponse($resource);
    }
    /**
     * @param string $resource
     * @return FindingApiResponseModelInterface
     */
    private function createModelResponse(string $resource): FindingApiResponseModelInterface
    {
        return new XmlFindingApiResponseModel($resource);
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return string
     */
    private function getRawResource(FindingApiRequestModelInterface $model): string
    {
        $requestProducer = new RequestProducer($this->createProcessors($model));

        $request = new Request($requestProducer->produce());

        return $this->finderSource->getFindingApiListing($request);
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return TypedArray
     */
    private function createProcessors(FindingApiRequestModelInterface $model): TypedArray
    {        /** @var CallTypeInterface $callType */
        $callType = $model->getCallType();

        $userParams = LockedImmutableHashSet::create([
            'operation_name' => (string) OperationType::fromValue($callType->getOperationName()),
        ]);

        $this->requestBase->setOptions($userParams);
        $itemFiltersProcessor = new ItemFiltersProcessor($this->createItemFilters($model));
        $callTypeProcessor = new CallTypeProcessor($callType);

        return TypedArray::create('integer', ProcessorInterface::class, [
            $this->requestBase,
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