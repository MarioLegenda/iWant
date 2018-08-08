<?php

namespace App\Amazon\Library\Response\ProductAdvertisingApi;

use App\Amazon\Library\Response\ProductAdvertisingApi\ResponseItem\Items;
use App\Amazon\Library\Response\ProductAdvertisingApi\ResponseItem\RequestProcessingTimeItem;
use App\Amazon\Library\Response\ProductAdvertisingApi\ResponseItem\RootItem;
use App\Amazon\Library\Response\ProductAdvertisingApi\ResponseItem\OperationRequest\OperationRequestItem;

interface ProductAdvertisingApiResponseInterface
{
    public function getRoot(): RootItem;

    public function getOperationRequestItem(): OperationRequestItem;

    public function getItems(): Items;
}