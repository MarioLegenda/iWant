<?php

namespace App\Ebay\Presentation\ShoppingApi\EntryPoint;

use App\Ebay\Business\Finder;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Library\Response\ResponseModelInterface;

class ShoppingApiEntryPoint
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
     * @param ShoppingApiRequestModelInterface $model
     * @return ResponseModelInterface
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     */
    public function getCategoryInfo(ShoppingApiRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->getCategoryInfo($model);
    }
    /**
     * @param ShoppingApiRequestModelInterface $model
     * @return ResponseModelInterface
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     */
    public function getUserProfile(ShoppingApiRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->getUserProfile($model);
    }
    /**
     * @param ShoppingApiRequestModelInterface $model
     * @return ResponseModelInterface
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     */
    public function getSingleItem(ShoppingApiRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->getSingleItem($model);
    }
    /**
     * @param ShoppingApiRequestModelInterface $model
     * @return ResponseModelInterface
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     */
    public function getShippingCosts(ShoppingApiRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->getShippingCosts($model);
    }
}