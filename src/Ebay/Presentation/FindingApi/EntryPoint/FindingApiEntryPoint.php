<?php

namespace App\Ebay\Presentation\FindingApi\EntryPoint;

use App\Ebay\Business\Finder;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\ResponseModelInterface;

class FindingApiEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * FindingApiEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return ResponseModelInterface
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     */
    public function findItemsByKeywords(FindingApiRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->findItemsByKeywords($model);
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return ResponseModelInterface
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     */
    public function findItemsAdvanced(FindingApiRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->findItemsAdvanced($model);
    }
    /**
     * @param FindingApiRequestModelInterface $model
     * @return ResponseModelInterface
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     */
    public function findItemsInEbayStores(FindingApiRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->findItemsInEbayStores($model);
    }

    public function getVersion(FindingApiRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->getVersion($model);
    }
}