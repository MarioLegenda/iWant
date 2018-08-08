<?php

namespace App\Amazon\Business;

use App\Amazon\Library\Information\SiteIdInformation;
use App\Amazon\Library\Processor\CascadingSignatureProcessor;
use App\Amazon\Library\Processor\ItemFiltersProcessor;
use App\Amazon\Library\Processor\RequestBaseProcessor;
use App\Amazon\Library\Processor\Signature\FinalProcessor;
use App\Amazon\Library\Processor\Signature\HMACEncoder;
use App\Amazon\Library\Processor\Signature\LineBreakProcessor;
use App\Amazon\Library\Processor\Signature\ParametersSplitProcessor;
use App\Amazon\Library\Processor\Signature\RejoinAmpersandProcessor;
use App\Amazon\Library\Processor\Signature\SignatureData;
use App\Amazon\Library\Processor\Signature\SortProcessor;
use App\Amazon\Library\Processor\Signature\UrlEncodeProcessor;
use App\Amazon\Library\RequestProducer;
use App\Amazon\Presentation\ProductAdvertising\Model\ProductAdvertisingApiModel;
use App\Amazon\Source\ProductAdvertisingApiSource;
use App\Library\Tools\LockedImmutableHashSet;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Processor\ProcessorInterface;
use App\Library\Util\Util;

class Finder
{
    /**
     * @var RequestBaseProcessor $baseProcessor
     */
    private $baseProcessor;
    /**
     * @var ProductAdvertisingApiSource $source
     */
    private $source;
    /**
     * Finder constructor.
     * @param RequestBaseProcessor $baseProcessor
     * @param ProductAdvertisingApiSource $apiSource
     */
    public function __construct(
        RequestBaseProcessor $baseProcessor,
        ProductAdvertisingApiSource $apiSource
    ) {
        $this->baseProcessor = $baseProcessor;
        $this->source = $apiSource;
    }
    /**
     * @param ProductAdvertisingApiModel $model
     */
    public function search(ProductAdvertisingApiModel $model)
    {
        $processors = TypedArray::create('integer', ProcessorInterface::class);

        $processors[] = $this->createRequestBaseProcessor($model);
        $processors[] = $this->createItemFiltersProcessor($model);

        $requestProducer = new RequestProducer($processors);

        $signatureProcessor = $this->createSignatureProcessor($requestProducer->produce());

        $this->source->getProductAdvertisingResource($signatureProcessor->process()->getProcessed());
    }
    /**
     * @param string $producedUrl
     * @return ProcessorInterface
     */
    private function createSignatureProcessor(string $producedUrl): ProcessorInterface
    {
        $privateKey = $this->baseProcessor->getPrivateKey();
        $host = explode('?', $producedUrl)[0];

        $signatureData = new SignatureData([
            'url' => $producedUrl,
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

        return new CascadingSignatureProcessor($signatureData, $processors);
    }
    /**
     * @param ProductAdvertisingApiModel $model
     * @return ProcessorInterface
     */
    private function createRequestBaseProcessor(ProductAdvertisingApiModel $model): ProcessorInterface
    {
        $userParams = [
            'operation' => $model->getOperation()->getValue(),
            'siteId' => SiteIdInformation::AU,
            'timestamp' => Util::toGmDateAmazonTimestamp(),
        ];

        $options = LockedImmutableHashSet::create($userParams);

        $this->baseProcessor->setOptions($options);

        return $this->baseProcessor;
    }
    /**
     * @param ProductAdvertisingApiModel $model
     * @return ProcessorInterface
     */
    private function createItemFiltersProcessor(ProductAdvertisingApiModel $model): ProcessorInterface
    {
        $itemFiltersProcessor = new ItemFiltersProcessor(
            $model->getItemFilters()
        );

        return $itemFiltersProcessor;
    }
}