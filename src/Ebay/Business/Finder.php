<?php

namespace App\Ebay\Business;

use App\Ebay\Business\ItemFilter\ItemFilterFactory;
use App\Ebay\Business\Request\FindItemsByKeywords;
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
    public function findItemsByKeywords(FindingApiRequestModelInterface $model): FindingApiResponseModelInterface
    {
        $findItemsByKeywords = new FindItemsByKeywords(
            $model,
            $this->requestBase
        );

        return $this->createModelResponse(
            $this->finderSource->getFindingApiListing($findItemsByKeywords->getRequest())
        );
    }
    /**
     * @param string $resource
     * @return FindingApiResponseModelInterface
     */
    private function createModelResponse(string $resource): FindingApiResponseModelInterface
    {
        return new XmlFindingApiResponseModel($resource);
    }
}