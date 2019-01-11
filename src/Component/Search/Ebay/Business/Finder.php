<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Model\Request\InternalSearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;
use App\Component\Search\Ebay\Business\Factory\EbayModelFactory;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;

class Finder
{
    /**
     * @var FindingApiEntryPoint $findingApiEntryPoint
     */
    private $findingApiEntryPoint;
    /**
     * @var EbayModelFactory $ebayModelFactory
     */
    private $ebayModelFactory;
    /**
     * Finder constructor.
     * @param FindingApiEntryPoint $findingApiEntryPoint
     * @param EbayModelFactory $ebayModelFactory
     */
    public function __construct(
        FindingApiEntryPoint $findingApiEntryPoint,
        EbayModelFactory $ebayModelFactory
    ) {
        $this->findingApiEntryPoint = $findingApiEntryPoint;
        $this->ebayModelFactory = $ebayModelFactory;
    }
    /**
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     * @return iterable
     * @throws \App\Symfony\Exception\HttpException
     */
    public function findEbayProductsAdvanced(SearchModelInterface $model): iterable
    {
        /** @var FindingApiModel[] $findingApiModel */
        $findingApiModels = $this->ebayModelFactory->createFindItemsAdvancedModel($model);

        $responses = TypedArray::create('integer', ResponseModelInterface::class);

        /** @var FindingApiModel $model */
        foreach ($findingApiModels as $model) {
            $responses[] = $this->findingApiEntryPoint->findItemsByKeywords($model);
        }

        return $responses;
    }
}