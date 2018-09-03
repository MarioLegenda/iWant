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
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getCategoryInfo(ShoppingApiRequestModelInterface $model): ResponseModelInterface
    {
        return $this->finder->getCategoryInfo($model);
    }
}