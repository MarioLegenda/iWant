<?php

namespace App\Etsy\Presentation\EntryPoint;

use App\Etsy\Business\Finder;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Presentation\Model\EtsyApiModel;

class EtsyApiEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * EtsyApiEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findAllListingActive(EtsyApiModel $model): EtsyApiResponseModelInterface
    {
        return $this->finder->findAllListingActive($model);
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
     */
    public function findAllShopListingsFeatured(EtsyApiModel $model): EtsyApiResponseModelInterface
    {
        return $this->finder->findAllShopListingsFeatured($model);
    }
}