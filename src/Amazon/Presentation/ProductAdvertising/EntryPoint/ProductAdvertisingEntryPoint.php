<?php

namespace App\Amazon\Presentation\ProductAdvertising\EntryPoint;

use App\Amazon\Business\Finder;
use App\Amazon\Presentation\ProductAdvertising\Model\ProductAdvertisingApiModel;

class ProductAdvertisingEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * ProductAdvertisingEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
    /**
     * @param ProductAdvertisingApiModel $model
     */
    public function search(ProductAdvertisingApiModel $model)
    {
        $this->finder->search($model);
    }
}