<?php

namespace App\Ebay\Presentation\FindingApi\EntryPoint;

use App\Ebay\Business\Finder;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;

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
     * @return FindingApiResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findItemsByKeywords(FindingApiRequestModelInterface $model): FindingApiResponseModelInterface
    {
        return $this->finder->findItemsByKeywords($model);
    }

    public function findItemsAdvanced(FindingApiRequestModelInterface $model): FindingApiResponseModelInterface
    {
        return $this->finder->findItemsAdvanced($model);
    }
}