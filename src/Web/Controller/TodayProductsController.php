<?php

namespace App\Web\Controller;

use App\Web\EntryPoint\TodayProductsEntryPoint;
use App\Web\Model\Request\TodayProductRequestModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TodayProductsController
{
    /**
     * @var TodayProductsEntryPoint $todayProductsEntryPoint
     */
    private $todayProductsEntryPoint;
    /**
     * TodayProductsController constructor.
     * @param TodayProductsEntryPoint $todayProductsEntryPoint
     */
    public function __construct(
        TodayProductsEntryPoint $todayProductsEntryPoint
    ) {
        $this->todayProductsEntryPoint = $todayProductsEntryPoint;
    }
    /**
     * @param TodayProductRequestModel $model
     * @return JsonResponse
     * @throws \App\Symfony\Exception\HttpException
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getTodaysProducts(TodayProductRequestModel $model): Response
    {
        $todaysProducts = $this->todayProductsEntryPoint->getTodaysProducts($model);

        $response = new JsonResponse($todaysProducts);

        $response->setCache([
            'max_age' => 60 * 60,
        ]);

        return $response;
    }
}