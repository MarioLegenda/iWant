<?php

namespace App\App\Presentation\EntryPoint;

use App\App\Business\Finder;
use App\App\Presentation\Model\Request\SingleItemOptionsModel;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\App\Presentation\Model\Response\SingleItemOptionsResponse;

class SingleItemEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * SingleItemEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
    /**
     * @param SingleItemRequestModel $model
     * @return mixed
     */
    public function getSingleItem(SingleItemRequestModel $model)
    {
        return $this->finder->getSingleItem($model);
    }
    /**
     * @param SingleItemOptionsModel $model
     * @return SingleItemOptionsResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function optionsCheckSingleItem(SingleItemOptionsModel $model): SingleItemOptionsResponse
    {
        return $this->finder->createOptionsForSingleItem($model);
    }
}