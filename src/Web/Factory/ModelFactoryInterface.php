<?php

namespace App\Web\Factory;

use App\Bonanza\Library\Response\BonanzaApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Web\Model\Response\UniformedResponseModel;

interface ModelFactoryInterface
{
    /**
     * @param FindingApiResponseModelInterface|EtsyApiResponseModelInterface|BonanzaApiResponseModelInterface $model
     * @return TypedArray
     */
    public function createModels($model): TypedArray;
}