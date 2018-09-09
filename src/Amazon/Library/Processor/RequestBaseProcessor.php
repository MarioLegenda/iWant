<?php

namespace App\Amazon\Library\Processor;

use App\Amazon\Library\Information\SiteIdInformation;
use App\Library\Tools\LockedImmutableHashSet;
use App\Library\Processor\ProcessorInterface;

class RequestBaseProcessor implements ProcessorInterface
{
    /**
     * @var LockedImmutableHashSet $amazonProductAdvertisingApiMetadata
     */
    private $amazonProductAdvertisingApiMetadata;
    /**
     * @var LockedImmutableHashSet $options
     */
    private $options;
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * FindingApiRequestBaseProcessor constructor.
     * @param iterable $amazonProductAdvertisingApiMetadata
     */
    public function __construct(
        iterable $amazonProductAdvertisingApiMetadata
    ) {
        $this->amazonProductAdvertisingApiMetadata = LockedImmutableHashSet::create($amazonProductAdvertisingApiMetadata);
    }
    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->amazonProductAdvertisingApiMetadata['private_key'];
    }
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface
    {
        if (!$this->options instanceof LockedImmutableHashSet) {
            $message = sprintf(
                'Options have to be set for %s',
                RequestBaseProcessor::class
            );

            throw new \RuntimeException($message);
        }

        $siteId = $this->options['siteId'];

        if (!SiteIdInformation::instance()->has($siteId)) {
            $message = sprintf(
                'Non existent site id \'%s\' given for Amazon api',
                $siteId
            );

            throw new \RuntimeException($message);
        }

        $siteId = SiteIdInformation::instance()->get($siteId);
        $baseUrl = $this->amazonProductAdvertisingApiMetadata['base_url'];
        $names = $this->amazonProductAdvertisingApiMetadata['names'];
        $configParams = $this->amazonProductAdvertisingApiMetadata['params']->toArray();
        $userParams = $this->options->toArray();

        /** Prepend the locale to base url. Call will not work because the url is in an invalid state without it */
        $baseUrl.=$siteId.'/onca/xml?';

        foreach ($names as $key => $name) {
            $currentProduct = '';
            if (array_key_exists($key, $configParams) and is_string($configParams[$key])) {
                $currentProduct.=sprintf(
                    '%s=%s',
                    $name,
                    $configParams[$key]
                );
            }

            if (array_key_exists($key, $configParams)) {
                $param = $configParams[$key];

                if (is_null($param)) {
                    $currentProduct=sprintf(
                        '%s',
                        $name,
                        $configParams[$key]
                    );
                }
            }

            if (array_key_exists($key, $userParams)) {
                $currentProduct=sprintf(
                    '%s=%s',
                    $name,
                    $userParams[$key]
                );
            }

            if (!empty($currentProduct)) {
                $baseUrl.=$currentProduct.'&';
            }
        }

        $this->processed =  rtrim($baseUrl, '&');

        return $this;
    }
    /**
     * @param LockedImmutableHashSet $options
     * @return ProcessorInterface
     */
    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface
    {
        if ($options->isEmpty()) {
            $message = sprintf(
                'Options cannot be empty for class %s',
                RequestBaseProcessor::class
            );

            throw new \RuntimeException($message);
        }

        $this->options = $options;

        return $this;
    }
    /**
     * @return string
     */
    public function getProcessed(): string
    {
        return $this->processed;
    }

    public function getDelimiter(): string
    {
    }
}