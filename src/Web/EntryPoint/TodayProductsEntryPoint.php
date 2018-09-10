<?php

namespace App\Web\EntryPoint;

use App\Component\TodayProducts\TodayProductsComponent;
use App\Web\Model\Request\TodayProductRequestModel;

class TodayProductsEntryPoint
{
    /**
     * @var TodayProductsComponent $todayProductsComponent
     */
    private $todayProductsComponent;
    /**
     * TodayProductsEntryPoint constructor.
     * @param TodayProductsComponent $todayProductsComponent
     */
    public function __construct(
        TodayProductsComponent $todayProductsComponent
    ) {
        $this->todayProductsComponent = $todayProductsComponent;
    }
    /**
     * @param TodayProductRequestModel $model
     * @return array
     * @throws \App\Symfony\Exception\HttpException
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getTodaysProducts(TodayProductRequestModel $model)
    {
        return $this->todayProductsComponent->getTodaysProducts($model);
    }
}