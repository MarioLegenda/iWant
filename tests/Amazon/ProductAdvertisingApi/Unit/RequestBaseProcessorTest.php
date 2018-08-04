<?php

namespace App\Tests\Amazon\ProductAdvertisingApi\Unit;

use App\Amazon\Library\Information\SiteIdInformation;
use App\Ebay\Library\Tools\LockedImmutableHashSet;
use App\Tests\Library\BasicSetup;
use App\Amazon\Library\Processor\RequestBaseProcessor;

class RequestBaseProcessorTest extends BasicSetup
{
    public function test_request_base_processor()
    {
        /** @var RequestBaseProcessor $requestBase */
        $requestBase = $this->locator->get(RequestBaseProcessor::class);

        $userParams = [
            'siteId' => SiteIdInformation::AU
        ];

        $options = LockedImmutableHashSet::create($userParams);

        $baseUrl = $requestBase
            ->setOptions($options)
            ->process()
            ->getProcessed();

        static::assertInternalType('string', $baseUrl);
        static::assertNotEmpty($baseUrl);
    }
}