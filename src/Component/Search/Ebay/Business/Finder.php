<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Model\Request\SearchRequestModel;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Business\Factory\EbayModelFactory;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;

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
     * @param SearchModel $model
     * @return iterable
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findEbayProducts(SearchModel $model): iterable
    {
        $responses = $this->getResponses($model);
    }
    /**
     * @param SearchModel $model
     * @return iterable
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getResponses(SearchModel $model): iterable
    {
        /** @var SearchRequestModel[] $requestModels */
        $requestModels = $this->ebayModelFactory->createRequestModels($model);

        $responses = [];
        /** @var SearchRequestModel $requestModel */
        foreach ($requestModels as $requestModel) {
            $response = $this->findingApiEntryPoint
                ->findItemsInEbayStores($requestModel->getEntryPointModel());

            $globalId = $requestModel->getMetadata()->getGlobalId();
            $globalIdInformation = GlobalIdInformation::instance()->getTotalInformation($globalId);

            $responses[$requestModel->getMetadata()->getGlobalId()][] = [
                'globalIdInformation' => $globalIdInformation,
                'response' => $response,
            ];
        }

        return $responses;
    }
}