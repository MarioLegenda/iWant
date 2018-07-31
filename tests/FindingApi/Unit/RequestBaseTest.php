<?php

namespace App\Tests\FindingApi\Unit;

use App\Ebay\Library\RequestBase;
use App\Ebay\Library\Tools\LockedImmutableHashSet;
use App\Ebay\Library\Type\OperationType;
use App\Ebay\Library\Type\ResponseDataFormatType;
use App\Tests\Library\BasicSetup;

class RequestBaseTest extends BasicSetup
{
    public function test_request_base()
    {
        /** @var RequestBase $requestBase */
        $requestBase = $this->locator->get(RequestBase::class);

        $params = [
            'operation_name' => (string) OperationType::fromKey('FindItemsByKeywords'),
            'service_version' => '1.0.0',
            'response_data_format' => (string) ResponseDataFormatType::fromKey('xml')
        ];

        $userParams = LockedImmutableHashSet::create($params);

        $baseUrl = $requestBase->getBaseUrl($userParams);

        static::assertInternalType('string', $baseUrl);
        static::assertNotEmpty($baseUrl);
    }
}