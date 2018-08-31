<?php

namespace App\Tests\Ebay\FindingApi\Unit;

use App\Tests\Library\BasicSetup;
use App\Ebay\Library\Processor\ShoppingApiRequestBaseProcessor;

class ShoppingApiRequestBaseProcessorTest extends BasicSetup
{
    public function test_shopping_api_request_base_processor()
    {
        /** @var ShoppingApiRequestBaseProcessor $requestBase */
        $requestBase = $this->locator->get(ShoppingApiRequestBaseProcessor::class);

        $baseUrl = $requestBase
            ->process()
            ->getProcessed();

        static::assertInternalType('string', $baseUrl);
        static::assertNotEmpty($baseUrl);
    }
}