<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Model\Request\InternalSearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;
use App\Component\Search\Ebay\Business\Factory\EbayModelFactory;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;

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
     * @return ResponseModelInterface
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     */
    public function findEbayProductsAdvanced(SearchModelInterface $model): ResponseModelInterface
    {
        /** @var FindingApiModel $findingApiModel */
        $findingApiModel = $this->ebayModelFactory->createFindItemsAdvancedModel($model);

        return $this->findingApiEntryPoint->findItemsAdvanced($findingApiModel);
    }
}