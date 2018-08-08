<?php

namespace App\Tests\Ebay\FindingApi\Unit;

use App\Ebay\Library\Processor\RequestBaseProcessor;
use App\Library\Tools\LockedImmutableHashSet;
use App\Ebay\Library\Type\OperationType;
use App\Ebay\Library\Type\ResponseDataFormatType;
use App\Tests\Library\BasicSetup;

class RequestBaseProcessorTest extends BasicSetup
{
    public function test_request_base()
    {
        /** @var RequestBaseProcessor $requestBase */
        $requestBase = $this->locator->get(RequestBaseProcessor::class);

        $params = [
            'operation_name' => (string) OperationType::fromKey('FindItemsByKeywords'),
            'service_version' => '1.0.0',
            'response_data_format' => (string) ResponseDataFormatType::fromKey('xml')
        ];

        $userParams = LockedImmutableHashSet::create($params);

        $baseUrl = $requestBase
            ->setOptions($userParams)
            ->process()
            ->getProcessed();

        static::assertInternalType('string', $baseUrl);
        static::assertNotEmpty($baseUrl);
    }
}