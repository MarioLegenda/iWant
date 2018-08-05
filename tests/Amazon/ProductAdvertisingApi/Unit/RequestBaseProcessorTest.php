<?php

namespace App\Tests\Amazon\ProductAdvertisingApi\Unit;

use App\Amazon\Library\Information\SiteIdInformation;
use App\Amazon\Library\Processor\CascadingSignatureProcessor;
use App\Amazon\Library\Processor\Signature\FinalProcessor;
use App\Amazon\Library\Processor\Signature\HMACEncoder;
use App\Amazon\Library\Processor\Signature\LineBreakProcessor;
use App\Amazon\Library\Processor\Signature\ParametersSplitProcessor;
use App\Amazon\Library\Processor\Signature\RejoinAmpersandProcessor;
use App\Amazon\Library\Processor\Signature\SignatureData;
use App\Amazon\Library\Processor\Signature\SortProcessor;
use App\Amazon\Library\Processor\Signature\UrlEncodeProcessor;
use App\Ebay\Library\Tools\LockedImmutableHashSet;
use App\Library\Util\Util;
use App\Tests\Library\BasicSetup;
use App\Amazon\Library\Processor\RequestBaseProcessor;

class RequestBaseProcessorTest extends BasicSetup
{
    public function test_request_base_processor()
    {
        /** @var RequestBaseProcessor $requestBase */
        $requestBase = $this->locator->get(RequestBaseProcessor::class);

        $userParams = [
            'siteId' => SiteIdInformation::AU,
            'timestamp' => Util::toGmDateAmazonTimestamp(),
        ];

        $options = LockedImmutableHashSet::create($userParams);

        $baseUrl = $requestBase
            ->setOptions($options)
            ->process()
            ->getProcessed();

        static::assertInternalType('string', $baseUrl);
        static::assertNotEmpty($baseUrl);
    }

    public function test_signature_processors()
    {
        $url = 'http://webservices.amazon.com/onca/xml?Service=AWSECommerceService&AWSAccessKeyId=AKIAIOSFODNN7EXAMPLE&AssociateTag=mytag-20&Operation=ItemLookup&ItemId=0679722769&ResponseGroup=Images,ItemAttributes,Offers,Reviews&Version=2013-08-01&Timestamp=2014-08-18T12:00:00Z';

        $host = explode('?', $url)[0];
        $privateKey = '1234567890';

        $signatureData = new SignatureData([
            'url' => $url,
            'host' => $host,
            'private_key' => $privateKey,
        ]);

        $processors = [
            new UrlEncodeProcessor([
                ',' => '%2C',
                ':' => '%3A',
            ]),
            new ParametersSplitProcessor(),
            new SortProcessor(),
            new RejoinAmpersandProcessor(),
            new LineBreakProcessor(),
            new HMACEncoder([
                '+' => '%2B',
                '=' => '%3D'
            ]),
            new FinalProcessor()
        ];

        $cascadingSignatureProcessor = new CascadingSignatureProcessor($signatureData, $processors);

        $cascadingSignatureProcessor->process();

        $processed = $cascadingSignatureProcessor->getProcessed();

        $correctAmazonUrl = 'http://webservices.amazon.com/onca/xml?AWSAccessKeyId=AKIAIOSFODNN7EXAMPLE&AssociateTag=mytag-20&ItemId=0679722769&Operation=ItemLookup&ResponseGroup=Images%2CItemAttributes%2COffers%2CReviews&Service=AWSECommerceService&Timestamp=2014-08-18T12%3A00%3A00Z&Version=2013-08-01&Signature=j7bZM0LXZ9eXeZruTqWm2DIvDYVUU3wxPPpp%2BiXxzQc%3D';

        static::assertEquals($processed, $correctAmazonUrl);
    }
}