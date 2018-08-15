<?php

namespace App\Etsy\Business;

use App\Etsy\Business\Request\FindAllListingActive;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Library\Http\Request;
use App\Library\Tools\LockedImmutableGenericHashSet;
use App\Etsy\Business\ItemFilter\ItemFilterFactory;
use App\Etsy\Library\MethodProcessor\MethodProcessorFactory;
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
        $findAllListingActive = new FindAllListingActive(
            $model,
            $this->requestBaseProcessor,
            $this->apiKeyProcessor
        );

        return $this->createResponseModel(
            $this->finderSource->getResource($findAllListingActive->getRequest())
        );
    }

    public function getShippingInfoByListingId(EtsyApiModel $model)
    {

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
}